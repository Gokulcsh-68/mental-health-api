const Message = require('../models/Message');
const Consult = require('../models/Consult');
const ChiefComplaint = require('../models/ChiefComplaint');
const HPI = require('../models/HPI');
const MSE = require('../models/MSE');
const openAIService = require('../services/OpenAIService');
const logger = require('../config/logger');

/**
 * @desc    Unified Chat Handler (Private AI, Group Chat, Consultation)
 */
const chatHandler = (io, socket) => {
    const user = socket.user;

    // 1. Join a Room (Private AI or Shared Group)
    socket.on('join_room', async (data) => {
        try {
            const { room_id, room_type } = data; // room_type: 'private_ai', 'group_chat', 'consultation'

            if (!room_id || !room_type) {
                return socket.emit('error', { message: 'room_id and room_type are required' });
            }

            // In private AI mode, room_id is usually the user's ID
            // In group/consult mode, we verify permissions if needed
            if (room_type === 'consultation' || room_type === 'group_chat') {
                const consult = await Consult.findOne({ consult_id: room_id });
                if (!consult) {
                    // If not a formal consult, we still allow group joining for general mental health discussion
                    logger.debug(`Joining general group room: ${room_id}`);
                }
            }

            socket.join(room_id);
            logger.info(`User ${user.firstName} joined ${room_type} room: ${room_id}`);

            // Notify others in group rooms
            if (room_type !== 'private_ai') {
                socket.to(room_id).emit('user_joined', {
                    user_id: user._id,
                    name: `${user.firstName} ${user.lastName}`,
                    role: user.role
                });
            }

        } catch (err) {
            logger.error('Socket Join Error: %s', err.message);
            socket.emit('error', { message: 'Failed to join room' });
        }
    });

    // 2. Send Message (Persist and Broadcast)
    socket.on('send_message', async (data) => {
        try {
            const { room_id, room_type, content, session_id = 'main' } = data;

            if (!room_id || !room_type || !content) {
                return socket.emit('error', { message: 'room_id, room_type, and content are required' });
            }

            // Save to Database
            const newMessage = await Message.create({
                room_id,
                room_type,
                session_id,
                sender_id: user._id,
                sender_role: user.role,
                sender_name: `${user.firstName} ${user.lastName}`,
                content
            });

            // Broadcast to the room
            io.to(room_id).emit('new_message', newMessage);

            // Handle AI responses
            if (room_type === 'private_ai') {
                triggerAI(room_id, room_type, session_id, io);
            } else if (content.toLowerCase().includes('@skyheal')) {
                triggerAI(room_id, room_type, session_id, io);
            }

        } catch (err) {
            logger.error('Socket Send Error: %s', err.message);
            socket.emit('error', { message: 'Failed to send message' });
        }
    });

    // 3. Typing Indicators
    socket.on('typing', (data) => {
        const { room_id } = data;
        socket.to(room_id).emit('user_typing', {
            user_id: user._id,
            name: user.firstName
        });
    });

    socket.on('stop_typing', (data) => {
        const { room_id } = data;
        socket.to(room_id).emit('user_stop_typing', {
            user_id: user._id
        });
    });

    // 4. Clear Chat / New Session
    socket.on('clear_chat', async (data) => {
        try {
            const { room_id, session_id = 'main' } = data;
            await Message.deleteMany({ room_id, session_id });
            socket.emit('chat_cleared', { room_id, session_id });
            logger.info(`Chat cleared for room ${room_id}, session ${session_id}`);
        } catch (err) {
            socket.emit('error', { message: 'Failed to clear chat' });
        }
    });

    // 5. Get History (Session Aware)
    socket.on('get_history', async (data) => {
        try {
            // data can be a string (room_id) or an object { room_id, session_id }
            const room_id = typeof data === 'string' ? data : data.room_id;
            const session_id = data.session_id || 'main';

            const history = await Message.find({ room_id, session_id })
                .sort({ timestamp: 1 })
                .limit(50);

            socket.emit('chat_history', history);
        } catch (err) {
            socket.emit('error', { message: 'Failed to fetch history' });
        }
    });
};

/**
 * Helper to trigger AI response with "Thinking" simulation
 */
async function triggerAI(room_id, room_type, session_id, io) {
    try {
        // 1. Show Typing Indicator
        io.to(room_id).emit('user_typing', {
            user_id: 'ai',
            name: 'Skyheal AI'
        });

        // 2. Fetch History for context (Session Aware)
        const messages = await Message.find({ room_id, session_id })
            .sort({ timestamp: -1 })
            .limit(10);
        const history = messages.reverse();

        // 3. Optional Clinical Context
        let clinicalContext = {};
        if (room_type === 'consultation') {
            const consult = await Consult.findOne({ consult_id: room_id });
            if (consult && consult.patient) {
                const patientId = consult.patient;
                const [cc, hpi, mse] = await Promise.all([
                    ChiefComplaint.findOne({ patient: patientId }).sort({ createdAt: -1 }),
                    HPI.findOne({ patient: patientId }).sort({ createdAt: -1 }),
                    MSE.findOne({ patient: patientId }).sort({ createdAt: -1 })
                ]);
                clinicalContext = { chief_complaint: cc, hpi, mse };
            }
        }

        // 4. Call AI Service
        const aiResponse = await openAIService.mentalHealthAssistant(history, clinicalContext);

        // 5. Small simulated delay to feel more natural
        await new Promise(resolve => setTimeout(resolve, 1500));

        // 6. Stop Typing Indicator
        io.to(room_id).emit('user_stop_typing', { user_id: 'ai' });

        // 7. Save AI message
        const aiMessage = await Message.create({
            room_id,
            room_type,
            session_id,
            sender_id: null,
            sender_role: 'ai',
            sender_name: 'Skyheal AI',
            content: aiResponse.content
        });

        // 8. Broadcast AI Response
        io.to(room_id).emit('new_message', aiMessage);

    } catch (err) {
        logger.error('AI Error in room %s: %s', room_id, err.message);
        io.to(room_id).emit('user_stop_typing', { user_id: 'ai' });
        io.to(room_id).emit('chat_error', { message: 'Our AI expert is resting.' });
    }
}

module.exports = chatHandler;
