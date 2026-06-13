const express = require('express');
const { protect } = require('../middleware/auth');
const { createDiagnosis, aiDiagnose, getDiagnosis, getAIDiagnosis } = require('../controllers/diagnosis.controller');

const router = express.Router();

// POST /api/v1/diagnosis - Create a diagnosis (protected)
router.post('/', protect, createDiagnosis);

// POST /api/v1/diagnosis/ai - AI-only diagnosis (protected)
router.post('/ai', protect, aiDiagnose);

// GET /api/v1/diagnosis/:consult_id - Retrieve stored diagnosis and prescription (protected)
router.get('/:consult_id', protect, getDiagnosis);

// GET /api/v1/diagnosis/ai/:consult_id - Get AI diagnosis without persisting (protected)
router.get('/ai/:consult_id', protect, getAIDiagnosis);

module.exports = router;
