const express = require('express');
const router = express.Router();
const advancedController = require('../controllers/advancedAssessment.controller');
const { protect } = require('../middleware/auth');

router.get('/:type/questions', protect, advancedController.getQuestions);
router.post('/:type', protect, advancedController.submitAssessment);

module.exports = router;
