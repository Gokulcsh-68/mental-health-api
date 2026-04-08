const openAIService = require('../services/OpenAIService');
const { sendSuccess, sendError } = require('../utils/responseHelper');
const logger = require('../config/logger');

/**
 * @desc    Transcribe audio file to text (Common Service)
 * @route   POST /api/v1/ai/transcribe
 * @access  Private
 */
exports.transcribeAudio = async (req, res, next) => {
    try {
        // Debug: Log info about the file and headers
        console.log('[DEBUG] Content-Type:', req.headers['content-type']);
        if (req.file) {
            console.log('[DEBUG] File Received:', {
                fieldname: req.file.fieldname,
                originalname: req.file.originalname,
                mimetype: req.file.mimetype,
                size: req.file.size
            });
        } else {
            console.log('[DEBUG] No file found in req.file');
        }
        
        if (!req.file) {
            return sendError(res, 400, 'Audio file is required');
        }

        // 1. Check for API Key
        if (!process.env.OPENAI_API_KEY) {
            logger.error('Transcription failed: OPENAI_API_KEY is missing from environment');
            return sendError(res, 503, 'AI Transcription service is currently misconfigured. Please contact support.');
        }

        logger.info(`Common Transcription started for File: ${req.file.originalname}`);

        // 2. Clear Whisper Transcription using the memory buffer directly
        const text = await openAIService.transcribeAudio(req.file.buffer, req.file.originalname);

        sendSuccess(res, 200, 'Audio transcribed successfully', {
            text: text
        });
    } catch (err) {
        next(err);
    }
};
/**
 * @desc    Streaming AI Chat (SSE)
 * @route   POST /api/v1/ai/chat-stream
 * @access  Private
 */
exports.chatStream = async (req, res, next) => {
    try {
        let { messages, clinicalContext } = req.body;

        const user = req.user || {};

        // 0. Handle Welcome Message if history is empty
        if (!messages || messages.length === 0) {
            messages = [
                { 
                    role: 'system', 
                    content: `The user ${user.firstName || 'there'} just opened the chat. 
                              Introduce yourself as MindBalance AI and give a brief, warm 1-sentence welcome. 
                              Ask how you can support them today.` 
                }
            ];
        }

        if (!messages || !Array.isArray(messages)) {
            return sendError(res, 400, 'Messages array is required');
        }

        // 1. Set headers for Server-Sent Events
        res.setHeader('Content-Type', 'text/event-stream');
        res.setHeader('Cache-Control', 'no-cache');
        res.setHeader('Connection', 'keep-alive');
        res.setHeader('X-Accel-Buffering', 'no'); // Prevent Nginx/Render from buffering

        // Flush headers immediately to tell client stream is open
        if (res.flushHeaders) res.flushHeaders();
        res.write('data: {"status": "connected"}\n\n');

        logger.info(`AI Chat Stream started for User: ${req.user?._id || 'anonymous'}`);

        // 2. Start OpenAI stream
        const stream = await openAIService.chatStream(messages, clinicalContext);

        // 3. Process Stream with modern async iterator
        try {
            for await (const chunk of stream) {
                const lines = chunk.toString().split('\n');
                for (let line of lines) {
                    line = line.trim();
                    if (!line || !line.startsWith('data: ')) continue;
                    
                    const message = line.replace(/^data: /, '');
                    if (message === '[DONE]') {
                        res.write('data: [DONE]\n\n');
                        return res.end();
                    }

                    try {
                        const parsed = JSON.parse(message);
                        const content = parsed.choices?.[0]?.delta?.content || '';
                        if (content) {
                            res.write(`data: ${JSON.stringify({ content })}\n\n`);
                        }
                    } catch (e) {
                        // Skip malformed
                    }
                }
            }
        } catch (streamErr) {
            logger.error('Stream processing error: %s', streamErr.message);
            res.write(`data: ${JSON.stringify({ error: 'Stream error during processing' })}\n\n`);
        }

        if (!res.writableEnded) {
            res.write('data: [DONE]\n\n');
            res.end();
        }

        // 4. Handle Client Disconnect
        req.on('close', () => {
            logger.info('Client closed connection to AI Chat Stream');
            if (stream.destroy) stream.destroy();
        });

    } catch (err) {
        logger.error('Chat Stream Controller Error: %s', err.message);
        // If headers already sent, we can't use regular error handler
        if (!res.headersSent) {
            next(err);
        } else {
            res.write(`data: ${JSON.stringify({ error: err.message })}\n\n`);
            res.end();
        }
    }
};
