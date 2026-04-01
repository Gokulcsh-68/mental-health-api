const mongoose = require('mongoose');
const Counter = require('./Counter');

const ChiefComplaintSchema = new mongoose.Schema({
    chiefComplaintId: {
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
        required: [true, 'Patient narrative is required']
    },
    voice_recording_url: {
        type: String,
        trim: true,
        default: null           // S3 / CDN URL if audio was uploaded
    },
    transcription_language: {
        type: String,
        trim: true,
        default: 'en'          // BCP-47 language code
    },
    transcription_confidence: {
        type: Number,
        min: 0,
        max: 100,
        default: null           // speech-to-text engine confidence %
    },

    // ── AI Output ──────────────────────────────────────────────
    ai_summary: {
        type: String,
        default: null           // one-paragraph GPT clinical summary
    },
    ai_extraction_metadata: {
        model: { type: String, default: null },
        extracted_at: { type: Date, default: null },
        is_mock: { type: Boolean, default: false }
    },

    // ── Structured Clinical (AI-filled, doctor-editable) ──────
    structured: {
        duration: {
            type: String,
            trim: true,
            default: null       // e.g. "3 weeks", "unknown"
        },
        severity: {
            type: String,
            enum: ['Mild', 'Moderate', 'Severe', null],
            default: null
        },
        onset_pattern: {
            type: String,
            enum: ['Acute', 'Gradual', 'Episodic', 'Chronic', null],
            default: null
        },
        onset_date: {
            type: Date,
            default: null       // approximate date symptoms began
        },
        triggers: {
            type: [String],
            default: []         // e.g. ["work stress", "relationship issues"]
        },
        relieving_factors: {
            type: [String],
            default: []         // e.g. ["sleep", "exercise", "medication"]
        },
        aggravating_factors: {
            type: [String],
            default: []         // e.g. ["caffeine", "isolation"]
        },
        associated_symptoms: {
            type: [String],
            default: []         // co-occurring symptoms
        },
        affected_domains: {
            sleep: { type: Boolean, default: false },
            appetite: { type: Boolean, default: false },
            work: { type: Boolean, default: false },
            social: { type: Boolean, default: false },
            self_care: { type: Boolean, default: false },
            concentration: { type: Boolean, default: false },
            physical_health: { type: Boolean, default: false }
        },
        functional_impairment: {
            type: String,
            trim: true,
            default: null       // free-text description of impact on daily life
        },
        clinical_impression: {
            type: String,
            trim: true,
            default: null       // AI-generated clinical formulation
        },
        potential_diagnoses: {
            type: [String],
            default: []         // e.g. ["Major Depressive Disorder", "GIZ"]
        },
        mse_observations: {
            mood: { type: String, default: null },
            affect: { type: String, default: null },
            speech: { type: String, default: null },
            thought_process: { type: String, default: null },
            insight_judgment: { type: String, default: null }
        },
        psychosocial_stressors: {
            type: [String],
            default: []         // e.g. ["Financial", "Family conflict"]
        },
        protective_factors: {
            type: [String],
            default: []         // e.g. ["Strong family support", "Religious faith"]
        },
        recommendations: {
            type: [String],
            default: []         // e.g. ["Start SSRI", "Therapy referral"]
        }
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

    // ── Previous Episodes ─────────────────────────────────────
    previous_episodes: {
        has_occurred_before: { type: Boolean, default: false },
        frequency: { type: String, default: null },      // e.g. "2 times/year"
        last_episode_date: { type: Date, default: null },
        hospitalized_before: { type: Boolean, default: false },
        notes: { type: String, default: null }
    },

    // ── Color Code (AI auto-generated hex) ──────────────────────
    color_code: {
        type: String,
        default: '#4CAF50'  // green = low/no risk
    },
    redFlagNotified: {
        type: Boolean,
        default: false
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
    timestamps: true             // adds createdAt + updatedAt
});

// ── Auto-increment PK ──────────────────────────────────────────
ChiefComplaintSchema.pre('save', async function () {
    if (!this.isNew) return;

    const counter = await Counter.findByIdAndUpdate(
        { _id: 'chiefComplaintId' },
        { $inc: { seq: 1 } },
        { returnDocument: 'after', upsert: true }
    );
    this.chiefComplaintId = counter.seq;
});

ChiefComplaintSchema.set('toJSON', {
    transform: (doc, ret) => ({
        chiefComplaintId: ret.chiefComplaintId,
        consult_id: ret.consult_id,
        patient: ret.patient,
        status: ret.status,

        // Voice / Narrative
        narrative: ret.narrative,
        transcription_language: ret.transcription_language,

        // AI
        ai_summary: ret.ai_summary,

        // Structured
        structured: ret.structured,

        // Risk
        risk_markers: ret.risk_markers,
        redFlagNotified: ret.redFlagNotified,

        // Color Code
        color_code: ret.color_code,

        // History
        previous_episodes: ret.previous_episodes,

        // Override
        doctor_override: ret.doctor_override,

        _id: ret._id,
        createdAt: ret.createdAt,
        updatedAt: ret.updatedAt,
        __v: ret.__v
    })
});

ChiefComplaintSchema.set('toObject', {
    transform: (doc, ret) => ({
        chiefComplaintId: ret.chiefComplaintId,
        consult_id: ret.consult_id,
        patient: ret.patient,
        status: ret.status,

        // Voice / Narrative
        narrative: ret.narrative,
        transcription_language: ret.transcription_language,

        // AI
        ai_summary: ret.ai_summary,

        // Structured
        structured: ret.structured,

        // Risk
        risk_markers: ret.risk_markers,
        redFlagNotified: ret.redFlagNotified,

        // Color Code
        color_code: ret.color_code,

        // History
        previous_episodes: ret.previous_episodes,

        // Override
        doctor_override: ret.doctor_override,

        _id: ret._id,
        createdAt: ret.createdAt,
        updatedAt: ret.updatedAt,
        __v: ret.__v
    })
});

module.exports = mongoose.model('ChiefComplaint', ChiefComplaintSchema);
