const HPI = require('../models/HPI');
const User = require('../models/User');
const openAIService = require('../services/OpenAIService');
const { autoColorCode } = require('../utils/colorCode');
const { resolvePatient } = require('../utils/patientHelper');
const logger = require('../config/logger');

/**
 * @desc    Create HPI with AI extraction
 * @route   POST /api/v1/hpis
 */
exports.createHPI = async (req, res) => {
    try {
        const { patient_id, consult_id, narrative } = req.body;

        if (!patient_id || !narrative) {
            return res.status(400).json({
                code: 400,
                message: 'patient_id and narrative are required',
                data: null
            });
        }

        // 1. Resolve patient + demographics
        const resolved = await resolvePatient(patient_id);
        if (!resolved) return res.status(404).json({ code: 404, message: `Patient ${patient_id} not found`, data: null });
        const { resolvedId: resolvedPatientId, age, gender } = resolved;

        // 2. AI Extraction with demographics
        const ai = await openAIService.extractClinicalInfo(narrative, { age, gender });

        // 3. Create Record
        const hpi = await HPI.create({
            patient: resolvedPatientId,
            consult_id: consult_id || null,
            narrative,
            status: 'completed',
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
                treatment_response: ai.hpi?.treatment_response || null
            },
            dsm5_mapping: ai.hpi?.dsm5_mapping || [],
            severity_index: ai.hpi?.severity_index || 0,
            recommendations: ai.hpi?.recommendations || [],
            color_code: ai.hpi?.color_code || autoColorCode(ai.hpi?.severity_index || 0),

        });

        // Trigger Red Flag Alerts if critical symptoms are detected
        const redFlags = [];
        if (doc.structured.suicidal_ideation && doc.structured.suicidal_ideation !== 'None') {
            redFlags.push(`Suicidal Ideation (${doc.structured.suicidal_ideation})`);
        }
        if (doc.severity_index > 80) {
            redFlags.push(`Critical Severity Index (${doc.severity_index}/100)`);
        }

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

        res.status(201).json({
            code: 201,
            message: 'HPI created with AI extraction',
            data: hpi
        });

    } catch (err) {
        logger.error('Create HPI Error: %s', err.message);
        res.status(500).json({
            code: 500,
            message: err.message,
            data: null
        });
    }
};

/**
 * @desc    Get HPIs with filters
 * @route   GET /api/v1/hpis
 */
exports.getHPIs = async (req, res) => {
    try {
        const { patient_id, consult_id, onset, course, startDate, endDate, color_code, severity_min, dsm5_keyword } = req.query;
        let query = {};

        // 1. Role-based filtering
        if (req.user.role === 'patient') {
            // Patient always sees only their own history
            query.patient = req.user._id;
        } else if (patient_id) {
            // Professionals can query for specific patients
            const resolved = await resolvePatient(patient_id);
            if (resolved) {
                query.patient = resolved.resolvedId;
            } else {
                return res.status(200).json({ code: 200, message: 'Patient not found', data: [] });
            }
        }

        logger.info('HPI Query constructed:', { query });

        if (consult_id) query.consult_id = consult_id;
        if (onset) query['structured.onset'] = onset;
        if (course) query['structured.course'] = course;
        
        // --- NEW COMPREHENSIVE FILTERS ---
        if (color_code) query.color_code = color_code;
        if (severity_min) query.severity_index = { $gte: Number(severity_min) };
        if (dsm5_keyword) query.dsm5_mapping = { $regex: new RegExp(dsm5_keyword, 'i') };

        // Date Range Filter
        if (startDate || endDate) {
            query.createdAt = {};
            if (startDate) query.createdAt.$gte = new Date(startDate);
            if (endDate) query.createdAt.$lte = new Date(endDate);
        }

        const hpis = await HPI.find(query)
            .populate('patient', 'name userId gender age')
            .sort({ createdAt: -1 });

        res.status(200).json({
            code: 200,
            message: 'HPIs retrieved successfully',
            data: hpis
        });

    } catch (err) {
        logger.error('Get HPIs Error: %s', err.message);
        res.status(500).json({
            code: 500,
            message: err.message,
            data: null
        });
    }
};
