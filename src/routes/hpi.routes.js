const express = require('express');
const router = express.Router();
const hpiController = require('../controllers/hpi.controller');
const { protect } = require('../middleware/auth');

// Note: apiKey validation is applied globally in app.js for all v1Router routes

router.route('/')
    .post(protect, hpiController.createHPI)
    .get(protect, hpiController.getHPIs);

module.exports = router;
