const Message = require('../models/Message');
const Consult = require('../models/Consult');
const ChiefComplaint = require('../models/ChiefComplaint');
const HPI = require('../models/HPI');
const MSE = require('../models/MSE');
const openAIService = require('../services/OpenAIService');
const logger = require('../config/logger');

/**
 * @desc    Consultation Chat Handler (2 humans + AI)
 */
const consultationHandler = (io, socket) => {
    const user = socket.user;

    // 1. Join Consultation Room
    socket.on('join_consultation', async (consult_id) => {
        try {
            if (!consult_id) {
                return socket.emit('error', { message: 'Consultation ID is required' });
            }

            // Verify if user is part of this consult
            const consult = await Consult.findOne({ consult_id: consult_id });
            if (!consult) {
                return socket.emit('error', { message: 'Invalid consultation ID' });
            }

            socket.join(`consultation_${consult_id}`);
            logger.info(`User ${user.firstName} joined consultation_${consult_id}`);

            // Notify others
            socket.to(`consultation_${consult_id}`).emit('user_joined', {
                user_id: user._id,
                name: `${user.firstName} ${user.lastName}`,
                role: user.role
            });

        } catch (err) {
            logger.error('Socket Join Error: %s', err.message);
            socket.emit('error', { message: 'Failed to join consultation room' });
        }
    });

    // 2. Send Message
    socket.on('send_message', async (data) => {
        try {
            const { consult_id, content } = data;

            if (!consult_id || !content) {
                return socket.emit('error', { message: 'consult_id and content are required' });
            }

            // Save to Database
            const newMessage = await Message.create({
                consult_id,
                sender_id: user._id,
                sender_role: user.role, // 'patient' or 'doctor'
                sender_name: `${user.firstName} ${user.lastName}`,
                content
            });

            // Broadcast to the room
            io.to(`consultation_${consult_id}`).emit('new_message', newMessage);

            // Auto-trigger AI if keyword detected (e.g., "@skyheal") or specifically requested
            if (content.toLowerCase().includes('@skyheal')) {
                triggerAI(consult_id, io);
            }

        } catch (err) {
            logger.error('Socket Send Error: %s', err.message);
            socket.emit('error', { message: 'Failed to send message' });
        }
    });

    // 3. Trigger AI Manually
    socket.on('trigger_ai', async (consult_id) => {
        triggerAI(consult_id, io);
    });

    // 4. Get History
    socket.on('get_history', async (consult_id) => {
        try {
            const history = await Message.find({ consult_id }).sort({ timestamp: 1 }).limit(50);
            socket.emit('chat_history', history);
        } catch (err) {
            socket.emit('error', { message: 'Failed to fetch history' });
        }
    });
};

/**
 * Helper to trigger AI response in a room
 */
async function triggerAI(consult_id, io) {
    try {
        logger.info(`AI Triggered for consultation: ${consult_id}`);

        // 1. Fetch History
        const messages = await Message.find({ consult_id }).sort({ timestamp: -1 }).limit(10);
        const history = messages.reverse();

        // 2. Fetch Clinical Context
        const consult = await Consult.findOne({ consult_id });
        const patientId = consult.patient;

        const [cc, hpi, mse] = await Promise.all([
            ChiefComplaint.findOne({ patient: patientId }).sort({ createdAt: -1 }),
            HPI.findOne({ patient: patientId }).sort({ createdAt: -1 }),
            MSE.findOne({ patient: patientId }).sort({ createdAt: -1 })
        ]);

        const clinicalContext = { chief_complaint: cc, hpi, mse };

        // 3. Call AI
        const aiResponse = await openAIService.consultationAssistant(history, clinicalContext);

        // 4. Save AI message
        const aiMessage = await Message.create({
            consult_id,
            sender_id: null,
            sender_role: 'ai',
            sender_name: 'Skyheal AI',
            content: aiResponse.content
        });

        // 5. Broadcast
        io.to(`consultation_${consult_id}`).emit('new_message', aiMessage);

    } catch (err) {
        logger.error('AI Error in consultation: %s', err.message);
        io.to(`consultation_${consult_id}`).emit('chat_error', { message: 'AI Assistant failed' });
    }
}

module.exports = consultationHandler;
