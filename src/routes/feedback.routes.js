const express = require('express');
const router = express.Router();
const feedbackController = require('../controllers/feedback.controller');
const { protect, authorize, optionalProtect } = require('../middleware/auth');

// Public or Protected submission
router.post('/', optionalProtect, feedbackController.submitFeedback);

// User-specific feedback history and rating
router.get('/my', protect, feedbackController.getMyFeedback);
router.get('/latest-rating', protect, feedbackController.getLatestRating);
router.post('/rate', protect, feedbackController.rateApp);

// Administrative management
router.get('/', protect, authorize('super_admin'), feedbackController.getAllFeedback);
router.put('/:id', protect, authorize('super_admin'), feedbackController.updateFeedback);

module.exports = router;
