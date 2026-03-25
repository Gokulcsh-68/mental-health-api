const express = require('express');
const {
    submitAssessment,
    getMyAssessments,
    getAssessmentById,
    getPatientAssessments,
    updateAssessment,
    deleteAssessment,
    getAllAssessments
} = require('../controllers/assessment.controller');
const { protect, authorize } = require('../middleware/auth');

const router = express.Router();

router.use(protect);

router.post('/', authorize('patient'), submitAssessment);
router.get('/', getMyAssessments);

// Overview accessible to all clinical staff/admin
router.get('/admin', authorize('psychiatrist', 'psychologist', 'nurse', 'social_worker', 'counselor', 'hospital', 'admin', 'super_admin'), getAllAssessments);

router.route('/:id')
    .get(getAssessmentById) // getAssessmentById usually has ownership checks
    .patch(authorize('hospital', 'admin', 'super_admin'), updateAssessment)
    .delete(authorize('super_admin'), deleteAssessment);

// Professional only routes
// Professional and Patient access to specific history
router.get('/patient/:patientId', authorize('psychiatrist', 'psychologist', 'nurse', 'social_worker', 'counselor', 'hospital', 'admin', 'super_admin', 'patient'), getPatientAssessments);

module.exports = router;
