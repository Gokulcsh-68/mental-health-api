const ChiefComplaint = require('../models/ChiefComplaint');
const { sendSuccess, sendPaginated, sendError } = require('../utils/responseHelper');
const openAIService = require('../services/OpenAIService');
const { autoColorCode } = require('../utils/colorCode');
const { resolvePatient } = require('../utils/patientHelper');
const logger = require('../config/logger');
const { encrypt, decrypt } = require('../utils/encryption');

/**
 * @desc    Extract clinical info from narrative via AI — returns preview, does NOT save to DB
 * @route   POST /api/v1/chief-complaints/extract
 * @access  Private
 * @body    { patient_id, narrative }
 */
exports.extractChiefComplaint = async (req, res, next) => {
    try {
        const { patient_id, narrative } = req.body || {};

        if (!patient_id) return sendError(res, 400, 'patient_id is required');
        if (!narrative) return sendError(res, 400, 'narrative is required for AI extraction');

        // ── Resolve Patient ──────────────────────────────────
        const resolved = await resolvePatient(patient_id);
        if (!resolved) return sendError(res, 404, `Patient with userId ${patient_id} not found`);
        const { age, gender } = resolved;

        // ── Call OpenAI ──────────────────────────────────────
        logger.info('Chief Complaints AI extraction (preview) started');
        const ai = await openAIService.extractClinicalInfo(narrative, { age, gender });

        // ── Map dates ────────────────────────────────────────
        let onset_date = null;
        if (ai.onset_date) {
            const parsed = new Date(ai.onset_date);
            onset_date = isNaN(parsed.getTime()) ? null : parsed;
        }

        let last_episode_date = null;
        if (ai.previous_episodes?.last_episode_date) {
            const parsed = new Date(ai.previous_episodes.last_episode_date);
            last_episode_date = isNaN(parsed.getTime()) ? null : parsed;
        }

        // ── Build preview (same shape as the DB document) ────
        const preview = {
            narrative,
            ai_summary: ai.ai_summary || null,
            ai_extraction_metadata: {
                model: ai._meta?.model || null,
                extracted_at: new Date(),
                is_mock: ai._meta?.is_mock ?? false
            },

            structured: {
                duration: ai.duration || null,
                severity: ai.severity || null,
                onset_pattern: ai.onset_pattern || null,
                onset_date,
                triggers: ai.triggers || [],
                relieving_factors: ai.relieving_factors || [],
                aggravating_factors: ai.aggravating_factors || [],
                associated_symptoms: ai.associated_symptoms || [],
                affected_domains: ai.affected_domains || {},
                functional_impairment: ai.functional_impairment || null,
                clinical_impression: ai.clinical_impression || null,
                potential_diagnoses: ai.potential_diagnoses || [],
                mse_observations: ai.mse_observations || {},
                psychosocial_stressors: ai.psychosocial_stressors || [],
                protective_factors: ai.protective_factors || [],
                recommendations: ai.recommendations || []
            },

            risk_markers: {
                self_harm_detected: ai.risk_markers?.self_harm_detected ?? false,
                violence_detected: ai.risk_markers?.violence_detected ?? false,
                psychosis_detected: ai.risk_markers?.psychosis_detected ?? false,
                substance_use_detected: ai.risk_markers?.substance_use_detected ?? false,
                keywords_found: ai.risk_markers?.keywords_found || [],
                risk_level: ai.risk_markers?.risk_level || 'None'
            },

            previous_episodes: {
                has_occurred_before: ai.previous_episodes?.has_occurred_before ?? false,
                frequency: ai.previous_episodes?.frequency || null,
                last_episode_date,
                hospitalized_before: ai.previous_episodes?.hospitalized_before ?? false,
                notes: ai.previous_episodes?.notes || null
            },

            color_code: ai.color_code || autoColorCode(
                ai.risk_markers?.risk_level === 'High' ? 80 :
                    ai.risk_markers?.risk_level === 'Moderate' ? 60 :
                        ai.risk_markers?.risk_level === 'Low' ? 40 : 20
            )
        };

        sendSuccess(res, 200, 'AI extraction complete — review before saving', preview);
    } catch (err) {
        next(err);
    }
};

/**
 * @desc    Save the reviewed/confirmed chief complaint to the database
 * @route   POST /api/v1/chief-complaints
 * @access  Private
 * @body    { consult_id, patient_id, narrative, ai_summary, structured, risk_markers,
 *            previous_episodes, color_code, voice_recording_url?, transcription_language?,
 *            transcription_confidence?, ai_extraction_metadata? }
 */
exports.confirmChiefComplaint = async (req, res, next) => {
    try {
        logger.info('Confirm Chief Complaint Request', { body: req.body, contentType: req.headers['content-type'] });
        const {
            consult_id,
            patient_id,
            narrative,
            ai_summary,
            structured,
            risk_markers,
            previous_episodes,
            color_code,
            voice_recording_url,
            transcription_language,
            transcription_confidence,
            ai_extraction_metadata
        } = req.body || {};

        const final_voice_url = req.file ? req.file.location : voice_recording_url;

        if (!patient_id) return sendError(res, 400, 'patient_id is required');
        if (!narrative) return sendError(res, 400, 'narrative is required');

        // ── Resolve Patient ──────────────────────────────────
        const resolved = await resolvePatient(patient_id);
        if (!resolved) return sendError(res, 404, `Patient with userId ${patient_id} not found`);
        const { resolvedId: resolvedPatientId } = resolved;

        // ── Parse dates ──────────────────────────────────────
        let onset_date = null;
        if (structured?.onset_date) {
            const parsed = new Date(structured.onset_date);
            onset_date = isNaN(parsed.getTime()) ? null : parsed;
        }

        let last_episode_date = null;
        if (previous_episodes?.last_episode_date) {
            const parsed = new Date(previous_episodes.last_episode_date);
            last_episode_date = isNaN(parsed.getTime()) ? null : parsed;
        }

        // ── Persist ──────────────────────────────────────────
        const doc = await ChiefComplaint.create({
            consult_id,
            patient: resolvedPatientId,
            status: 'completed',

            narrative: encrypt(narrative),
            voice_recording_url: final_voice_url || null,
            transcription_language: transcription_language || 'en',
            transcription_confidence: transcription_confidence ?? null,

            ai_summary: ai_summary || null,
            ai_extraction_metadata: ai_extraction_metadata || {
                model: null,
                extracted_at: new Date(),
                is_mock: false
            },

            structured: {
                duration: structured?.duration || null,
                severity: structured?.severity || null,
                onset_pattern: structured?.onset_pattern || null,
                onset_date,
                triggers: structured?.triggers || [],
                relieving_factors: structured?.relieving_factors || [],
                aggravating_factors: structured?.aggravating_factors || [],
                associated_symptoms: structured?.associated_symptoms || [],
                affected_domains: structured?.affected_domains || {},
                functional_impairment: structured?.functional_impairment || null,
                clinical_impression: structured?.clinical_impression || null,
                potential_diagnoses: structured?.potential_diagnoses || [],
                mse_observations: structured?.mse_observations || {},
                psychosocial_stressors: structured?.psychosocial_stressors || [],
                protective_factors: structured?.protective_factors || [],
                recommendations: structured?.recommendations || []
            },

            risk_markers: {
                self_harm_detected: risk_markers?.self_harm_detected ?? false,
                violence_detected: risk_markers?.violence_detected ?? false,
                psychosis_detected: risk_markers?.psychosis_detected ?? false,
                substance_use_detected: risk_markers?.substance_use_detected ?? false,
                keywords_found: risk_markers?.keywords_found || [],
                risk_level: risk_markers?.risk_level || 'None'
            },

            previous_episodes: {
                has_occurred_before: previous_episodes?.has_occurred_before ?? false,
                frequency: previous_episodes?.frequency || null,
                last_episode_date,
                hospitalized_before: previous_episodes?.hospitalized_before ?? false,
                notes: previous_episodes?.notes || null
            },

            color_code: color_code || autoColorCode(20)
        });

        // ── Red Flag Alerts ──────────────────────────────────
        const redFlags = [];
        if (doc.risk_markers.self_harm_detected) redFlags.push('Self-Harm Ideation');
        if (doc.risk_markers.violence_detected) redFlags.push('Violent Ideation');
        if (doc.risk_markers.psychosis_detected) redFlags.push('Psychosis/Hallucinations');

        if (redFlags.length > 0) {
            const AlertService = require('../services/AlertService');
            const User = require('../models/User');

            const patient = await User.findById(doc.patient);
            if (patient && patient.reportingTo) {
                await AlertService.triggerRedFlagAlert(
                    patient.reportingTo,
                    { id: patient._id, name: `${patient.firstName} ${patient.lastName}` },
                    redFlags
                );
            }
        }

        const raw = doc.toObject();
        if (raw.narrative) raw.narrative = decrypt(raw.narrative);

        sendSuccess(res, 201, 'Chief complaint saved successfully', raw);
    } catch (err) {
        next(err);
    }
};

exports.getChiefComplaints = async (req, res, next) => {
    try {
        const {
            consult_id,
            patient_id,
            severity,
            status,
            risk_level,
            startDate,
            endDate,
            page = 1,
            limit = 10
        } = req.query;

        const filter = {};

        // 1. Role-based filtering
        if (req.user.role === 'patient') {
            // Patient always sees only their own history
            filter.patient = req.user._id;
        } else if (patient_id) {
            // Professionals can query for specific patients
            if (!isNaN(patient_id)) {
                const User = require('../models/User');
                const user = await User.findOne({ userId: Number(patient_id) });
                if (user) {
                    filter.patient = user._id;
                } else {
                    return sendPaginated(res, 200, 'Patient not found', [], {
                        page: Number(page),
                        limit: Number(limit),
                        total: 0,
                        totalPages: 0
                    });
                }
            } else {
                filter.patient = patient_id;
            }
        }

        // 2. Simple Filters
        if (consult_id) filter.consult_id = Number(consult_id);
        if (status) filter.status = status;

        // 3. Nested Clinical Filters
        if (severity) filter['structured.severity'] = severity;
        if (risk_level) filter['risk_markers.risk_level'] = risk_level;

        // 4. Date Range Filter
        if (startDate || endDate) {
            filter.createdAt = {};
            if (startDate) filter.createdAt.$gte = new Date(startDate);
            if (endDate) filter.createdAt.$lte = new Date(endDate);
        }

        logger.info('Fetching Chief Complaints with filter:', filter);

        const skip = (Number(page) - 1) * Number(limit);
        const [data, total] = await Promise.all([
            ChiefComplaint.find(filter)
                .sort({ createdAt: -1 })
                .skip(skip)
                .limit(Number(limit))
                .populate('patient', 'firstName lastName email userId')
                .populate('doctor_override.overridden_by', 'firstName lastName userId'),
            ChiefComplaint.countDocuments(filter)
        ]);

        sendPaginated(res, 200, 'Chief complaints fetched successfully', data.map(d => {
            const raw = d.toObject();
            if (raw.narrative) raw.narrative = decrypt(raw.narrative);
            return raw;
        }), {
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
 * @desc    Get single chief complaint by its own ID
 * @route   GET /api/v1/chief-complaints/:ccId
 * @access  Private
 */
exports.getChiefComplaintById = async (req, res, next) => {
    try {
        const doc = await ChiefComplaint.findOne({ chiefComplaintId: Number(req.params.ccId) })
            .populate('patient', 'firstName lastName email userId')
            .populate('doctor_override.overridden_by', 'firstName lastName userId');

        if (!doc) return sendError(res, 404, 'Chief complaint not found');

        // Security: Patient can only view their own record
        if (req.user.role === 'patient' && doc.patient._id.toString() !== req.user._id.toString()) {
            return sendError(res, 403, 'Not authorized to view this record');
        }

        const raw = doc.toObject();
        if (raw.narrative) raw.narrative = decrypt(raw.narrative);

        sendSuccess(res, 200, 'Chief complaint fetched successfully', raw);
    } catch (err) {
        next(err);
    }
};

/**
 * @desc    Doctor manually overrides / corrects AI-extracted fields
 * @route   PATCH /api/v1/chief-complaints/:ccId
 * @access  Private (Specialist Only)
 * @body    { narrative?, structured?: { duration?, severity?, onset_pattern?, onset_date?, triggers?,
 *             relieving_factors?, aggravating_factors?, associated_symptoms?, affected_domains?,
 *             functional_impairment? }, risk_markers?, previous_episodes?, override_notes? }
 */
exports.overrideChiefComplaint = async (req, res, next) => {
    try {
        const { narrative, structured, risk_markers, previous_episodes, override_notes } = req.body || {};

        const doc = await ChiefComplaint.findOne({ chiefComplaintId: Number(req.params.ccId) });
        if (!doc) return sendError(res, 404, 'Chief complaint not found');

        // Merge structured fields (partial update)
        if (structured) {
            Object.keys(structured).forEach(key => {
                if (key === 'affected_domains' && typeof structured.affected_domains === 'object') {
                    Object.assign(doc.structured.affected_domains, structured.affected_domains);
                } else {
                    doc.structured[key] = structured[key];
                }
            });
        }

        // Merge risk_markers
        if (risk_markers) {
            Object.assign(doc.risk_markers, risk_markers);
        }

        // Merge previous_episodes
        if (previous_episodes) {
            Object.assign(doc.previous_episodes, previous_episodes);
        }

        if (narrative) doc.narrative = encrypt(narrative);
        if (doc.status !== 'completed') doc.status = 'completed';

        // Mark override
        doc.doctor_override = {
            applied: true,
            overridden_at: new Date(),
            overridden_by: req.user?._id || null,
            override_notes: override_notes || null
        };

        await doc.save();

        const raw = doc.toObject();
        if (raw.narrative) raw.narrative = decrypt(raw.narrative);

        sendSuccess(res, 200, 'Chief complaint updated by doctor override', raw);
    } catch (err) {
        next(err);
    }
};

/**
 * @desc    Delete a chief complaint record
 * @route   DELETE /api/v1/chief-complaints/:ccId
 * @access  Private (Admin / Specialist)
 */
exports.deleteChiefComplaint = async (req, res, next) => {
    try {
        const doc = await ChiefComplaint.findOneAndDelete({ chiefComplaintId: Number(req.params.ccId) });
        if (!doc) return sendError(res, 404, 'Chief complaint not found');

        sendSuccess(res, 200, 'Chief complaint deleted successfully');
    } catch (err) {
        next(err);
    }
};
