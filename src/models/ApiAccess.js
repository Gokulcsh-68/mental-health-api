const mongoose = require('mongoose');

const ApiAccessSchema = new mongoose.Schema({
    role_code: {
        type: String,
        required: true
    },
    resource: {
        type: String,
        required: true
    },
    permissions: {
        type: [String], // e.g., ['create', 'read', 'update', 'delete']
        default: []
    },
    createdAt: {
        type: Date,
        default: Date.now
    }
});

// Ensure a role has only one entry per resource
ApiAccessSchema.index({ role_code: 1, resource: 1 }, { unique: true });

module.exports = mongoose.model('ApiAccess', ApiAccessSchema);
