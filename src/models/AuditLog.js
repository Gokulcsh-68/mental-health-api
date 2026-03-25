const mongoose = require('mongoose');

const AuditLogSchema = new mongoose.Schema({
    user: {
        type: mongoose.Schema.Types.ObjectId,
        ref: 'User',
        required: true
    },
    action: {
        type: String,
        required: true,
        enum: ['READ', 'WRITE', 'UPDATE', 'DELETE', 'OVERRIDE', 'LOGIN', 'LOGOUT']
    },
    resource: {
        type: String,
        required: true
    },
    resourceId: {
        type: String
    },
    details: {
        type: mongoose.Schema.Types.Mixed
    },
    ipAddress: {
        type: String
    },
    userAgent: {
        type: String
    },
    timestamp: {
        type: Date,
        default: Date.now
    }
}, {
    timestamps: true
});

// Indexing for performance and lookup
AuditLogSchema.index({ user: 1, timestamp: -1 });
AuditLogSchema.index({ resource: 1, resourceId: 1 });

module.exports = mongoose.model('AuditLog', AuditLogSchema);
