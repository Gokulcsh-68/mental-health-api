const mongoose = require('mongoose');
const { sendEmail } = require('./emailService');
const { sendSMS } = require('./smsService');
const { sendPushNotification } = require('./pushService');
const Notification = require('../models/Notification');
const User = require('../models/User');

/**
 * Send notification through all channels based on user preferences
 * @param {Object} options
 * @param {ObjectId|Number} options.userId - Target user ID (ObjectId or numeric userId)
 * @param {string} options.title - Notification title
 * @param {string} options.message - Notification message
 * @param {string} options.type - Notification type (welcome, appointment, reminder, alert, general)
 * @param {ObjectId} options.createdBy - Who triggered the notification (optional)
 * @param {Object} options.data - Extra data for push notification (optional)
 */
const socketService = require('./socketService');

/**
 * Send notification through all channels based on user preferences
 */
const notify = async ({ userId, title, message, type = 'general', createdBy = null, data = {}, imageUrl = null }) => {
    try {
        // Get user details for delivery channels
        let user;
        if (mongoose.Types.ObjectId.isValid(userId) && String(userId).length === 24) {
            user = await User.findById(userId);
        } else {
            user = await User.findOne({ userId: parseInt(userId) });
        }


        if (!user) {
            console.error(`  ❌ Notification failed: User ${userId} not found`);
            return;
        }

        const preferences = user.communicationPreferences || {};
        const deliveryStatus = { inApp: 'sent', email: 'skipped', sms: 'skipped', push: 'skipped' };

        console.log(`\n📣 Sending notification to ${user.firstName} ${user.lastName} (${user.role})`);

        // 1. Always save to DB (in-app notification)
        const notification = await Notification.create({
            userId,
            title,
            message,
            type,
            createdBy,
            imageUrl, // Save image URL to DB for history
            channels: {
                email: preferences.email !== false,
                sms: preferences.sms !== false,
                push: preferences.push !== false
            }
        });

        // 2. Real-time Socket.io emission for in-app popups/counters
        socketService.emitToUser(userId.toString(), 'notification', notification);

        // 2. Send Email (if preference enabled and email exists)
        if (preferences.email !== false && user.email) {
            const result = await sendEmail({
                to: user.email,
                subject: title,
                text: message,
                html: `
                    <div style="font-family: Arial, sans-serif; padding: 20px; max-width: 600px;">
                        <h2 style="color: #4A90D9;">${title}</h2>
                        <p style="color: #333; font-size: 16px; line-height: 1.6;">${message}</p>
                        ${imageUrl ? `<img src="${imageUrl}" style="width: 100%; border-radius: 8px; margin: 10px 0;">` : ''}
                        <hr style="border: 1px solid #eee;">
                        <p style="color: #999; font-size: 12px;">Mental Health Platform</p>
                    </div>
                `
            });
            deliveryStatus.email = result.success ? 'sent' : 'failed';
        }

        // 3. Send SMS (if preference enabled and phone exists)
        if (preferences.sms !== false && user.phone) {
            const phoneNumber = user.isdCode ? `${user.isdCode}${user.phone}` : user.phone;
            const result = await sendSMS({
                to: phoneNumber,
                message: `${title}: ${message}`
            });
            deliveryStatus.sms = result.success ? 'sent' : 'failed';
        }

        // 4. Send Push Notification (if preference enabled and tokens exist)
        const hasPushToken = user.fcmTokens && user.fcmTokens.length > 0;
        const pushEnabled = preferences.push !== false;
        
        if (pushEnabled && hasPushToken) {
            console.log(`  🔔 Sending push to ${user.fcmTokens.length} token(s)...`);
            const result = await sendPushNotification({
                tokens: user.fcmTokens,
                title,
                body: message,
                data,
                imageUrl
            });
            deliveryStatus.push = result.success ? 'sent' : 'failed';
        } else {
            console.log(`  🔔 Push skipped: enabled=${pushEnabled}, tokens=${hasPushToken ? user.fcmTokens.length : 0}`);
            deliveryStatus.push = 'skipped';
        }

        // Update notification with delivery status
        notification.deliveryStatus = deliveryStatus;
        await notification.save();

        console.log(`  ✅ Delivery: in-app=${deliveryStatus.inApp}, email=${deliveryStatus.email}, sms=${deliveryStatus.sms}, push=${deliveryStatus.push}`);


        return notification;
    } catch (err) {
        console.error('  ❌ Notification service error:', err.message);
    }
};

/**
 * Send a silent push notification (data-only) to trigger background app logic
 * @param {Object} options
 * @param {ObjectId} options.userId - Target user's _id
 * @param {Object} options.data - Data payload for the app kernel
 */
const notifySilent = async ({ userId, data = {} }) => {
    try {
        const user = await User.findById(userId);
        if (!user || (user.communicationPreferences && user.communicationPreferences.push === false)) {
            return;
        }

        if (user.fcmTokens && user.fcmTokens.length > 0) {
            await sendPushNotification({
                tokens: user.fcmTokens,
                data: { ...data, silent: 'true' }
            });
        }
    } catch (err) {
        console.error('  ❌ Silent notification error:', err.message);
    }
};

/**
 * Broadcast notification to a group of users
 * @param {Object} options
 * @param {string} options.role - Target role (e.g., patient, specialist, all)
 * @param {string} options.title - Notification title
 * @param {string} options.message - Notification message
 * @param {string} options.type - Notification type
 * @param {ObjectId} options.createdBy - Who triggered the broadcast
 */
const broadcast = async ({ role, title, message, type = 'general', createdBy = null, imageUrl = null }) => {
    try {
        const query = role && role !== 'all' ? { role } : {};
        const users = await User.find(query);

        console.log(`\n📢 Broadcasting to ${users.length} users (Role: ${role || 'all'})...`);

        const results = await Promise.all(users.map(user => 
            notify({
                userId: user._id,
                title,
                message,
                type,
                createdBy,
                imageUrl
            })
        ));

        return {
            totalSent: results.filter(Boolean).length,
            totalFound: users.length
        };
    } catch (err) {
        console.error('  ❌ Broadcast service error:', err.message);
        throw err;
    }
};

module.exports = { notify, notifySilent, broadcast };
