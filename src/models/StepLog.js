const mongoose = require('mongoose');

const StepLogSchema = new mongoose.Schema({
    patient: {
        type: mongoose.Schema.Types.ObjectId,
        ref: 'User',
        required: true
    },
    count: {
        type: Number,
        required: true,
        min: 0
    },
    date: {
        type: Date,
        required: true,
        default: () => {
            const now = new Date();
            return new Date(now.getFullYear(), now.getMonth(), now.getDate());
        }
    },
    source: {
        type: String,
        enum: ['GoogleFit', 'AppleHealth', 'Manual', 'Other'],
        default: 'Manual'
    },
    notes: String,
    createdBy: {
        type: mongoose.Schema.Types.ObjectId,
        ref: 'User'
    }
}, {
    timestamps: true
});

// Compound index to ensure unique record per patient per day
StepLogSchema.index({ patient: 1, date: 1 }, { unique: true });

module.exports = mongoose.model('StepLog', StepLogSchema);
