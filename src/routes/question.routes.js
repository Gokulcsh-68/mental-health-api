const express = require('express');
const { getAssessmentQuestions, createQuestion, getAllQuestions, getPatientSelfAssessments, getChildQuestions, submitChildAnswer, getChildAssessmentHistory } = require('../controllers/question.controller');
const { protect, authorize } = require('../middleware/auth');
const auditLog = require('../middleware/audit');

const router = express.Router();

router.use(protect);

router.get('/assessment', getAssessmentQuestions);
router.get('/self-assessments', authorize('patient', 'super_admin'), getPatientSelfAssessments);

// Admin routes
router.get('/', authorize('super_admin', 'admin', 'hospital'), getAllQuestions);
router.post('/', authorize('super_admin', 'admin', 'hospital'), auditLog('WRITE', 'Question'), createQuestion);
router.get('/children', authorize('patient'), getChildQuestions);
router.post('/children/answers', submitChildAnswer);
router.get('/children/history', getChildAssessmentHistory);

module.exports = router;
