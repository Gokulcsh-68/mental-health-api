const MSE = require('../models/MSE');
const User = require('../models/User');
const openAIService = require('../services/OpenAIService');
const logger = require('../config/logger');
const { autoColorCode } = require('../utils/colorCode');
const { filterQuestions } = require('../utils/questionFilter');
const MSEQuestionnaire = require('../config/mseQuestionnaire');

logger.info('MSE Controller Loaded. Questionnaire size: ' + (MSEQuestionnaire ? MSEQuestionnaire.length : 'null'));

exports.getMSEQuestions = (req, res) => {
    try {
        const { age, gender, view } = req.query;

        // Default to professional if view not specified
        const filtered = filterQuestions(MSEQuestionnaire, {
            age: age ? parseInt(age) : null,
            gender: gender || null,
            view: view || 'professional'
        });

        res.status(200).json({
            code: 200,
            message: 'MSE questionnaire retrieved',
            data: filtered
        });
    } catch (err) {
        logger.error('getMSEQuestions Controller Error: %s', err.message);
        res.status(500).json({
            code: 500,
            message: `${err.message} (Caught in Controller)`,
            data: null
        });
    }
};

/**
 * @desc    Create MSE record with AI analysis
 * @route   POST /api/v1/mse
 */
exports.createMSE = async (req, res) => {
    try {
        const {
            patient_id, consult_id,
            appearance = {}, behavior = {}, speech = {}, mood = {},
            affect = {}, thought_form = {}, thought_content = {},
            perception = {}, insight = {}, judgment = {}, cognition = {}
        } = req.body;

        if (!patient_id) return res.status(400).json({ code: 400, message: 'patient_id is required', data: null });

        let patient;
        if (/^[0-9a-fA-F]{24}$/.test(String(patient_id))) {
            patient = await User.findById(patient_id).select('_id dateOfBirth gender firstName lastName');
        } else {
            patient = await User.findOne({ userId: patient_id }).select('_id dateOfBirth gender firstName lastName');
        }
        if (!patient) return res.status(404).json({ code: 404, message: `Patient ${patient_id} not found`, data: null });

        const resolvedPatientId = patient._id;
        
        // 🛡️ Security Check: Patients can only create MSE for themselves
        if (req.user.role === 'patient' && resolvedPatientId.toString() !== req.user._id.toString()) {
            return res.status(403).json({ code: 403, message: 'Not authorized to create MSE for this patient', data: null });
        }
        const dob = patient.dateOfBirth;
        const age = dob ? Math.floor((Date.now() - new Date(dob).getTime()) / (1000 * 60 * 60 * 24 * 365.25)) : null;
        const gender = patient.gender || null;

        // AI analyzes MSE findings enriched with patient demographics
        const ai = await openAIService.analyzeMSE({
            appearance, behavior, speech, mood, affect,
            thought_form, thought_content, perception, insight, judgment, cognition
        }, { age, gender });

        const doc = await MSE.create({
            patient: resolvedPatientId,
            consult_id: consult_id || null,
            status: 'completed',

            appearance: {
                grooming: appearance.grooming || null,
                dress: appearance.dress || null,
                hygiene: appearance.hygiene || null,
                eye_contact: appearance.eye_contact || null,
                notes: appearance.notes || null
            },
            behavior: {
                attitude: behavior.attitude || null,
                psychomotor: behavior.psychomotor || null,
                mannerisms: behavior.mannerisms || [],
                notes: behavior.notes || null
            },
            speech: {
                rate: speech.rate || null,
                volume: speech.volume || null,
                articulation: speech.articulation || null,
                spontaneity: speech.spontaneity || null,
                notes: speech.notes || null
            },
            mood: {
                subjective: mood.subjective || null,
                clinician_observed: mood.clinician_observed || null
            },
            affect: {
                quality: affect.quality || null,
                range: affect.range || null,
                appropriateness: affect.appropriateness || null,
                notes: affect.notes || null
            },
            thought_form: {
                process: thought_form.process || null,
                coherence: thought_form.coherence || null,
                notes: thought_form.notes || null
            },
            thought_content: {
                delusions: !!thought_content.delusions,
                delusion_types: thought_content.delusion_types || [],
                suicidal_ideation: thought_content.suicidal_ideation || 'None',
                homicidal_ideation: thought_content.homicidal_ideation || 'None',
                obsessions: !!thought_content.obsessions,
                phobias: !!thought_content.phobias,
                other_content: thought_content.other_content || null
            },
            perception: {
                hallucinations: !!perception.hallucinations,
                hallucination_types: perception.hallucination_types || [],
                hallucination_details: perception.hallucination_details || null,
                illusions: !!perception.illusions,
                depersonalization: !!perception.depersonalization,
                derealization: !!perception.derealization
            },
            insight: {
                level: insight.level || null,
                description: insight.description || null
            },
            judgment: {
                level: judgment.level || null,
                notes: judgment.notes || null
            },
            cognition: {
                orientation: {
                    person: cognition.orientation?.person !== false,
                    place: cognition.orientation?.place !== false,
                    time: cognition.orientation?.time !== false
                },
                memory: cognition.memory || null,
                concentration: cognition.concentration || null,
                cognitive_test: cognition.cognitive_test || null,
                cognitive_score: cognition.cognitive_score ?? null,
                cognitive_max: cognition.cognitive_max ?? null
            },
            ai_analysis: {
                affect_recognition: ai.affect_recognition || null,
                speech_tempo_analysis: ai.speech_tempo_analysis || null,
                emotional_tone_mapping: ai.emotional_tone_mapping || [],
                psychomotor_markers: ai.psychomotor_markers || [],
                clinical_formulation: ai.clinical_formulation || null,
                diagnostic_impressions: ai.diagnostic_impressions || []
            },
            color_code: ai.color_code || autoColorCode(20)
        });

        // Trigger Red Flag Alerts if critical symptoms are detected
        const redFlags = [];
        if (doc.thought_content.suicidal_ideation !== 'None') redFlags.push(`Suicidal Ideation (${doc.thought_content.suicidal_ideation})`);
        if (doc.thought_content.homicidal_ideation !== 'None') redFlags.push(`Homicidal Ideation (${doc.thought_content.homicidal_ideation})`);
        if (doc.thought_content.delusions) redFlags.push('Delusions Recognized');
        if (doc.perception.hallucinations) redFlags.push('Hallucinations Recognized');

        if (redFlags.length > 0) {
            const AlertService = require('../services/AlertService');
            // User is already required at the top of the file

            // Find patient to get their reportingTo (Psychiatrist)
            const patientRecord = await User.findById(doc.patient);
            if (patientRecord && patientRecord.reportingTo) {
                await AlertService.triggerRedFlagAlert(
                    patientRecord.reportingTo,
                    { id: patientRecord._id, name: `${patientRecord.firstName} ${patientRecord.lastName}` },
                    redFlags
                );
                doc.redFlagNotified = true;
                await doc.save();
            }
        }

        res.status(201).json({ code: 201, message: 'MSE created with AI analysis', data: doc });

    } catch (err) {
        logger.error('Create MSE Error: %s', err.message);
        res.status(500).json({ code: 500, message: err.message, data: null });
    }
};

exports.getMSE = async (req, res) => {
    try {
        const { patient_id, consult_id, startDate, endDate, color_code, insight_level, memory } = req.query;
        let query = {};

        // Role-based filtering: Patients can only see their own MSE records
        if (req.user.role === 'patient') {
            query.patient = req.user._id;
        } else if (patient_id) {
            // Handle patient_id lookup for non-patient roles
            if (!/^[0-9a-fA-F]{24}$/.test(String(patient_id))) {
                const patientIdNum = Number(patient_id);
                if (!isNaN(patientIdNum)) {
                    const user = await User.findOne({ userId: patientIdNum });
                    if (user) {
                        query.patient = user._id;
                    } else {
                        return res.status(404).json({ code: 404, message: 'Patient not found', data: [] });
                    }
                }
            } else {
                query.patient = patient_id;
            }
        }
        
        if (consult_id) query.consult_id = consult_id;
        
        // --- NEW COMPREHENSIVE FILTERS ---
        if (color_code) query.color_code = color_code;
        if (insight_level) query['insight.level'] = { $regex: new RegExp(insight_level, 'i') };
        if (memory) query['cognition.memory'] = { $regex: new RegExp(memory, 'i') };

        // Date Range Filter
        if (startDate || endDate) {
            query.createdAt = {};
            if (startDate) query.createdAt.$gte = new Date(startDate);
            if (endDate) query.createdAt.$lte = new Date(new Date(endDate).setHours(23, 59, 59, 999));
        }

        // Corrected populate to use firstName and lastName instead of non-existent name
        const docs = await MSE.find(query)
            .populate('patient', 'firstName lastName userId gender')
            .sort({ createdAt: -1 });

        res.status(200).json({ code: 200, message: 'MSE retrieved successfully', data: docs });
    } catch (err) {
        logger.error('getMSE Controller Error: %s', err.message);
        res.status(500).json({ code: 500, message: err.message, data: null });
    }
};
/**
 * @desc    Get single MSE record
 * @route   GET /api/v1/mse/:id
 */
exports.getMSEById = async (req, res) => {
    try {
        const { id } = req.params;
        let mse;

        if (/^[0-9a-fA-F]{24}$/.test(id)) {
            mse = await MSE.findById(id).populate('patient', 'firstName lastName userId gender');
        } else if (!isNaN(Number(id))) {
            mse = await MSE.findOne({ mseId: Number(id) }).populate('patient', 'firstName lastName userId gender');
        }

        if (!mse) {
            return res.status(404).json({ code: 404, message: 'MSE record not found', data: null });
        }

        // Security check: Patients can only see their own MSE records
        if (req.user.role === 'patient' && mse.patient._id.toString() !== req.user._id.toString()) {
            return res.status(403).json({ code: 403, message: 'Not authorized to view this record', data: null });
        }

        res.status(200).json({ code: 200, message: 'MSE record retrieved successfully', data: mse });
    } catch (err) {
        logger.error('getMSEById Controller Error: %s', err.message);
        res.status(500).json({ code: 500, message: err.message, data: null });
    }
};
