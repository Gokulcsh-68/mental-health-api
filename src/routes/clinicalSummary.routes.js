const express = require('express');
const router = express.Router();
const { getClinicalSummary, generateClinicalInference } = require('../controllers/clinicalSummary.controller');
const { protect, authorize } = require('../middleware/auth');
const auditLog = require('../middleware/audit');

// GET /api/v1/patients/:patientId/clinical-summary
router.get('/:patientId/clinical-summary', protect, authorize('psychiatrist', 'psychologist', 'super_admin'), getClinicalSummary);

// POST /api/v1/patients/:patientId/clinical-inference
router.post('/:patientId/clinical-inference', protect, authorize('psychiatrist', 'super_admin'), auditLog('WRITE', 'ClinicalInference'), generateClinicalInference);

module.exports = router;
