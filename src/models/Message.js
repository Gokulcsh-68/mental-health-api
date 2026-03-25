const mongoose = require('mongoose');

const MessageSchema = new mongoose.Schema({
    room_id: {
        type: String,
        required: true,
        index: true
    },
    room_type: {
        type: String,
        enum: ['private_ai', 'group_chat', 'consultation'],
        required: true
    },
    session_id: {
        type: String,
        default: 'main',
        index: true
    },
    sender_id: {
        type: mongoose.Schema.Types.ObjectId,
        ref: 'User',
        required: false // Null if sender is AI
    },
    sender_role: {
        type: String,
        enum: ['patient', 'doctor', 'ai'],
        required: true
    },
    sender_name: {
        type: String,
        required: true
    },
    content: {
        type: String,
        required: true
    },
    timestamp: {
        type: Date,
        default: Date.now
    }
});

module.exports = mongoose.model('Message', MessageSchema);
