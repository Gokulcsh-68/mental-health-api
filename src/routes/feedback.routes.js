const express = require('express');
const router = express.Router();
const feedbackController = require('../controllers/feedback.controller');
const { protect, authorize } = require('../middleware/auth');

// Public or Protected submission
router.post('/', (req, res, next) => {
    // Optional protect: if token present, use it, else submit as guest
    next();
}, feedbackController.submitFeedback);

// Administrative management
router.use(protect);
router.get('/my', feedbackController.getMyFeedback);
router.get('/latest-rating', feedbackController.getLatestRating);
router.post('/rate', feedbackController.rateApp);

router.get('/', protect, authorize('super_admin'), feedbackController.getAllFeedback);
router.put('/:id', protect, authorize('super_admin'), feedbackController.updateFeedback);

module.exports = router;
