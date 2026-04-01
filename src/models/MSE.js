const mongoose = require('mongoose');
const Counter = require('./Counter');

const MSESchema = new mongoose.Schema({
    mseId: { type: Number, unique: true },
    consult_id: { type: Number, default: null },
    patient: { type: mongoose.Schema.Types.ObjectId, ref: 'User', required: true },
    status: { type: String, enum: ['draft', 'completed'], default: 'completed' },

    // ── MSE Components ───────────────────────────────────────────

    appearance: {
        grooming: { type: String, default: null },   // Well groomed / Disheveled / Unkempt
        dress: { type: String, default: null },        // Appropriate / Bizarre
        hygiene: { type: String, default: null },      // Good / Poor
        eye_contact: { type: String, default: null },  // Normal / Avoidant / Intense
        notes: { type: String, default: null }
    },

    behavior: {
        attitude: { type: String, default: null },     // Cooperative / Guarded / Hostile / Withdrawn
        psychomotor: { type: String, default: null },  // Normal / Agitated / Retarded / Catatonic
        mannerisms: { type: [String], default: [] },   // e.g. ['Tremor', 'Tics']
        notes: { type: String, default: null }
    },

    speech: {
        rate: { type: String, default: null },         // Normal / Pressured / Slow / Mutism
        volume: { type: String, default: null },       // Normal / Loud / Soft / Whispering
        articulation: { type: String, default: null }, // Clear / Slurred / Stuttering
        spontaneity: { type: String, default: null },  // Spontaneous / Restricted / Absent
        notes: { type: String, default: null }
    },

    mood: {
        subjective: { type: String, default: null },   // Patient's own words — e.g. "I feel hopeless"
        clinician_observed: { type: String, default: null }
    },

    affect: {
        quality: { type: String, default: null },      // Euthymic / Depressed / Elevated / Anxious / Irritable / Dysphoric
        range: { type: String, default: null },        // Full / Constricted / Blunted / Flat
        appropriateness: { type: String, default: null }, // Congruent / Incongruent / Labile
        notes: { type: String, default: null }
    },

    thought_form: {
        process: { type: String, default: null },      // Logical / Tangential / Circumstantial / Flight of ideas / Loose / Blocking
        coherence: { type: String, default: null },    // Coherent / Incoherent
        notes: { type: String, default: null }
    },

    thought_content: {
        delusions: { type: Boolean, default: false },
        delusion_types: { type: [String], default: [] }, // Paranoid / Grandiose / Persecutory / Somatic / Nihilistic
        suicidal_ideation: { type: String, default: 'None' }, // None / Passive / Active / Plan / Intent
        homicidal_ideation: { type: String, default: 'None' },
        obsessions: { type: Boolean, default: false },
        phobias: { type: Boolean, default: false },
        other_content: { type: String, default: null }
    },

    perception: {
        hallucinations: { type: Boolean, default: false },
        hallucination_types: { type: [String], default: [] }, // Auditory / Visual / Olfactory / Tactile / Gustatory
        hallucination_details: { type: String, default: null },
        illusions: { type: Boolean, default: false },
        depersonalization: { type: Boolean, default: false },
        derealization: { type: Boolean, default: false }
    },

    insight: {
        level: { type: String, default: null },   // Good / Partial / Poor / Absent
        description: { type: String, default: null }
    },

    judgment: {
        level: { type: String, default: null },   // Intact / Mildly Impaired / Moderately Impaired / Severely Impaired
        notes: { type: String, default: null }
    },

    cognition: {
        orientation: {
            person: { type: Boolean, default: true },
            place: { type: Boolean, default: true },
            time: { type: Boolean, default: true }
        },
        memory: { type: String, default: null },   // Intact / Mildly Impaired / Moderately / Severely
        concentration: { type: String, default: null },
        cognitive_test: { type: String, default: null }, // MMSE / MoCA / None
        cognitive_score: { type: Number, default: null },
        cognitive_max: { type: Number, default: null }   // 30 for MMSE, 30 for MoCA
    },

    // ── AI-Generated Analysis ─────────────────────────────────────
    ai_analysis: {
        affect_recognition: { type: String, default: null },
        speech_tempo_analysis: { type: String, default: null },
        emotional_tone_mapping: { type: [String], default: [] },
        psychomotor_markers: { type: [String], default: [] },
        clinical_formulation: { type: String, default: null },
        diagnostic_impressions: { type: [String], default: [] }
    },
    color_code: { type: String, default: '#4CAF50' },
    redFlagNotified: { type: Boolean, default: false }

}, { timestamps: true });

MSESchema.pre('save', async function () {
    if (!this.isNew) return;
    const counter = await Counter.findByIdAndUpdate(
        { _id: 'mseId' }, { $inc: { seq: 1 } }, { returnDocument: 'after', upsert: true }
    );
    this.mseId = counter.seq;
});

MSESchema.set('toJSON', {
    transform: (doc, ret) => ({
        _id: ret._id,
        mseId: ret.mseId,
        consult_id: ret.consult_id,
        patient: ret.patient,
        status: ret.status,
        appearance: ret.appearance,
        behavior: ret.behavior,
        speech: ret.speech,
        mood: ret.mood,
        affect: ret.affect,
        thought_form: ret.thought_form,
        thought_content: ret.thought_content,
        perception: ret.perception,
        insight: ret.insight,
        judgment: ret.judgment,
        cognition: ret.cognition,
        ai_analysis: ret.ai_analysis,
        color_code: ret.color_code,
        redFlagNotified: ret.redFlagNotified,
        createdAt: ret.createdAt
    })
});

module.exports = mongoose.model('MSE', MSESchema);
