const mongoose = require('mongoose');
const Counter = require('./Counter');

const ROSSchema = new mongoose.Schema({
    rosId: { type: Number, unique: true },
    consult_id: { type: Number, default: null },
    patient: { type: mongoose.Schema.Types.ObjectId, ref: 'User', required: [true, 'Patient is required'] },
    status: { type: String, enum: ['draft', 'completed'], default: 'completed' },

    // ── Psychiatric ROS (with detailed follow-ups) ───────────────
    psychiatric: {
        depressed_mood: { type: Boolean, default: false },
        depressed_mood_duration: { type: String, default: null },
        depressed_mood_severity: { type: Number, default: null },

        anxiety: { type: Boolean, default: false },
        anxiety_type: { type: String, default: null },
        panic_attacks: { type: Boolean, default: false },

        mania: { type: Boolean, default: false },
        mania_duration: { type: String, default: null },
        mania_features: { type: [String], default: [] },

        psychosis: { type: Boolean, default: false },
        psychosis_type: { type: [String], default: [] },
        psychosis_trigger: { type: String, default: null },

        ocd_symptoms: { type: Boolean, default: false },
        ocd_details: { type: String, default: null },

        ptsd_symptoms: { type: Boolean, default: false },
        trauma_type: { type: String, default: null },
        trauma_date: { type: String, default: null },

        substance_use: { type: Boolean, default: false },
        substance_types: { type: [String], default: [] },
        substance_frequency: { type: String, default: null },

        cognitive_decline: { type: Boolean, default: false },
        cognitive_domains: { type: [String], default: [] },
        cognitive_onset: { type: String, default: null }
    },

    // ── Medical ROS (with detailed follow-ups) ───────────────────
    medical: {
        thyroid_symptoms: { type: Boolean, default: false },
        thyroid_type: { type: String, default: null },
        thyroid_diagnosed: { type: Boolean, default: false },

        seizure_history: { type: Boolean, default: false },
        seizure_type: { type: String, default: null },
        seizure_on_medication: { type: Boolean, default: false },

        head_injury: { type: Boolean, default: false },
        head_injury_severity: { type: String, default: null },
        head_injury_date: { type: String, default: null },

        chronic_illness: { type: Boolean, default: false },
        chronic_illness_details: { type: String, default: null },

        medication_history: { type: Boolean, default: false },
        medications_list: { type: String, default: null },

        hormonal_changes: { type: Boolean, default: false },
        hormonal_type: { type: String, default: null }
    },

    extra_notes: { type: String, default: null },

    // ── AI-Generated Flags ────────────────────────────────────────
    organic_red_flags: { type: [String], default: [] },
    medication_induced_risk: { type: [String], default: [] },
    substance_induced_probability: {
        type: String,
        enum: ['None', 'Low', 'Moderate', 'High'],
        default: 'None'
    },
    ai_notes: { type: String, default: null },
    color_code: { type: String, default: '#4CAF50' },
    redFlagNotified: { type: Boolean, default: false }

}, { timestamps: true });

ROSSchema.pre('save', async function () {
    if (!this.isNew) return;
    const counter = await Counter.findByIdAndUpdate(
        { _id: 'rosId' }, { $inc: { seq: 1 } }, { new: true, upsert: true }
    );
    this.rosId = counter.seq;
});

ROSSchema.set('toJSON', {
    transform: (doc, ret) => ({
        _id: ret._id,
        rosId: ret.rosId,
        consult_id: ret.consult_id,
        patient: ret.patient,
        status: ret.status,
        psychiatric: ret.psychiatric,
        medical: ret.medical,
        extra_notes: ret.extra_notes,
        organic_red_flags: ret.organic_red_flags,
        medication_induced_risk: ret.medication_induced_risk,
        substance_induced_probability: ret.substance_induced_probability,
        ai_notes: ret.ai_notes,
        color_code: ret.color_code,
        redFlagNotified: ret.redFlagNotified,
        createdAt: ret.createdAt
    })
});

module.exports = mongoose.model('ROS', ROSSchema);
