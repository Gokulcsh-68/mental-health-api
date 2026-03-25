const mongoose = require('mongoose');

const TreatmentPlanSchema = new mongoose.Schema({
    patient: {
        type: mongoose.Schema.Types.ObjectId,
        ref: 'User',
        required: true
    },
    consultId: {
        type: Number,
        required: false
    },
    plan: {
        type: String,
        required: [true, 'Please add a clinical plan or impression']
    },
    medications: {
        type: String,
        required: false
    },
    next_steps: {
        type: String,
        required: false
    },
    createdBy: {
        type: mongoose.Schema.Types.ObjectId,
        ref: 'User',
        required: true
    },
    createdAt: {
        type: Date,
        default: Date.now
    }
});

module.exports = mongoose.model('TreatmentPlan', TreatmentPlanSchema);
