const mongoose = require('mongoose');
const Counter = require('./Counter');

const PastHistorySchema = new mongoose.Schema({
    pastHistoryId: { type: Number, unique: true },
    consult_id: { type: Number, default: null },
    patient: { type: mongoose.Schema.Types.ObjectId, ref: 'User', required: [true, 'Patient is required'] },
    status: { type: String, enum: ['draft', 'completed'], default: 'completed' },

    // Encrypted narrative for privacy
    narrative: { type: String, default: null },

    // ── 1. Psychiatric History ───────────────────────────────────
    psychiatric_history: {
        previous_diagnosis: { type: [String], default: [] },
        hospitalizations: [{
            year: String,
            reason: String,
            location: String,
            duration: String
        }],
        suicide_attempts: [{
            year: String,
            method: String,
            intent: String
        }],
        medication_trials: [{
            name: String,
            dose: String,
            duration: String,
            response: String,
            side_effects: String
        }],
        psychotherapy_history: { type: String, default: null }
    },

    // ── 2. Medical & Surgical History ─────────────────────────────
    medical_history: {
        chronic_conditions: { type: [String], default: [] },
        surgeries: [{
            procedure: String,
            year: String
        }],
        head_injury: {
            detected: { type: Boolean, default: false },
            loss_of_consciousness: { type: Boolean, default: false },
            details: String
        },
        seizures: {
            detected: { type: Boolean, default: false },
            frequency: String,
            last_seizure: String
        },
        allergies: { type: [String], default: [] }
    },

    // ── 3. Family History ──────────────────────────────────────────
    family_history: {
        conditions: [{
            relative: String,
            condition: String,
            outcome: String
        }],
        suicide_in_family: { type: Boolean, default: false },
        substance_abuse_in_family: { type: Boolean, default: false }
    },

    // ── 4. Substance Use History ─────────────────────────────────
    substance_use: {
        alcohol: {
            status: { type: String, enum: ['Current', 'Past', 'Never'], default: 'Never' },
            quantity: String,
            frequency: String,
            last_use: String
        },
        tobacco_nicotine: {
            status: { type: String, default: 'Never' },
            type: { type: String },
            quantity: { type: String }
        },
        illicit_drugs: [{
            drug: String,
            status: { type: String, enum: ['Current', 'Past'] },
            frequency: String,
            last_use: String
        }],
        caffeine: String,
        prescription_misuse: String
    },

    // ── 5. Developmental History ──────────────────────────────────
    developmental_history: {
        pregnancy_complications: String,
        delivery_type: String,
        milestones: { type: String, enum: ['On-time', 'Delayed', 'Early'], default: 'On-time' },
        childhood_behavior: String,
        school_performance: String
    },

    // ── 6. Personal & Social History ──────────────────────────────
    social_history: {
        education: String,
        employment: String,
        marital_status: String,
        living_situation: String,
        legal_history: {
            legal_issues: { type: Boolean, default: false },
            legal_details: String
        },
        spiritual_beliefs: String,
        strengths_hobbies: String
    },

    // ── 7. Trauma & Abuse History ───────────────────────────────────
    trauma_history: {
        physical_abuse: { type: Boolean, default: false },
        emotional_abuse: { type: Boolean, default: false },
        sexual_abuse: { type: Boolean, default: false },
        significant_losses: String,
        military_service: { type: Boolean, default: false },
        trauma_notes: String
    },

    // ── AI Analysis & Risk flags ──────────────────────────────────
    risk_flags: { type: [String], default: [] },
    treatment_resistance_risk: { type: String, enum: ['None', 'Low', 'Moderate', 'High'], default: 'None' },
    genetic_risk_summary: { type: String, default: null },
    ai_notes: { type: String, default: null },
    color_code: { type: String, default: '#4CAF50' },

    // Metadata for AI Extraction Tracking
    ai_extraction_metadata: {
        model_version: String,
        extraction_date: Date,
        confidence_score: Number
    },

    // Specialist Manual Adjustments Tracking
    doctor_override: {
        is_overridden: { type: Boolean, default: false },
        overridden_by: { type: mongoose.Schema.Types.ObjectId, ref: 'User' },
        override_date: Date,
        notes: String
    }

}, { timestamps: true });

PastHistorySchema.pre('save', async function () {
    if (!this.isNew) return;
    const counter = await Counter.findByIdAndUpdate(
        { _id: 'pastHistoryId' }, { $inc: { seq: 1 } }, { returnDocument: 'after', upsert: true }
    );
    this.pastHistoryId = counter.seq;
});

module.exports = mongoose.model('PastHistory', PastHistorySchema);
