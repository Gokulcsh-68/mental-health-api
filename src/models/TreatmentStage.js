const mongoose = require('mongoose');

const TreatmentStageSchema = new mongoose.Schema({
    patient: {
        type: mongoose.Schema.Types.ObjectId,
        ref: 'User',
        required: true
    },
    stage: {
        type: String,
        enum: ['Onboarding', 'Assessment', 'Therapy', 'Monitoring', 'Completed', 'Maintenance', 'Discharged'],
        required: true
    },
    status: {
        type: String,
        enum: ['pending', 'in_progress', 'completed', 'skipped'],
        default: 'pending'
    },
    order: {
        type: Number,
        required: true
    },
    assignedTo: {
        type: mongoose.Schema.Types.ObjectId,
        ref: 'User' // Professional
    },
    completedAt: Date,
    notes: String,
    metadata: {
        type: Map,
        of: String
    },
    updatedBy: {
        type: mongoose.Schema.Types.ObjectId,
        ref: 'User'
    }
}, {
    timestamps: true
});

// Index for efficient retrieval per patient
TreatmentStageSchema.index({ patient: 1, order: 1 });
TreatmentStageSchema.index({ patient: 1, stage: 1 }, { unique: true });

module.exports = mongoose.model('TreatmentStage', TreatmentStageSchema);
