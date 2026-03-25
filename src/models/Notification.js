const mongoose = require('mongoose');

const NotificationSchema = new mongoose.Schema({
    userId: {
        type: mongoose.Schema.Types.ObjectId,
        ref: 'User',
        required: true
    },
    title: {
        type: String,
        required: [true, 'Please add a title']
    },
    message: {
        type: String,
        required: [true, 'Please add a message']
    },
    type: {
        type: String,
        enum: ['welcome', 'appointment', 'reminder', 'alert', 'general'],
        default: 'general'
    },
    channels: {
        email: { type: Boolean, default: false },
        sms: { type: Boolean, default: false },
        push: { type: Boolean, default: false }
    },
    deliveryStatus: {
        inApp: { type: String, enum: ['sent', 'failed', 'skipped'], default: 'sent' },
        email: { type: String, enum: ['sent', 'failed', 'skipped'], default: 'skipped' },
        sms: { type: String, enum: ['sent', 'failed', 'skipped'], default: 'skipped' },
        push: { type: String, enum: ['sent', 'failed', 'skipped'], default: 'skipped' }
    },
    isRead: {
        type: Boolean,
        default: false
    },
    readAt: {
        type: Date
    },
    createdBy: {
        type: mongoose.Schema.Types.ObjectId,
        ref: 'User'
    },
    imageUrl: {
        type: String
    },
    createdAt: {
        type: Date,
        default: Date.now
    }
});

module.exports = mongoose.model('Notification', NotificationSchema);
