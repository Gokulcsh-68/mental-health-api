const express = require('express');
const { transcribeAudio } = require('../controllers/ai.controller');
const { protect } = require('../middleware/auth');
const { memoryUpload } = require('../services/S3Service');

const router = express.Router();

/**
 * @route   POST /api/v1/ai/transcribe
 * @desc    Common transcription endpoint
 * @access  Private
 */
router.post('/transcribe', protect, memoryUpload.single('audio'), transcribeAudio);

module.exports = router;
