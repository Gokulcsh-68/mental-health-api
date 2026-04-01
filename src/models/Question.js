const mongoose = require('mongoose');

const QuestionSchema = new mongoose.Schema({
    questionId: {
        type: Number,
        unique: true
    },
    text: {
        type: String,
        required: [true, 'Please add a question text'],
        trim: true
    },
    professionalText: {
        type: String,
        trim: true
    },
    patientText: {
        type: String,
        trim: true
    },
    category: {
        type: String,
        required: [true, 'Please add a category'],
        default: 'general'
    },
    master: {
        type: mongoose.Schema.Types.ObjectId,
        ref: 'Master'
    },
    type: {
        type: String,
        required: [true, 'Please add a question type'],
        enum: ['scale', 'boolean', 'choice'],
        default: 'scale' // scale 0-4 or 1-5
    },
    uiType: {
        type: String,
        required: [true, 'Please add a UI type'],
        enum: ['radio', 'checkbox', 'textarea'],
        default: 'radio'
    },
    options: [{
        text: String,
        score: Number
    }],
    minAge: {
        type: Number,
        default: 0
    },
    maxAge: {
        type: Number,
        default: 120
    },
    gender: {
        type: String,
        enum: ['male', 'female', 'other', 'all'],
        default: 'all'
    },
    assessmentType: {
        type: String,
        enum: ['self', 'professional', 'both'],
        default: 'both'
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

// Auto-increment ID (using the same pattern as User)
const Counter = require('./Counter');
QuestionSchema.pre('save', async function () {
    if (!this.isNew) {
        return;
    }

    const counter = await Counter.findByIdAndUpdate(
        { _id: 'questionId' },
        { $inc: { seq: 1 } },
        { returnDocument: 'after', upsert: true }
    );
    this.questionId = counter.seq;
});

module.exports = mongoose.model('Question', QuestionSchema);
