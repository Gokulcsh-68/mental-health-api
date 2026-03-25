const express = require('express');
const router = express.Router();
const { saveSymptoms, getSymptomsByPatient, getSymptomById } = require('../controllers/symptom.controller');
const { protect, authorize } = require('../middleware/auth');
const auditLog = require('../middleware/audit');

// All routes require authentication
router.use(protect);

// POST /api/v1/symptoms - Save new symptoms
router.post('/', auditLog('WRITE', 'Symptom'), saveSymptoms);

// GET /api/v1/symptoms/patient/:patientId - Get patient history
router.get('/patient/:patientId', authorize('psychiatrist', 'psychologist', 'super_admin', 'patient'), getSymptomsByPatient);

// GET /api/v1/symptoms/:id - Get specific record
router.get('/:id', authorize('psychiatrist', 'psychologist', 'super_admin', 'patient'), getSymptomById);

module.exports = router;
