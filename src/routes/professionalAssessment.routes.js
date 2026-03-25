const express = require('express');
const { getQuestions, submitAssessment, getPatientHistory } = require('../controllers/professionalAssessment.controller');
const { protect, authorize } = require('../middleware/auth');

const router = express.Router();

router.use(protect);
// Questions and History accessible to everyone (ownership handled in controller)
router.get('/questions', authorize('psychiatrist', 'psychologist', 'nurse', 'social_worker', 'counselor', 'hospital', 'admin', 'super_admin', 'patient'), getQuestions);
router.get('/patient/:patientId', authorize('psychiatrist', 'psychologist', 'nurse', 'social_worker', 'counselor', 'hospital', 'admin', 'super_admin', 'patient'), getPatientHistory);
router.get('/history/:patientId', authorize('psychiatrist', 'psychologist', 'nurse', 'social_worker', 'counselor', 'hospital', 'admin', 'super_admin', 'patient'), getPatientHistory);

// Only Staff can submit professional assessments
router.post('/submit', authorize('psychiatrist', 'psychologist', 'nurse', 'social_worker', 'counselor', 'hospital', 'admin', 'super_admin'), submitAssessment);

module.exports = router;
