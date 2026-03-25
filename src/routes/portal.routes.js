const express = require('express');
const router = express.Router();
const portalController = require('../controllers/portalContent.controller');
const notificationController = require('../controllers/notification.controller');
const { protect, authorize } = require('../middleware/auth');

/**
 * Public Content Routes
 */
router.get('/help-center', portalController.getHelpCenterList);
router.get('/content/:type', portalController.getPortalContent);

/**
 * Admin Content Management
 */
router.put('/content/:type', protect, authorize('super_admin'), portalController.updatePortalContent);

/**
 * Global Communication (Super Admin ONLY)
 */
router.post('/broadcast', protect, authorize('super_admin'), notificationController.broadcastNotification);

module.exports = router;
