const express = require('express');
const { protect } = require('../middleware/auth');
const { createDiagnosis, aiDiagnose, getDiagnosis, getAIDiagnosis, getDiagnosisHistory, getLatestDiagnosisByUser } = require('../controllers/diagnosis.controller');

const router = express.Router();

// GET all diagnoses for a user by user_id (protected)
router.get('/ai/:user_id', protect, getLatestDiagnosisByUser);

// POST /api/v1/diagnosis - Create a diagnosis (protected)
router.post('/', protect, createDiagnosis);

// POST /api/v1/diagnosis/ai - AI-only diagnosis (protected)
router.post('/ai', protect, aiDiagnose);

// GET diagnosis history for a user (query param user_id)
router.get('/ai', protect, getDiagnosisHistory);

// GET /api/v1/diagnosis/:consult_id - Retrieve stored diagnosis and prescription (protected)
router.get('/:consult_id', protect, getDiagnosis);

module.exports = router;
