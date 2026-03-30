const PastHistory = require('../models/PastHistory');
const User = require('../models/User');
const PastHistoryQuestionnaire = require('../config/pastHistoryQuestionnaire');
const { filterQuestions } = require('../utils/questionFilter');
const { resolvePatient } = require('../utils/patientHelper');
const openAIService = require('../services/OpenAIService');
const { autoColorCode } = require('../utils/colorCode');
const { encrypt, decrypt } = require('../utils/encryption');
const logger = require('../config/logger');

/**
 * @desc    Get Past History questionnaire structure
 * @route   GET /api/v1/past-history/questions
 */
exports.getQuestions = (req, res) => {
    const { age, gender, view } = req.query;

    const filtered = filterQuestions(PastHistoryQuestionnaire, {
        age: age ? parseInt(age) : null,
        gender: gender || null,
        view: view || 'professional'
    });

    res.status(200).json({
        code: 200,
        message: 'Past History questionnaire retrieved',
        data: filtered
    });
};

/**
 * @desc    AI-powered extraction of Past History from narrative
 * @route   POST /api/v1/past-history/extract
 */
exports.extractPastHistory = async (req, res) => {
    try {
        const { narrative, patient_id } = req.body;

        if (!narrative) {
            return res.status(400).json({ code: 400, message: 'narrative is required', data: null });
        }

        const resolved = await resolvePatient(patient_id);
        if (!resolved) return res.status(404).json({ code: 404, message: 'Patient not found' });

        // 🛡️ Security: Patient can only extract for themselves
        if (req.user.role === 'patient' && resolved.resolvedId.toString() !== req.user._id.toString()) {
            return res.status(403).json({ code: 403, message: 'Not authorized to extract history for this patient' });
        }

        const { age, gender } = resolved;
        const extracted = await openAIService.extractPastHistory(narrative, { age, gender });

        // Use standard sendSuccess utility if available, or return clear data
        return res.status(200).json({
            code: 200,
            message: 'A-Z clinical history extracted from narrative',
            data: extracted
        });
    } catch (err) {
        logger.error('Extract PastHistory Error: %s', err.message);
        res.status(500).json({ code: 500, message: err.message, data: null });
    }
};

/**
 * @desc    AI-powered analysis of structured Past History (Manual Mode Preview)
 * @route   POST /api/v1/past-history/analyze
 */
exports.analyzePastHistory = async (req, res) => {
    try {
        const {
            patient_id,
            psychiatric_history,
            medical_history,
            family_history,
            substance_use,
            trauma_history
        } = req.body;

        const resolved = await resolvePatient(patient_id);
        if (!resolved) return res.status(404).json({ code: 404, message: 'Patient not found' });

        // 🛡️ Security: Patient can only analyze for themselves
        if (req.user.role === 'patient' && resolved.resolvedId.toString() !== req.user._id.toString()) {
            return res.status(403).json({ code: 403, message: 'Not authorized to analyze history for this patient' });
        }

        const { age, gender } = resolved;
        const analysis = await openAIService.analyzePastHistory(
            psychiatric_history, medical_history, substance_use, family_history, trauma_history, { age, gender }
        );

        return res.status(200).json({
            code: 200,
            message: 'Clinical risk analysis completed for structured history',
            data: analysis
        });
    } catch (err) {
        logger.error('Analyze PastHistory Error: %s', err.message);
        res.status(500).json({ code: 500, message: err.message, data: null });
    }
};

/**
 * @desc    Create/Save A-Z Past History record
 * @route   POST /api/v1/past-history
 */
exports.createPastHistory = async (req, res) => {
    try {
        const {
            patient_id,
            consult_id,
            narrative,
            psychiatric_history,
            medical_history,
            family_history,
            substance_use,
            developmental_history,
            social_history,
            trauma_history
        } = req.body;

        if (!patient_id) {
            return res.status(400).json({ code: 400, message: 'patient_id is required', data: null });
        }

        const resolved = await resolvePatient(patient_id);
        if (!resolved) return res.status(404).json({ code: 404, message: `Patient ${patient_id} not found`, data: null });
        const { resolvedId: resolvedPatientId, age, gender } = resolved;

        // 🛡️ Security: Patient can only create for themselves
        if (req.user.role === 'patient' && resolvedPatientId.toString() !== req.user._id.toString()) {
            return res.status(403).json({ code: 403, message: 'Not authorized to create history for this patient' });
        }

        // Perform AI Risk Analysis on the structured data
        const aiAnalysis = await openAIService.analyzePastHistory(
            psychiatric_history, medical_history, substance_use, family_history, trauma_history, { age, gender }
        );

        const doc = await PastHistory.create({
            patient: resolvedPatientId,
            consult_id: consult_id || null,
            status: 'completed',
            narrative: encrypt(narrative),
            
            psychiatric_history,
            medical_history,
            family_history,
            substance_use,
            developmental_history,
            social_history,
            trauma_history,

            risk_flags: aiAnalysis.risk_flags || [],
            treatment_resistance_risk: aiAnalysis.treatment_resistance_risk || 'None',
            genetic_risk_summary: aiAnalysis.genetic_risk_summary || null,
            ai_notes: aiAnalysis.ai_notes || null,
            color_code: aiAnalysis.color_code || autoColorCode(20),

            ai_extraction_metadata: {
                model_version: 'gpt-4-clinical-az',
                extraction_date: new Date(),
                confidence_score: 0.95
            }
        });

        // Audit Log
        logger.info('PastHistory Created | Patient: %s | Specialist: %s', resolvedPatientId, req.user._id);

        res.status(201).json({ code: 201, message: 'Comprehensive A-Z history saved', data: doc });

    } catch (err) {
        logger.error('Create PastHistory Error: %s', err.message);
        res.status(500).json({ code: 500, message: err.message, data: null });
    }
};

/**
 * @desc    Get specific history by ID (with decryption)
 * @route   GET /api/v1/past-history/:id
 */
exports.getPastHistoryById = async (req, res) => {
    try {
        const doc = await PastHistory.findById(req.params.id).populate('patient', 'firstName lastName userId');
        if (!doc) return res.status(404).json({ code: 404, message: 'Record not found' });

        // Security: Patient can only view their own record
        if (req.user.role === 'patient' && doc.patient._id.toString() !== req.user._id.toString()) {
            return res.status(403).json({ code: 403, message: 'Not authorized to view this record' });
        }

        // Decrypt narrative for view
        const plain = doc.toObject();
        plain.narrative = decrypt(doc.narrative);

        res.status(200).json({ code: 200, data: plain });
    } catch (err) {
        res.status(500).json({ code: 500, message: err.message });
    }
};

/**
 * @desc    Update (Override) Past History
 * @route   PATCH /api/v1/past-history/:id
 */
exports.updatePastHistory = async (req, res) => {
    try {
        const doc = await PastHistory.findById(req.params.id);
        if (!doc) return res.status(404).json({ code: 404, message: 'Record not found' });

        // 🛡️ Security: Patient can only update their own record
        if (req.user.role === 'patient' && doc.patient.toString() !== req.user._id.toString()) {
            return res.status(403).json({ code: 403, message: 'Not authorized to update this record' });
        }

        // Merge fields for override
        const updates = req.body;
        
        // Track override for audit
        doc.doctor_override = {
            is_overridden: true,
            overridden_by: req.user._id,
            override_date: new Date(),
            notes: updates.override_notes || 'Manual specialist adjustment'
        };

        // Apply updates
        Object.assign(doc, updates);

        await doc.save();
        logger.info('PastHistory Overridden | ID: %s | Specialist: %s', doc._id, req.user._id);

        res.status(200).json({ code: 200, message: 'History updated with specialist override', data: doc });
    } catch (err) {
        res.status(500).json({ code: 500, message: err.message });
    }
};

/**
 * @desc    Delete History
 * @route   DELETE /api/v1/past-history/:id
 */
exports.deletePastHistory = async (req, res) => {
    try {
        const doc = await PastHistory.findById(req.params.id);
        if (!doc) return res.status(404).json({ code: 404, message: 'Record not found' });

        // 🛡️ Security: Patient can only delete their own record
        if (req.user.role === 'patient' && doc.patient.toString() !== req.user._id.toString()) {
            return res.status(403).json({ code: 403, message: 'Not authorized to delete this record' });
        }

        await PastHistory.findByIdAndDelete(req.params.id);

        logger.warn('PastHistory Deleted | ID: %s | DeletedBy: %s', req.params.id, req.user._id);
        res.status(200).json({ code: 200, message: 'Record deleted' });
    } catch (err) {
        res.status(500).json({ code: 500, message: err.message });
    }
};

/**
 * @desc    List past histories for patient
 * @route   GET /api/v1/past-history
 */
exports.getPastHistory = async (req, res) => {
    try {
        const { patient_id, consult_id } = req.query;
        let query = {};

        // 🛡️ Security Check & Multi-View logic
        if (req.user.role === 'patient') {
            // Patient always sees only their own history
            query.patient = req.user._id;
        } else if (patient_id) {
            // Professionals can query for specific patients
            const resolved = await resolvePatient(patient_id);
            if (resolved) query.patient = resolved.resolvedId;
        } else if (req.user.role === 'family') {
            // Family role might need special filtering (e.g., records with consent)
            // For now, let's keep it restricted or default to zero records if no patient_id provided
            return res.status(200).json({ code: 200, message: 'Please specify a patient_id to view history', data: [] });
        }

        if (consult_id) query.consult_id = consult_id;

        const docs = await PastHistory.find(query)
            .populate('patient', 'firstName lastName userId gender')
            .sort({ createdAt: -1 });

        res.status(200).json({ code: 200, message: 'Records retrieved', data: docs });

    } catch (err) {
        logger.error('Get PastHistory Error: %s', err.message);
        res.status(500).json({ code: 500, message: err.message, data: null });
    }
};
