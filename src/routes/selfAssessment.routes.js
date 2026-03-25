const express = require('express');
const { getQuestions, submitAssessment, getHistory, getAssessmentById } = require('../controllers/selfAssessment.controller');
const { protect, authorize } = require('../middleware/auth');

const router = express.Router();

router.use(protect);
// Everyone can get questions and history (controllers handle ownership/filtering)
router.get('/questions', authorize('patient', 'psychiatrist', 'psychologist', 'nurse', 'social_worker', 'counselor', 'hospital', 'admin', 'super_admin'), getQuestions);
router.get('/history', authorize('patient', 'psychiatrist', 'psychologist', 'nurse', 'social_worker', 'counselor', 'hospital', 'admin', 'super_admin'), getHistory);
router.get('/history/:patientId', authorize('patient', 'psychiatrist', 'psychologist', 'nurse', 'social_worker', 'counselor', 'hospital', 'admin', 'super_admin'), getHistory);
router.get('/patient/:patientId', authorize('patient', 'psychiatrist', 'psychologist', 'nurse', 'social_worker', 'counselor', 'hospital', 'admin', 'super_admin'), getHistory);

// Get specific assessment details
router.get('/:id', authorize('patient', 'psychiatrist', 'psychologist', 'nurse', 'social_worker', 'counselor', 'hospital', 'admin', 'super_admin'), getAssessmentById);

// Only patients can submit self-assessments
router.post('/submit', authorize('patient'), submitAssessment);

module.exports = router;
