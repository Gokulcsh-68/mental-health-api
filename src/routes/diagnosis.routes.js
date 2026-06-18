const express = require('express');
const { protect } = require('../middleware/auth');
const { createDiagnosis, aiDiagnose, getDiagnosis, getAIDiagnosis, getFriendlyDiagnosis, getDiagnosisHistory, getLatestDiagnosisByUser } = require('../controllers/diagnosis.controller');



const router = express.Router();

// GET latest diagnosis by user ID
router.get('/ai/:user_id', protect, getLatestDiagnosisByUser);

// POST /api/v1/diagnosis - Create a diagnosis (protected)
router.post('/', protect, createDiagnosis);

// POST /api/v1/diagnosis/ai - AI-only diagnosis (protected)
router.post('/ai', protect, aiDiagnose);

// GET /api/v1/diagnosis/ai - Diagnosis history for a user (protected)
router.get('/ai', protect, getDiagnosisHistory);
router.get('/ai/', protect, getDiagnosisHistory);


// GET /api/v1/diagnosis/ai/:consult_id - Get AI diagnosis without persisting (protected)
router.get('/ai/:consult_id', protect, getAIDiagnosis);

// GET /api/v1/diagnosis/:consult_id - Retrieve stored diagnosis and prescription (protected)
router.get('/:consult_id', protect, getDiagnosis);

module.exports = router;
