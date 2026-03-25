const mongoose = require('mongoose');

const RefreshTokenSchema = new mongoose.Schema({
    token: {
        type: String,
        required: true,
        unique: true
    },
    user: {
        type: mongoose.Schema.Types.ObjectId,
        ref: 'User',
        required: true
    },
    expiryDate: {
        type: Date,
        required: true
    },
    isRevoked: {
        type: Boolean,
        default: false
    },
    createdAt: {
        type: Date,
        default: Date.now
    }
});

// Check if token is expired
RefreshTokenSchema.methods.isExpired = function () {
    return Date.now() >= this.expiryDate.getTime();
};

module.exports = mongoose.model('RefreshToken', RefreshTokenSchema);
