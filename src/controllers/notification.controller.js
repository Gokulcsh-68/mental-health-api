const Notification = require('../models/Notification');
const { notify, broadcast } = require('../services/notificationService');
const { sendSuccess, sendError } = require('../utils/responseHelper');

/**
 * @desc    Broadcast notification to role(s)
 * @route   POST /api/v1/notifications/broadcast
 * @access  Private (Super Admin)
 */
exports.broadcastNotification = async (req, res, next) => {
    try {
        const { role, title, message, type } = req.body;

        if (!title || !message) {
            return sendError(res, 400, 'Please provide title and message');
        }

        const stats = await broadcast({
            role: role || 'all',
            title,
            message,
            type: type || 'general',
            createdBy: req.user._id
        });

        sendSuccess(res, 201, `Broadcast sent to ${stats.totalSent} users`, stats);
    } catch (err) {
        next(err);
    }
};

// @desc    Send notification to a user
// @route   POST /api/v1/notifications/send
// @access  Private
exports.sendNotification = async (req, res, next) => {
    try {
        const { userId, title, message, type } = req.body;

        if (!userId || !title || !message) {
            return sendError(res, 400, 'Please provide userId, title and message');
        }

        const notification = await notify({
            userId,
            title,
            message,
            type: type || 'general',
            createdBy: req.user._id
        });

        if (!notification) {
            return sendError(res, 404, 'Target user not found');
        }

        sendSuccess(res, 201, 'Notification sent via all channels', notification);
    } catch (err) {
        next(err);
    }
};

// @desc    Get notifications for logged-in user
// @route   GET /api/v1/notifications?page=1&limit=10&isRead=false
// @access  Private
exports.getNotifications = async (req, res, next) => {
    try {
        const { page = 1, limit = 10, isRead } = req.query;

        const query = { userId: req.user._id };
        if (isRead !== undefined) query.isRead = isRead === 'true';

        const total = await Notification.countDocuments(query);
        const unreadCount = await Notification.countDocuments({ userId: req.user._id, isRead: false });

        const notifications = await Notification.find(query)
            .skip((page - 1) * limit)
            .limit(parseInt(limit))
            .sort({ createdAt: -1 });

        sendSuccess(res, 200, 'Notifications fetched successfully', {
            notifications,
            unreadCount,
            pagination: {
                page: parseInt(page),
                limit: parseInt(limit),
                total,
                totalPages: Math.ceil(total / limit)
            }
        });
    } catch (err) {
        next(err);
    }
};

// @desc    Mark notification as read
// @route   PUT /api/v1/notifications/:id/read
// @access  Private
exports.markAsRead = async (req, res, next) => {
    try {
        const notification = await Notification.findOneAndUpdate(
            { _id: req.params.id, userId: req.user._id },
            { isRead: true, readAt: new Date() },
            { returnDocument: 'after' }
        );

        if (!notification) {
            return sendError(res, 404, 'Notification not found');
        }

        sendSuccess(res, 200, 'Notification marked as read', notification);
    } catch (err) {
        next(err);
    }
};

// @desc    Mark all notifications as read
// @route   PUT /api/v1/notifications/read-all
// @access  Private
exports.markAllAsRead = async (req, res, next) => {
    try {
        await Notification.updateMany(
            { userId: req.user._id, isRead: false },
            { isRead: true, readAt: new Date() }
        );

        sendSuccess(res, 200, 'All notifications marked as read');
    } catch (err) {
        next(err);
    }
};

/**
 * @desc    Manual trigger for AI Engagement Tip broadcast
 * @route   POST /api/v1/notifications/ai-engagement
 * @access  Private (Super Admin)
 */
exports.triggerAIEngagementBroadcast = async (req, res, next) => {
    try {
        const timedService = require('../services/TimedNotificationService');
        
        // This is a background task, but we'll return a success message immediately
        setImmediate(() => timedService.sendDailyAIEngagementTip());

        sendSuccess(res, 200, 'AI engagement tip broadcast triggered in background');
    } catch (err) {
        next(err);
    }
};
