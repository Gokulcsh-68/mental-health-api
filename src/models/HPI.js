const mongoose = require('mongoose');
const Counter = require('./Counter');

/**
 * @desc    History of Present Illness (HPI) - DSM-5 Aligned
 */
const HPISchema = new mongoose.Schema({
    hpiId: {
        type: Number,
        unique: true
    },
    consult_id: {
        type: Number,
        default: null
    },
    patient: {
        type: mongoose.Schema.Types.ObjectId,
        ref: 'User',
        required: [true, 'Patient ID is required']
    },
    status: {
        type: String,
        enum: ['draft', 'completed'],
        default: 'completed'
    },
    narrative: {
        type: String,
        required: [true, 'HPI narrative is required']
    },

    // ── DSM-5 Aligned HPI Fields ──────────────────────────────
    structured: {
        onset: { type: String, enum: ['Gradual', 'Acute', null], default: null },
        duration: { type: String, default: null },
        course: { type: String, enum: ['Episodic', 'Continuous', 'Progressive', null], default: null },

        // Symptom Domains
        mood_features: { type: [String], default: [] },     // e.g. ["Low mood", "Anhedonia"]
        anxiety_features: { type: [String], default: [] },  // e.g. ["Panic", "Restlessness"]
        psychotic_features: { type: [String], default: [] },// e.g. ["Delusions", "Hallucinations"]

        // Neurovegetative / Cognitive
        sleep: { type: String, enum: ['Insomnia', 'Hypersomnia', 'Normal', null], default: null },
        appetite: { type: String, enum: ['Increased', 'Reduced', 'Normal', null], default: null },
        energy: { type: String, enum: ['Fatigue', 'Agitation', 'Normal', null], default: null },
        cognitive: { type: [String], default: [] },         // e.g. ["Memory loss", "Poor concentration"]

        // Safety & History
        suicidal_ideation: { type: String, enum: ['Passive', 'Active', 'Plan', 'None', null], default: null },
        previous_episodes: { type: String, enum: ['Yes', 'No', null], default: null },
        treatment_response: { type: String, enum: ['Good', 'Partial', 'Resistant', 'None', null], default: null }
    },

    // AI Generated
    dsm5_mapping: {
        type: [String],
        default: []  // e.g. ["Symptom cluster mapping to DSM-5 criteria"]
    },
    severity_index: {
        type: Number,
        min: 0,
        max: 100,
        default: 0
    },
    recommendations: {
        type: [String],
        default: []  // e.g. ["Safety planning", "Refer for CBT", "Consider SSRI"]
    },
    color_code: {
        type: String,
        default: '#4CAF50'  // green hex default
    },
    redFlagNotified: {
        type: Boolean,
        default: false
    }


}, {
    timestamps: true
});

// Auto-increment PK
HPISchema.pre('save', async function () {
    if (!this.isNew) return;
    const counter = await Counter.findByIdAndUpdate(
        { _id: 'hpiId' },
        { $inc: { seq: 1 } },
        { new: true, upsert: true }
    );
    this.hpiId = counter.seq;
});

// ── JSON serialisation ──────────────────────────────────────────
HPISchema.set('toJSON', {
    transform: (doc, ret) => ({
        hpiId: ret.hpiId,
        consult_id: ret.consult_id,
        patient: ret.patient,
        status: ret.status,

        // Narrative
        narrative: ret.narrative,

        // DSM-5 Structured
        structured: ret.structured,

        // AI Generated
        dsm5_mapping: ret.dsm5_mapping,
        severity_index: ret.severity_index,
        color_code: ret.color_code,
        redFlagNotified: ret.redFlagNotified,
        recommendations: ret.recommendations,

        createdAt: ret.createdAt
    })
});

module.exports = mongoose.model('HPI', HPISchema);
