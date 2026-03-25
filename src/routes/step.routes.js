const express = require('express');
const { logSteps, getStepHistory, getStepStats } = require('../controllers/step.controller');
const { protect } = require('../middleware/auth');

const router = express.Router();

// All step routes require authentication
router.use(protect);

/**
 * @route   POST /api/v1/steps
 * @desc    Log physical steps
 */
router.post('/', logSteps);

/**
 * @route   GET /api/v1/steps
 * @desc    Get step history
 */
router.get('/', getStepHistory);

/**
 * @route   GET /api/v1/steps/stats
 * @desc    Get step aggregate statistics
 */
router.get('/stats', getStepStats);

module.exports = router;
