const express = require('express');
const { 
    sendNotification, 
    broadcastNotification,
    getNotifications, 
    markAsRead, 
    markAllAsRead, 
    triggerAIEngagementBroadcast 
} = require('../controllers/notification.controller');
const { protect, authorize } = require('../middleware/auth');

const router = express.Router();

// All notification routes require authentication
router.use(protect);

/**
 * @route   POST /api/v1/notifications/broadcast
 * @desc    Super Admin global broadcast to specific roles or everyone
 * @access  Super Admin
 */
router.post('/broadcast', authorize('super_admin'), broadcastNotification);

/**
 * @route   POST /api/v1/notifications/send
 * @desc    Send targeted notification to a single user
 * @access  Admin, Super Admin, Staff
 */
router.post('/send', authorize('super_admin', 'admin', 'staff', 'psychiatrist'), sendNotification);

/**
 * @route   POST /api/v1/notifications/ai-engagement
 * @desc    Trigger AI-driven engagement broadcast
 * @access  Super Admin
 */
router.post('/ai-engagement', authorize('super_admin', 'admin'), triggerAIEngagementBroadcast);

router.get('/', getNotifications);
router.put('/read-all', markAllAsRead);
router.put('/:id/read', markAsRead);

module.exports = router;
