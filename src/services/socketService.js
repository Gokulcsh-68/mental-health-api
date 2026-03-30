const socketio = require('socket.io');
const jwt = require('jsonwebtoken');
const User = require('../models/User');
const logger = require('../config/logger');
const config = require('../config/config');
const chatHandler = require('../handlers/chat.socket');
const chatbotHandler = require('../handlers/chatbot.socket');
const userHandler = require('../handlers/user.socket');

class SocketService {
    constructor() {
        this.io = null;
    }

    /**
     * @desc    Initialize Socket.io with HTTP server
     */
    init(server) {
        this.io = socketio(server, {
            cors: {
                origin: config.CORS_ORIGIN,
                methods: ['GET', 'POST']
            }
        });

        // Authentication Middleware
        this.io.use(async (socket, next) => {
            try {
                const token = socket.handshake.query.auth || socket.handshake.auth.token;

                if (!token) {
                    return next(new Error('Authentication error: Token missing'));
                }

                const decoded = jwt.verify(token, config.JWT_SECRET);
                const user = await User.findById(decoded._id).select('-password');

                if (!user) {
                    return next(new Error('Authentication error: User not found'));
                }

                socket.user = user;
                next();
            } catch (err) {
                logger.error('Socket Auth Error: %s', err.message);
                next(new Error('Authentication error: Invalid token'));
            }
        });

        logger.info('Socket.io initialized with authentication');

        this.io.on('connection', (socket) => {
            logger.info(`Auth client connected: ${socket.id} (User: ${socket.user.firstName} ${socket.user.lastName})`);

            // Debug: Log all incoming events
            socket.onAny((eventName, ...args) => {
                logger.info(`Socket Event Received: ${eventName} from ${socket.id}`);
            });

            // Register Handlers
            chatHandler(this.io, socket);
            chatbotHandler(this.io, socket);
            userHandler(this.io, socket);

            // Ping test
            socket.on('ping_test', () => {
                logger.info(`Ping received from ${socket.id}`);
                socket.emit('pong_test', { time: new Date() });
            });

            socket.on('disconnect', () => {
                logger.debug(`Client disconnected: ${socket.id}`);
            });
        });

        return this.io;
    }

    /**
     * @desc    Emit event to a specific consultation room
     */
    emitToConsultation(consultId, event, data) {
        if (this.io) {
            this.io.to(`consultation_${consultId}`).emit(event, data);
        }
    }

    /**
     * @desc    Emit event to a specific user (via their personal room)
     */
    emitToUser(userId, event, data) {
        if (this.io) {
            this.io.to(userId.toString()).emit(event, data);
        }
    }

    getIO() {
        return this.io;
    }
}

module.exports = new SocketService();
