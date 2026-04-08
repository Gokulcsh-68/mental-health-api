const mongoose = require('mongoose');

const ScheduledJobSchema = new mongoose.Schema({
    name: {
        type: String,
        required: [true, 'Please add a unique name'],
        unique: true,
        trim: true
    },
    description: {
        type: String,
        required: [true, 'Please add a description']
    },
    cron: {
        type: String,
        required: [true, 'Please add a cron expression'],
        default: '0 0 * * *'
    },
    actionType: {
        type: String,
        required: [true, 'Please add an action type'],
        enum: ['NOTIFICATION_BROADCAST', 'AI_TIP_BROADCAST', 'DB_CLEANUP', 'RESEARCH_PULSE'],
        default: 'NOTIFICATION_BROADCAST'
    },
    payload: {
        type: mongoose.Schema.Types.Mixed, // Stores { title, message, role } etc.
        default: {}
    },
    isActive: {
        type: Boolean,
        default: true
    },
    createdBy: {
        type: mongoose.Schema.Types.ObjectId,
        ref: 'User'
    },
    lastRunAt: {
        type: Date
    },
    createdAt: {
        type: Date,
        default: Date.now
    }
});

module.exports = mongoose.model('ScheduledJob', ScheduledJobSchema);
