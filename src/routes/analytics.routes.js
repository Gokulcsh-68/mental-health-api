const express = require('express');
const router = express.Router();
const { getDashboardStats, getSymptomHeatMap } = require('../controllers/analytics.controller');
const { protect, authorize } = require('../middleware/auth');
const validateApiKey = require('../middleware/apiKey');

router.use(validateApiKey);
router.use(protect);
router.use(authorize('super_admin', 'admin', 'psychiatrist'));

router.get('/dashboard', getDashboardStats);
router.get('/heat-map', getSymptomHeatMap);

module.exports = router;
