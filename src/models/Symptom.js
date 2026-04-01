const mongoose = require('mongoose');
const Counter = require('./Counter');

const SymptomSchema = new mongoose.Schema({
    symptomId: {
        type: Number,
        unique: true
    },
    patient: {
        type: mongoose.Schema.Types.ObjectId,
        ref: 'User',
        required: [true, 'Patient reference is required']
    },
    consult_id: {
        type: Number,
        default: null,
        index: true
    },
    scores: {
        mood: { type: Number, min: 0, max: 10, default: 5 },
        anxiety: { type: Number, min: 0, max: 10, default: 0 },
        sleep: { type: Number, min: 0, max: 10, default: 5 },
        appetite: { type: Number, min: 0, max: 10, default: 5 },
        energy: { type: Number, min: 0, max: 10, default: 5 },
        concentration: { type: Number, min: 0, max: 10, default: 5 }
    },
    notes: {
        type: String,
        trim: true,
        default: null
    },
    color_code: {
        type: String,
        default: '#4CAF50'
    }
}, {
    timestamps: true
});

// Auto-increment ID
SymptomSchema.pre('save', async function () {
    if (!this.isNew) return;

    const counter = await Counter.findByIdAndUpdate(
        { _id: 'symptomId' },
        { $inc: { seq: 1 } },
        { returnDocument: 'after', upsert: true }
    );
    this.symptomId = counter.seq;
});

const transform = (doc, ret) => ({
    symptomId: ret.symptomId,
    patient: ret.patient,
    consult_id: ret.consult_id,
    scores: ret.scores,
    notes: ret.notes,
    color_code: ret.color_code,
    _id: ret._id,
    createdAt: ret.createdAt,
    updatedAt: ret.updatedAt,
    __v: ret.__v
});

SymptomSchema.set('toJSON', { transform });
SymptomSchema.set('toObject', { transform });

module.exports = mongoose.model('Symptom', SymptomSchema);
