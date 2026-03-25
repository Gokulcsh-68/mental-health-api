const openAIService = require('../services/OpenAIService');
const ChiefComplaint = require('../models/ChiefComplaint');
const HPI = require('../models/HPI');
const User = require('../models/User');
const logger = require('../config/logger');

/**
 * @desc    Chatbot Socket Handler
 * @param   {Object} io - Socket.io instance
 * @param   {Object} socket - Client socket connection
 */
const chatbotHandler = (io, socket) => {
    logger.info(`Registering chatbotHandler for socket: ${socket.id}`);

    // Handle chat query from patient
    socket.on('chat_query', async (data) => {
        try {
            const { patientId, messages } = data;

            if (!patientId || !messages) {
                return socket.emit('chat_error', { message: 'Patient ID and messages are required' });
            }

            logger.info(`Chat query received for patient: ${patientId}`);

            // 1. Fetch Patient Info
            let patient;
            if (/^[0-9a-fA-F]{24}$/.test(String(patientId))) {
                patient = await User.findById(patientId).select('firstName lastName gender dateOfBirth');
            } else {
                patient = await User.findOne({ userId: patientId }).select('firstName lastName gender dateOfBirth');
            }

            if (!patient) {
                logger.warn(`Chatbot: Patient ${patientId} not found`);
                return socket.emit('chat_error', { message: `Patient ${patientId} not found` });
            }

            logger.info(`Chatbot: Patient found: ${patient.firstName} ${patient.lastName}`);

            // Calculate age
            const dob = patient.dateOfBirth;
            const age = dob ? Math.floor((Date.now() - new Date(dob).getTime()) / (1000 * 60 * 60 * 24 * 365.25)) : null;

            // 2. Fetch Latest Clinical Context
            logger.info(`Chatbot: Fetching clinical context for patient ${patient._id}`);
            const [chiefComplaint, hpi] = await Promise.all([
                ChiefComplaint.findOne({ patient: patient._id }).sort({ createdAt: -1 }),
                HPI.findOne({ patient: patient._id }).sort({ createdAt: -1 })
            ]);
            logger.info(`Chatbot: Context fetched. CC: ${!!chiefComplaint}, HPI: ${!!hpi}`);

            const clinicalContext = { chief_complaint: chiefComplaint, hpi };
            const patientInfo = {
                name: `${patient.firstName} ${patient.lastName}`,
                age,
                gender: patient.gender
            };

            // 3. Call OpenAI Service
            logger.info(`Chatbot: Calling OpenAI Service (Model: ${openAIService.model})...`);
            const aiResponse = await openAIService.chatWithPatient(messages, clinicalContext, patientInfo);
            logger.info(`Chatbot: OpenAI response received successfully.`);

            // 4. Emit response back to the client
            socket.emit('chat_response', aiResponse);
            logger.info(`Chatbot: Response emitted to socket ${socket.id}`);

        } catch (error) {
            logger.error(`Chat Socket Error: ${error.message}`);
            logger.error(error.stack);
            socket.emit('chat_error', { message: 'An error occurred while processing your request' });
        }
    });
};

module.exports = chatbotHandler;
