const openAIService = require('../services/OpenAIService');
const { sendSuccess, sendError } = require('../utils/responseHelper');
const { GetObjectCommand } = require('@aws-sdk/client-s3');
const { s3 } = require('../services/S3Service');
const logger = require('../config/logger');

/**
 * Helper to convert stream to buffer (AWS SDK v3 returns Body as stream)
 */
const streamToBuffer = async (stream) => {
    return new Promise((resolve, reject) => {
        const chunks = [];
        stream.on('data', (chunk) => chunks.push(chunk));
        stream.on('error', reject);
        stream.on('end', () => resolve(Buffer.concat(chunks)));
    });
};

/**
 * @desc    Transcribe audio file to text (Common Service)
 * @route   POST /api/v1/ai/transcribe
 * @access  Private
 */
exports.transcribeAudio = async (req, res, next) => {
    try {
        if (!req.file) {
            return sendError(res, 400, 'Audio file is required');
        }

        logger.info(`Common Transcription started for File: ${req.file.originalname}`);

        // 1. Clear Whisper Transcription using the memory buffer directly
        const text = await openAIService.transcribeAudio(req.file.buffer, req.file.originalname);

        sendSuccess(res, 200, 'Audio transcribed successfully', {
            text: text
        });
    } catch (err) {
        next(err);
    }
};
