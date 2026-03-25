const mongoose = require('mongoose');
const Counter = require('./Counter');

const HistoryOfIllnessSchema = new mongoose.Schema({
    historyOfIllnessId: {
        type: Number,
        unique: true
    },

    // ── Linkage ────────────────────────────────────────────────
    consult_id: {
        type: Number,
        index: true
    },
    patient: {
        type: mongoose.Schema.Types.ObjectId,
        ref: 'User',
        required: [true, 'patient reference is required']
    },
    status: {
        type: String,
        enum: ['draft', 'completed'],
        default: 'draft'
    },

    // ── Voice / Narrative ──────────────────────────────────────
    narrative: {
        type: String,
        trim: true,
        required: [true, 'HPI narrative is required']
    },
    voice_recording_url: {
        type: String,
        trim: true,
        default: null
    },
    transcription_language: {
        type: String,
        trim: true,
        default: 'en'
    },
    transcription_confidence: {
        type: Number,
        min: 0,
        max: 100,
        default: null
    },

    // ── AI Output ──────────────────────────────────────────────
    ai_summary: {
        type: String,
        default: null
    },
    ai_extraction_metadata: {
        model: { type: String, default: null },
        extracted_at: { type: Date, default: null },
        is_mock: { type: Boolean, default: false }
    },

    // ── Structured Clinical (HPI focus) ────────────────────────
    structured: {
        onset: { type: String, default: null },
        duration: { type: String, default: null },
        course: { type: String, default: null },
        mood_features: { type: [String], default: [] },
        anxiety_features: { type: [String], default: [] },
        psychotic_features: { type: [String], default: [] },
        sleep: { type: String, default: null },
        appetite: { type: String, default: null },
        energy: { type: String, default: null },
        cognitive: { type: [String], default: [] },
        suicidal_ideation: { type: String, default: null },
        previous_episodes: { type: String, default: null },
        treatment_response: { type: String, default: null },
        dsm5_mapping: { type: [String], default: [] },
        severity_index: { type: Number, default: 0 },
        recommendations: { type: [String], default: [] }
    },

    // ── Risk Markers ───────────────────────────────────────────
    risk_markers: {
        self_harm_detected: { type: Boolean, default: false },
        violence_detected: { type: Boolean, default: false },
        psychosis_detected: { type: Boolean, default: false },
        substance_use_detected: { type: Boolean, default: false },
        keywords_found: { type: [String], default: [] },
        risk_level: {
            type: String,
            enum: ['None', 'Low', 'Moderate', 'High'],
            default: 'None'
        }
    },

    // ── Color Code ─────────────────────────────────────────────
    color_code: {
        type: String,
        default: '#4CAF50'
    },

    // ── Doctor Manual Override ────────────────────────────────
    doctor_override: {
        applied: { type: Boolean, default: false },
        overridden_at: { type: Date, default: null },
        overridden_by: {
            type: mongoose.Schema.Types.ObjectId,
            ref: 'User',
            default: null
        },
        override_notes: { type: String, default: null }
    }

}, {
    timestamps: true
});

// ── Auto-increment PK ──────────────────────────────────────────
HistoryOfIllnessSchema.pre('save', async function () {
    if (!this.isNew) return;

    const counter = await Counter.findByIdAndUpdate(
        { _id: 'historyOfIllnessId' },
        { $inc: { seq: 1 } },
        { new: true, upsert: true }
    );
    this.historyOfIllnessId = counter.seq;
});

const transform = (doc, ret) => ({
    historyOfIllnessId: ret.historyOfIllnessId,
    consult_id: ret.consult_id,
    patient: ret.patient,
    status: ret.status,
    narrative: ret.narrative,
    transcription_language: ret.transcription_language,
    ai_summary: ret.ai_summary,
    structured: ret.structured,
    risk_markers: ret.risk_markers,
    color_code: ret.color_code,
    doctor_override: ret.doctor_override,
    _id: ret._id,
    createdAt: ret.createdAt,
    updatedAt: ret.updatedAt,
    __v: ret.__v
});

HistoryOfIllnessSchema.set('toJSON', { transform });
HistoryOfIllnessSchema.set('toObject', { transform });

module.exports = mongoose.model('HistoryOfIllness', HistoryOfIllnessSchema);
