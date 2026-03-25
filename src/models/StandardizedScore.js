const mongoose = require('mongoose');

const StandardizedScoreSchema = new mongoose.Schema({
    category: {
        type: String,
        required: true
    },
    master: {
        type: mongoose.Schema.Types.ObjectId,
        ref: 'Master'
    },
    rawScore: {
        type: Number,
        required: true
    },
    tScore: {
        type: Number,
        required: true
    },
    standardError: {
        type: Number
    },
    interpretation: {
        type: String
    }
});

// Index for fast lookup
StandardizedScoreSchema.index({ category: 1, rawScore: 1 }, { unique: true });

module.exports = mongoose.model('StandardizedScore', StandardizedScoreSchema);
