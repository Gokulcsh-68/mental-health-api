const mongoose = require('mongoose');

const AssessmentSchema = new mongoose.Schema({
    assessmentId: {
        type: Number,
        unique: true
    },
    consult_id: {
        type: Number
    },
    assessment_type: {
        type: String,
        enum: ['initial', 'follow_up', 'self', 'professional'],
        default: 'follow_up'
    },
    user: {
        type: mongoose.Schema.Types.ObjectId,
        ref: 'User',
        required: true
    },
    category: {
        type: String,
        required: true,
        default: 'general'
    },
    responses: [{
        question: {
            type: mongoose.Schema.Types.ObjectId,
            ref: 'Question',
            required: true
        },
        questionId: Number,
        optionId: {
            type: mongoose.Schema.Types.ObjectId,
            required: true
        },
        score: {
            type: Number,
            required: true
        }
    }],
    totalScore: {
        type: Number,
        default: 0
    },
    maxPossibleScore: {
        type: Number,
        default: 0
    },
    percentage: {
        type: Number,
        default: 0
    },
    categoryBreakdown: {
        type: Map,
        of: Number
    },
    clinicalResults: {
        type: Map,
        of: {
            rawScore: Number,
            tScore: Number,
            standardError: Number,
            interpretation: String
        }
    },
    status: {
        type: String,
        enum: ['draft', 'completed'],
        default: 'completed'
    },
    date: {
        type: String,
        trim: true
    },
    time: {
        type: String,
        trim: true
    },
    notes: {
        type: String,
        trim: true
    },
    wellnessAspect: {
        type: String,
        trim: true
    },
    isSelfAssessment: {
        type: Boolean,
        default: false
    },
    professionalRequestId: {
        type: mongoose.Schema.Types.ObjectId,
        ref: 'ProfessionalRequest'
    },
    createdAt: {
        type: Date,
        default: Date.now
    }
});

// Auto-increment ID
const Counter = require('./Counter');
AssessmentSchema.pre('save', async function () {
    if (!this.isNew) {
        return;
    }

    const counter = await Counter.findByIdAndUpdate(
        { _id: 'assessmentId' },
        { $inc: { seq: 1 } },
        { returnDocument: 'after', upsert: true }
    );
    this.assessmentId = counter.seq;
});

// Set transformation for field ordering
AssessmentSchema.set('toJSON', {
    transform: (doc, ret) => {
        return {
            assessmentId: ret.assessmentId,
            consult_id: ret.consult_id !== undefined ? ret.consult_id : null,
            assessment_type: ret.assessment_type,
            category: ret.category,
            status: ret.status,
            totalScore: ret.totalScore,
            maxPossibleScore: ret.maxPossibleScore,
            percentage: ret.percentage,
            clinicalResults: ret.clinicalResults,
            categoryBreakdown: ret.categoryBreakdown,
            responses: ret.responses,
            user: ret.user,
            date: ret.date,
            time: ret.time,
            notes: ret.notes,
            _id: ret._id,
            createdAt: ret.createdAt,
            __v: ret.__v
        };
    }
});

module.exports = mongoose.model('Assessment', AssessmentSchema);
