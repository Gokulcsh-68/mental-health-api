const mongoose = require('mongoose');

const ProfessionalRequestSchema = new mongoose.Schema({
    requestId: {
        type: Number,
        unique: true
    },
    professional: {
        type: mongoose.Schema.Types.ObjectId,
        ref: 'User',
        required: true
    },
    patient: {
        type: mongoose.Schema.Types.ObjectId,
        ref: 'User',
        required: true
    },
    patientId: {
        type: Number,
        required: true
    },
    category: {
        type: String,
        required: [true, 'Please add a DSM-5 category (e.g., gambling)'],
        trim: true
    },
    message: {
        type: String,
        trim: true
    },
    status: {
        type: String,
        enum: ['pending', 'completed', 'expired', 'cancelled'],
        default: 'pending'
    },
    assessment: {
        type: mongoose.Schema.Types.ObjectId,
        ref: 'Assessment'
    },
    createdAt: {
        type: Date,
        default: Date.now
    },
    completedAt: {
        type: Date
    }
});

// Auto-increment ID
const Counter = require('./Counter');
ProfessionalRequestSchema.pre('save', async function () {
    if (!this.isNew) {
        return;
    }

    const counter = await Counter.findByIdAndUpdate(
        { _id: 'requestId' },
        { $inc: { seq: 1 } },
        { returnDocument: 'after', upsert: true }
    );
    this.requestId = counter.seq;
});

module.exports = mongoose.model('ProfessionalRequest', ProfessionalRequestSchema);
