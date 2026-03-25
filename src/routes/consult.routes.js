const express = require('express');
const { consultTokenValidate } = require('../controllers/consult.controller');
const { protect } = require('../middleware/auth');

const router = express.Router();

/**
 * @route   GET /api/v1/consults/token-validate
 * @desc    Validate a consultation token to get details
 * @access  Private
 */
router.get('/token-validate', protect, consultTokenValidate);

module.exports = router;
