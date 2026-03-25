const express = require('express');
const dashboardController = require('../controllers/dashboard.controller');
const { protect, authorize } = require('../middleware/auth');

const router = express.Router();

router.get('/specialist', protect, authorize('psychiatrist', 'psychologist', 'nurse', 'social_worker', 'counselor'), dashboardController.getSpecialistDashboard);
router.get('/patient', protect, authorize('patient'), dashboardController.getPatientDashboard);
router.get('/patient/statistics', protect, authorize('patient'), dashboardController.getPatientOwnStatistics);
router.get('/specialist/patient-statistics', protect, authorize('psychiatrist', 'psychologist', 'nurse', 'social_worker', 'counselor'), dashboardController.getPatientStatistics);
router.get('/super-admin', protect, authorize('super_admin'), dashboardController.getSuperAdminStats);

module.exports = router;
