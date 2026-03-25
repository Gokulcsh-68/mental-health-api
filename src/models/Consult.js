const mongoose = require('mongoose');
const Counter = require('./Counter');

const ConsultSchema = new mongoose.Schema({
    consultId: {
        type: Number,
        unique: true
    },
    consult_id: {
        type: Number
    },
    consult_code: {
        type: String,
        unique: true
    },
    scheduled_at: {
        type: Date,
        required: [true, 'Please add a scheduled date and time']
    },
    consult_type: {
        type: String,
        required: [true, 'Please add a consult type'],
        enum: ['virtual', 'home', 'clinic'],
        default: 'virtual'
    },
    reason: {
        type: String,
        required: [true, 'Please add a reason for the consultation']
    },
    consult_data: {
        type: mongoose.Schema.Types.Mixed,
        default: {}
    },
    city: String,
    coordinates: {
        lat: Number,
        lng: Number
    },
    virtual_service_provider: {
        type: mongoose.Schema.Types.Mixed,
        default: {
            id: 12,
            name: 'Tokbox',
            slug: 'tokbox'
        }
    },
    participants: [
        {
            token: String,
            temp_en: String,
            participant_status: {
                id: { type: Number, default: 7 },
                name: { type: String, default: 'Not Started' },
                slug: { type: String, default: 'not_started' }
            },
            role: {
                type: String,
                enum: ['publisher', 'subscriber']
            },
            ref_number: {
                type: String
            },
            participant_info: {
                name: String,
                email: String,
                phone: String,
                gender: String,
                profile_pic: String,
                additional_info: mongoose.Schema.Types.Mixed
            },
            started_at: Date,
            ended_at: Date,
            created_at: { type: Date, default: Date.now }
        }
    ],
    additional_info: {
        type: mongoose.Schema.Types.Mixed,
        default: {}
    },
    consult_status: {
        id: { type: Number, default: 1 },
        name: { type: String, default: 'Scheduled' },
        slug: { type: String, default: 'scheduled' }
    },
    consult_current_status: {
        id: { type: Number, default: 1 },
        name: { type: String, default: 'Scheduled' },
        slug: { type: String, default: 'scheduled' }
    },
    consult_entity_id: {
        id: { type: Number, default: 1 },
        name: { type: String, default: 'Active' },
        slug: { type: String, default: 'active' }
    },
    active: {
        type: Boolean,
        default: true
    },
    started_at: Date,
    ended_at: Date,
    started_participant_id: { type: Number, default: 0 },
    ended_participant_id: { type: Number, default: 0 },
    hospital: {
        type: mongoose.Schema.Types.ObjectId,
        ref: 'User'
    },
    clinical_record: {
        metadata: {
            consult_type: String,
            mode: String,
            verification: {
                identity_verified: Boolean,
                location_confirmed: Boolean,
                consent_recorded: Boolean,
                emergency_contact_captured: Boolean
            }
        },
        chief_complaints: {
            narrative: String,
            structured: {
                duration: String,
                severity: { type: String, enum: ['Mild', 'Moderate', 'Severe'] },
                onset: String,
                triggers: String,
                functional_impairment: String,
                risk_markers: [String]
            }
        },
        hpi: {
            onset: String,
            duration: String,
            course: String,
            symptoms: mongoose.Schema.Types.Mixed,
            ai_summary: mongoose.Schema.Types.Mixed
        },
        ros: {
            psychiatric: [String],
            medical: [String],
            ai_flags: [String]
        },
        past_history: {
            psychiatric: mongoose.Schema.Types.Mixed,
            medical: mongoose.Schema.Types.Mixed,
            substance_use: mongoose.Schema.Types.Mixed,
            family_history: mongoose.Schema.Types.Mixed
        },
        mse: {
            appearance: String,
            behavior: String,
            speech: String,
            mood: String,
            affect: String,
            thought_form: String,
            thought_content: String,
            perception: String,
            insight: String,
            judgment: String,
            cognition: String,
            ai_analysis: mongoose.Schema.Types.Mixed
        },
        ai_inference: {
            differential_diagnosis: mongoose.Schema.Types.Mixed,
            rule_outs: [String],
            suggested_investigations: mongoose.Schema.Types.Mixed,
            criteria_matched: [String],
            criteria_missing: [String],
            red_flag_alerts: [String],
            risk_assessment: {
                suicide_risk: { type: String, enum: ['Low', 'Moderate', 'High'] },
                violence_risk: { type: String, enum: ['Low', 'Moderate', 'High'] },
                self_care: String,
                emergency_needed: Boolean,
                immediate_protocol: [String]
            },
            risk_stratification: {
                score: Number,
                level: { type: String, enum: ['Low', 'Moderate', 'High', 'Critical'] },
                primary_risk: String
            },
            _ai_metadata: mongoose.Schema.Types.Mixed
        },
        diagnosis: {
            primary: mongoose.Schema.Types.Mixed,
            secondary: mongoose.Schema.Types.Mixed,
            stressors: String,
            severity: String
        },
        management_plan: {
            pharmacological: mongoose.Schema.Types.Mixed,
            psychotherapy: mongoose.Schema.Types.Mixed,
            lifestyle: mongoose.Schema.Types.Mixed,
            ai_check_results: mongoose.Schema.Types.Mixed,
            _ai_metadata: mongoose.Schema.Types.Mixed
        },
        follow_up: {
            timeline: String,
            digital_tools: [String]
        },
        prescription: {
            patient_name: String,
            diagnosis_summary: String,
            medications: [{
                name: String,
                dose: String,
                duration: String,
                instructions: String
            }],
            emergency_warning_signs: [String],
            follow_up_date: String,
            signature: {
                name: String,
                role: String,
                signed_at: Date
            }
        }
    },
    cancel_reason: {
        type: String
    },
    last_synced_at: {
        type: Date
    },
    createdAt: {
        type: Date,
        default: Date.now
    }
});

// Auto-increment ID
ConsultSchema.pre('save', async function () {
    if (!this.isNew) {
        return;
    }

    const counter = await Counter.findByIdAndUpdate(
        { _id: 'consultId' },
        { $inc: { seq: 1 } },
        { new: true, upsert: true }
    );
    this.consultId = counter.seq;
});

module.exports = mongoose.model('Consult', ConsultSchema);
