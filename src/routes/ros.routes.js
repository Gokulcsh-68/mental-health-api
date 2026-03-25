const express = require('express');
const router = express.Router();
const rosController = require('../controllers/ros.controller');
const { protect } = require('../middleware/auth');

// Get ROS questionnaire form structure
router.get('/questions', protect, rosController.getROSQuestions);

router.route('/')
    .post(protect, rosController.createROS)
    .get(protect, rosController.getROS);

router.get('/:id', protect, rosController.getROSById);

module.exports = router;
