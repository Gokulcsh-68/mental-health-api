const HistoryOfIllness = require('../models/HistoryOfIllness');
const { sendSuccess, sendPaginated, sendError } = require('../utils/responseHelper');
const openAIService = require('../services/OpenAIService');
const { resolvePatient } = require('../utils/patientHelper');
const logger = require('../config/logger');
const { encrypt, decrypt } = require('../utils/encryption');

/**
 * @desc    Extract HPI info from narrative via AI — returns preview, does NOT save to DB
 * @route   POST /api/v1/history-of-illness/extract
 * @access  Private
 */
exports.extractHistoryOfIllness = async (req, res, next) => {
    try {
        const { patient_id, narrative } = req.body || {};

        if (!patient_id) return sendError(res, 400, 'patient_id is required');
        if (!narrative) return sendError(res, 400, 'narrative is required for AI extraction');

        const { age, gender, resolvedId } = resolved;
        
        // 🛡️ Security Check: Patient can only extract for themselves
        if (req.user.role === 'patient' && resolvedId.toString() !== req.user._id.toString()) {
            return sendError(res, 403, 'Not authorized to extract history for this patient');
        }

        logger.info('History of Illness AI extraction (preview) started');
        const ai = await openAIService.extractClinicalInfo(narrative, { age, gender });

        const preview = {
            narrative,
            ai_summary: ai.ai_summary || null,
            ai_extraction_metadata: {
                model: ai._meta?.model || null,
                extracted_at: new Date(),
                is_mock: ai._meta?.is_mock ?? false
            },
            structured: {
                onset: ai.hpi?.onset || null,
                duration: ai.hpi?.duration || null,
                course: ai.hpi?.course || null,
                mood_features: ai.hpi?.mood_features || [],
                anxiety_features: ai.hpi?.anxiety_features || [],
                psychotic_features: ai.hpi?.psychotic_features || [],
                sleep: ai.hpi?.sleep || null,
                appetite: ai.hpi?.appetite || null,
                energy: ai.hpi?.energy || null,
                cognitive: ai.hpi?.cognitive || [],
                suicidal_ideation: ai.hpi?.suicidal_ideation || null,
                previous_episodes: ai.hpi?.previous_episodes || null,
                treatment_response: ai.hpi?.treatment_response || null,
                dsm5_mapping: ai.hpi?.dsm5_mapping || [],
                severity_index: ai.hpi?.severity_index || 0,
                recommendations: ai.hpi?.recommendations || []
            },
            risk_markers: {
                self_harm_detected: ai.risk_markers?.self_harm_detected ?? false,
                violence_detected: ai.risk_markers?.violence_detected ?? false,
                psychosis_detected: ai.risk_markers?.psychosis_detected ?? false,
                substance_use_detected: ai.risk_markers?.substance_use_detected ?? false,
                keywords_found: ai.risk_markers?.keywords_found || [],
                risk_level: ai.risk_markers?.risk_level || 'None'
            },
            color_code: ai.hpi?.color_code || ai.color_code || '#4CAF50'
        };

        sendSuccess(res, 200, 'HPI extraction complete — review before saving', preview);
    } catch (err) {
        next(err);
    }
};

/**
 * @desc    Confirm and save HPI to DB
 * @route   POST /api/v1/history-of-illness
 * @access  Private
 */
exports.confirmHistoryOfIllness = async (req, res, next) => {
    try {
        const {
            consult_id, patient_id, narrative, 
            ai_summary, ai_extraction_metadata,
            structured, risk_markers, color_code,
            voice_recording_url, transcription_language, transcription_confidence
        } = req.body;

        if (!patient_id) return sendError(res, 400, 'patient_id is required');
        if (!narrative) return sendError(res, 400, 'narrative is required');

        const resolved = await resolvePatient(patient_id);
        if (!resolved) return sendError(res, 404, `Patient with userId ${patient_id} not found`);

        // 🛡️ Security Check: Patient can only confirm for themselves
        if (req.user.role === 'patient' && resolved.resolvedId.toString() !== req.user._id.toString()) {
            return sendError(res, 403, 'Not authorized to save history for this patient');
        }

        const doc = await HistoryOfIllness.create({
            consult_id,
            patient: resolved.resolvedId,
            status: 'completed',
            narrative: encrypt(narrative),
            voice_recording_url,
            transcription_language: transcription_language || 'en',
            transcription_confidence,
            ai_summary,
            ai_extraction_metadata,
            structured,
            risk_markers,
            color_code: color_code || '#4CAF50'
        });

        const raw = doc.toObject();
        raw.narrative = narrative;

        sendSuccess(res, 201, 'History of illness saved successfully', raw);
    } catch (err) {
        next(err);
    }
};

/**
 * @desc    Get all HPI records
 * @route   GET /api/v1/history-of-illness
 */
exports.getHistoryOfIllnesses = async (req, res, next) => {
    try {
        const { patient_id, consult_id, page = 1, limit = 10 } = req.query;
        const filter = {};

        if (req.user.role === 'patient') {
            filter.patient = req.user._id;
        } else if (patient_id) {
            const resolved = await resolvePatient(patient_id);
            if (resolved) filter.patient = resolved.resolvedId;
        }
        if (consult_id) filter.consult_id = Number(consult_id);

        const skip = (Number(page) - 1) * Number(limit);
        const [data, total] = await Promise.all([
            HistoryOfIllness.find(filter)
                .sort({ createdAt: -1 })
                .skip(skip)
                .limit(Number(limit))
                .populate('patient', 'firstName lastName userId'),
            HistoryOfIllness.countDocuments(filter)
        ]);

        const decrypted = data.map(d => {
            const raw = d.toObject();
            if (raw.narrative) raw.narrative = decrypt(raw.narrative);
            return raw;
        });

        sendPaginated(res, 200, 'HPI records fetched successfully', decrypted, {
            page: Number(page),
            limit: Number(limit),
            total,
            totalPages: Math.ceil(total / Number(limit))
        });
    } catch (err) {
        next(err);
    }
};

/**
 * @desc    Get single HPI by ID
 * @route   GET /api/v1/history-of-illness/:hpiId
 */
exports.getHistoryOfIllnessById = async (req, res, next) => {
    try {
        const doc = await HistoryOfIllness.findOne({ historyOfIllnessId: Number(req.params.hpiId) })
            .populate('patient', 'firstName lastName userId');
            
        if (!doc) return sendError(res, 404, 'HPI record not found');

        // 🛡️ Security Check: Patient can only view their own record
        if (req.user.role === 'patient' && doc.patient._id.toString() !== req.user._id.toString()) {
            return sendError(res, 403, 'Not authorized to view this record');
        }

        const raw = doc.toObject();
        if (raw.narrative) raw.narrative = decrypt(raw.narrative);

        sendSuccess(res, 200, 'HPI record fetched successfully', raw);
    } catch (err) {
        next(err);
    }
};

/**
 * @desc    Update HPI (Override)
 * @route   PATCH /api/v1/history-of-illness/:hpiId
 */
exports.updateHistoryOfIllness = async (req, res, next) => {
    try {
        const docRecord = await HistoryOfIllness.findOne({ historyOfIllnessId: Number(req.params.hpiId) });
        if (!docRecord) return sendError(res, 404, 'HPI record not found');

        // 🛡️ Security Check: Patient can only update their own record
        if (req.user.role === 'patient' && docRecord.patient.toString() !== req.user._id.toString()) {
            return sendError(res, 403, 'Not authorized to update this record');
        }

        const updates = req.body;
        if (updates.narrative) updates.narrative = encrypt(updates.narrative);

        const doc = await HistoryOfIllness.findOneAndUpdate(
            { historyOfIllnessId: Number(req.params.hpiId) },
            { 
                ...updates,
                doctor_override: {
                    applied: true,
                    overridden_at: new Date(),
                    overridden_by: req.user._id,
                    override_notes: updates.override_notes || 'Manual update'
                }
            },
            { returnDocument: 'after' }
        );

        if (!doc) return sendError(res, 404, 'HPI record not found');

        const raw = doc.toObject();
        if (raw.narrative) raw.narrative = decrypt(raw.narrative);

        sendSuccess(res, 200, 'HPI record updated successfully', raw);
    } catch (err) {
        next(err);
    }
};

/**
 * @desc    Delete HPI
 * @route   DELETE /api/v1/history-of-illness/:hpiId
 */
exports.deleteHistoryOfIllness = async (req, res, next) => {
    try {
        const doc = await HistoryOfIllness.findOne({ historyOfIllnessId: Number(req.params.hpiId) });
        if (!doc) return sendError(res, 404, 'HPI record not found');

        // 🛡️ Security Check: Patient can only delete their own record
        if (req.user.role === 'patient' && doc.patient.toString() !== req.user._id.toString()) {
            return sendError(res, 403, 'Not authorized to delete this record');
        }

        await HistoryOfIllness.findOneAndDelete({ historyOfIllnessId: Number(req.params.hpiId) });

        sendSuccess(res, 200, 'HPI record deleted successfully', null);
    } catch (err) {
        next(err);
    }
};
