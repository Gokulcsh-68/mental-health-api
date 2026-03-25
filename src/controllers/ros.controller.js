const ROS = require('../models/ROS');
const User = require('../models/User');
const openAIService = require('../services/OpenAIService');
const { autoColorCode } = require('../utils/colorCode');
const { resolvePatient } = require('../utils/patientHelper');
const logger = require('../config/logger');
const { filterQuestions } = require('../utils/questionFilter');
const ROSQuestionnaire = require('../config/rosQuestionnaire');

/**
 * @desc    Get ROS questionnaire structure (filtered by demographics/view)
 * @route   GET /api/v1/ros/questions
 */
exports.getROSQuestions = (req, res) => {
    const { age, gender, view } = req.query;

    // Default to professional if view not specified
    const filtered = filterQuestions(ROSQuestionnaire, {
        age: age ? parseInt(age) : null,
        gender: gender || null,
        view: view || 'professional'
    });

    res.status(200).json({
        code: 200,
        message: 'ROS questionnaire retrieved',
        data: filtered
    });
};

/**
 * @desc    Create ROS with detailed answers + AI analysis
 * @route   POST /api/v1/ros
 *
 * @body Example:
 * {
 *   "patient_id": 9,
 *   "psychiatric": {
 *     "depressed_mood": true, "depressed_mood_duration": "2 months", "depressed_mood_severity": 8,
 *     "anxiety": true, "anxiety_type": "Panic attacks", "panic_attacks": true,
 *     "mania": false,
 *     "psychosis": true, "psychosis_type": ["Auditory hallucinations"],
 *     "substance_use": true, "substance_types": ["Cannabis"], "substance_frequency": "Daily"
 *   },
 *   "medical": {
 *     "thyroid_symptoms": true, "thyroid_type": "Hypothyroidism", "thyroid_diagnosed": true,
 *     "medication_history": true, "medications_list": "Prednisolone 30mg"
 *   },
 *   "extra_notes": "Patient is on corticosteroids for autoimmune condition"
 * }
 */
exports.createROS = async (req, res) => {
    try {
        const { patient_id, consult_id, psychiatric = {}, medical = {}, extra_notes } = req.body;

        if (!patient_id) {
            return res.status(400).json({ code: 400, message: 'patient_id is required', data: null });
        }

        // Resolve patient + demographics
        const resolved = await resolvePatient(patient_id);
        if (!resolved) return res.status(404).json({ code: 404, message: `Patient ${patient_id} not found`, data: null });
        const { resolvedId: resolvedPatientId, age, gender } = resolved;

        // AI analyzes all the detailed answers with patient context
        const ai = await openAIService.analyzeROS(psychiatric, medical, extra_notes || '', { age, gender });

        const doc = await ROS.create({
            patient: resolvedPatientId,
            consult_id: consult_id || null,
            status: 'completed',
            extra_notes: extra_notes || null,

            psychiatric: {
                depressed_mood: !!psychiatric.depressed_mood,
                depressed_mood_duration: psychiatric.depressed_mood_duration || null,
                depressed_mood_severity: psychiatric.depressed_mood_severity || null,

                anxiety: !!psychiatric.anxiety,
                anxiety_type: psychiatric.anxiety_type || null,
                panic_attacks: !!psychiatric.panic_attacks,

                mania: !!psychiatric.mania,
                mania_duration: psychiatric.mania_duration || null,
                mania_features: psychiatric.mania_features || [],

                psychosis: !!psychiatric.psychosis,
                psychosis_type: psychiatric.psychosis_type || [],
                psychosis_trigger: psychiatric.psychosis_trigger || null,

                ocd_symptoms: !!psychiatric.ocd_symptoms,
                ocd_details: psychiatric.ocd_details || null,

                ptsd_symptoms: !!psychiatric.ptsd_symptoms,
                trauma_type: psychiatric.trauma_type || null,
                trauma_date: psychiatric.trauma_date || null,

                substance_use: !!psychiatric.substance_use,
                substance_types: psychiatric.substance_types || [],
                substance_frequency: psychiatric.substance_frequency || null,

                cognitive_decline: !!psychiatric.cognitive_decline,
                cognitive_domains: psychiatric.cognitive_domains || [],
                cognitive_onset: psychiatric.cognitive_onset || null
            },

            medical: {
                thyroid_symptoms: !!medical.thyroid_symptoms,
                thyroid_type: medical.thyroid_type || null,
                thyroid_diagnosed: !!medical.thyroid_diagnosed,

                seizure_history: !!medical.seizure_history,
                seizure_type: medical.seizure_type || null,
                seizure_on_medication: !!medical.seizure_on_medication,

                head_injury: !!medical.head_injury,
                head_injury_severity: medical.head_injury_severity || null,
                head_injury_date: medical.head_injury_date || null,

                chronic_illness: !!medical.chronic_illness,
                chronic_illness_details: medical.chronic_illness_details || null,

                medication_history: !!medical.medication_history,
                medications_list: medical.medications_list || null,

                hormonal_changes: !!medical.hormonal_changes,
                hormonal_type: medical.hormonal_type || null
            },

            organic_red_flags: ai.organic_red_flags || [],
            medication_induced_risk: ai.medication_induced_risk || [],
            substance_induced_probability: ai.substance_induced_probability || 'None',
            ai_notes: ai.ai_notes || null,
            color_code: ai.color_code || autoColorCode(20)
        });

        // Trigger Red Flag Alerts if organic risks are detected
        if (doc.organic_red_flags.length > 0) {
            const AlertService = require('../services/AlertService');
            // User is already required at the top of the file

            // Find patient to get their reportingTo (Psychiatrist)
            const patient = await User.findById(doc.patient);
            if (patient && patient.reportingTo) {
                await AlertService.triggerRedFlagAlert(
                    patient.reportingTo,
                    { id: patient._id, name: `${patient.firstName} ${patient.lastName}` },
                    doc.organic_red_flags
                );
                doc.redFlagNotified = true;
                await doc.save();
            }
        }

        res.status(201).json({ code: 201, message: 'ROS created with AI analysis', data: doc });

    } catch (err) {
        logger.error('Create ROS Error: %s', err.message);
        res.status(500).json({ code: 500, message: err.message, data: null });
    }
};

/**
 * @desc    Get ROS with filters
 * @route   GET /api/v1/ros
 */
exports.getROS = async (req, res) => {
    try {
        const { 
            patient_id, 
            consult_id, 
            substance_induced_probability, 
            color_code, 
            status,
            startDate,
            endDate,
            red_flag
        } = req.query;
        let query = {};

        // 1. Role-based filtering
        if (req.user.role === 'patient') {
            // Patient always sees only their own history
            query.patient = req.user._id;
        } else if (patient_id) {
            if (!/^[0-9a-fA-F]{24}$/.test(String(patient_id))) {
                const user = await User.findOne({ userId: patient_id });
                if (user) query.patient = user._id;
            } else {
                query.patient = patient_id;
            }
        }
        if (consult_id) query.consult_id = consult_id;
        if (substance_induced_probability) query.substance_induced_probability = substance_induced_probability;
        if (color_code) query.color_code = color_code;
        if (status) query.status = status;

        // Date Range Filter
        if (startDate || endDate) {
            query.createdAt = {};
            if (startDate) query.createdAt.$gte = new Date(startDate);
            if (endDate) query.createdAt.$lte = new Date(endDate);
        }

        // Red Flag Keyword Search
        if (red_flag) {
            query.organic_red_flags = { $regex: new RegExp(red_flag, 'i') };
        }

        const docs = await ROS.find(query)
            .populate('patient', 'name userId gender')
            .sort({ createdAt: -1 });

        res.status(200).json({ code: 200, message: 'ROS retrieved successfully', data: docs });

    } catch (err) {
        logger.error('Get ROS Error: %s', err.message);
        res.status(500).json({ code: 500, message: err.message, data: null });
    }
};

/**
 * @desc    Get single ROS record by ID
 * @route   GET /api/v1/ros/:id
 */
exports.getROSById = async (req, res) => {
    try {
        const doc = await ROS.findOne({ 
            $or: [
                { _id: req.params.id.match(/^[0-9a-fA-F]{24}$/) ? req.params.id : null },
                { rosId: !isNaN(req.params.id) ? parseInt(req.params.id) : null }
            ]
        }).populate('patient', 'name userId gender');

        if (!doc) {
            return res.status(404).json({ code: 404, message: 'ROS record not found', data: null });
        }

        // Security check: Patients can only see their own ROS records
        if (req.user.role === 'patient' && doc.patient._id.toString() !== req.user._id.toString()) {
            return res.status(403).json({ code: 403, message: 'Not authorized to view this record', data: null });
        }

        res.status(200).json({ code: 200, message: 'ROS record retrieved', data: doc });

    } catch (err) {
        logger.error('Get ROS By ID Error: %s', err.message);
        res.status(500).json({ code: 500, message: err.message, data: null });
    }
};
