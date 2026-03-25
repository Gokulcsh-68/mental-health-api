const express = require('express');
const { 
    sendNotification, 
    getNotifications, 
    markAsRead, 
    markAllAsRead, 
    triggerAIEngagementBroadcast 
} = require('../controllers/notification.controller');
const { protect, authorize } = require('../middleware/auth');

const router = express.Router();

// All notification routes require authentication
router.use(protect);

router.post('/send', authorize('admin', 'staff', 'psychiatrist'), sendNotification);
router.post('/ai-engagement', authorize('admin'), triggerAIEngagementBroadcast);
router.get('/', getNotifications);
router.put('/read-all', markAllAsRead);
router.put('/:id/read', markAsRead);

module.exports = router;
