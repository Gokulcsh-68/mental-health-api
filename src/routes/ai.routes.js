const express = require('express');
const multer = require('multer');
const { transcribeAudio, chatStream } = require('../controllers/ai.controller');
const { protect } = require('../middleware/auth');

// ── Local Multer Setup (No S3) ──────────────────────────────
const memoryUpload = multer({
    storage: multer.memoryStorage(),
    fileFilter: (req, file, cb) => {
        if (file.mimetype.startsWith('audio/')) {
            cb(null, true);
        } else {
            cb(new Error('Invalid file type. Only audio files are allowed.'), false);
        }
    },
    limits: {
        fileSize: 25 * 1024 * 1024 // 25MB limit for transcription
    }
});

const router = express.Router();

/**
 * @route   POST /api/v1/ai/transcribe
 * @desc    Common transcription endpoint
 * @access  Private
 */
router.post('/transcribe', protect, memoryUpload.single('audio'), transcribeAudio);

/**
 * @route   POST /api/v1/ai/chat-stream
 * @desc    Streaming AI Chat (SSE)
 * @access  Private
 */
router.post('/chat-stream', protect, chatStream);

module.exports = router;
