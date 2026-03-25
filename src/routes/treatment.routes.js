const express = require('express');
const { initializeTreatment, getPatientProgress, updateStageStatus, addTreatmentPlan, getTreatmentHistory } = require('../controllers/treatment.controller');
const { protect, authorize } = require('../middleware/auth');

const router = express.Router();

// All treatment routes require authentication
router.use(protect);

/**
 * @route   POST /api/v1/treatment/initialize
 * @desc    Initialize treatment plan for a patient
 * @access  Private (Professional/Hospital/Admin)
 */
router.post('/initialize', authorize('super_admin', 'admin', 'hospital', 'psychiatrist', 'psychologist', 'nurse', 'social_worker', 'counselor'), initializeTreatment);

/**
 * @route   GET /api/v1/treatment/progress/:patientId
 * @desc    Get treatment progress for a patient
 * @access  Private (Patient/Professional/Hospital)
 */
router.get('/progress/:patientId', getPatientProgress);

/**
 * @route   PATCH /api/v1/treatment/:id
 * @desc    Update a treatment stage status
 * @access  Private (Professional/Hospital)
 */
router.patch('/:id', authorize('super_admin', 'admin', 'hospital', 'psychiatrist', 'psychologist', 'nurse', 'social_worker', 'counselor'), updateStageStatus);

/**
 * @route   POST /api/v1/treatment/plan
 * @desc    Add a new standalone treatment plan
 * @access  Private (Professional/Hospital)
 */
router.post('/plan', authorize('super_admin', 'admin', 'hospital', 'psychiatrist', 'psychologist', 'nurse', 'social_worker', 'counselor'), addTreatmentPlan);

/**
 * @route   GET /api/v1/treatment/plan/history/:patientId
 * @desc    Get treatment plan history for a patient
 * @access  Private
 */
router.get('/plan/history/:patientId', getTreatmentHistory);

module.exports = router;
