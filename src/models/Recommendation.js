const mongoose = require('mongoose');

const RecommendationSchema = new mongoose.Schema({
    category: {
        type: String,
        required: [true, 'Please add a category'],
        trim: true
    },
    minPercentage: {
        type: Number,
        required: true,
        default: 0
    },
    maxPercentage: {
        type: Number,
        required: true,
        default: 100
    },
    text: {
        type: String,
        required: [true, 'Please add recommendation text'],
        trim: true
    },
    actionLabel: String,
    actionUrl: String,
    priority: {
        type: Number,
        default: 0
    },
    isActive: {
        type: Boolean,
        default: true
    },
    createdBy: {
        type: mongoose.Schema.Types.ObjectId,
        ref: 'User'
    },
    createdAt: {
        type: Date,
        default: Date.now
    }
});

module.exports = mongoose.model('Recommendation', RecommendationSchema);
