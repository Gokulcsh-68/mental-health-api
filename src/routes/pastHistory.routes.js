const express = require('express');
const router = express.Router();
const pastHistoryController = require('../controllers/pastHistory.controller');
const { protect, authorize } = require('../middleware/auth');

// All routes require authentication
router.use(protect);

// Get Past History questionnaire form structure
router.get('/questions', pastHistoryController.getQuestions);

// AI Extraction/Analysis
router.post('/extract', authorize('psychiatrist', 'psychologist', 'hospital', 'patient', 'family', 'admin', 'super_admin'), pastHistoryController.extractPastHistory);
router.post('/analyze', authorize('psychiatrist', 'psychologist', 'hospital', 'patient', 'family', 'admin', 'super_admin'), pastHistoryController.analyzePastHistory);

// Base CRUD
router.route('/')
    .post(pastHistoryController.createPastHistory)
    .get(pastHistoryController.getPastHistory);

// Specific ID actions (Override, View Decrypted, Delete)
router.route('/:id')
    .get(pastHistoryController.getPastHistoryById)
    .patch(authorize('psychiatrist', 'psychologist', 'hospital', 'admin', 'super_admin'), pastHistoryController.updatePastHistory)
    .delete(authorize('super_admin', 'admin', 'hospital'), pastHistoryController.deletePastHistory);

module.exports = router;
