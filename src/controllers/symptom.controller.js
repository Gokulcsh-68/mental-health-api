const Symptom = require('../models/Symptom');
const User = require('../models/User');
const { sendSuccess, sendError } = require('../utils/responseHelper');
const logger = require('../config/logger');

/**
 * @desc    Save new symptom scores
 * @route   POST /api/v1/symptoms
 * @access  Private
 */
exports.saveSymptoms = async (req, res, next) => {
    try {
        const { patientId, scores, consult_id, notes } = req.body;

        if (!patientId) {
            return sendError(res, 400, 'patientId is required');
        }

        if (!scores || typeof scores !== 'object') {
            return sendError(res, 400, 'scores object is required');
        }

        // Resolve patient
        let patient;
        if (/^[0-9a-fA-F]{24}$/.test(String(patientId))) {
            patient = await User.findById(patientId);
        } else {
            patient = await User.findOne({ userId: patientId });
        }

        if (!patient) {
            return sendError(res, 404, `Patient ${patientId} not found`);
        }

        // Simple color code logic based on anxiety or mood extremes
        // This can be refined based on clinical requirements
        let color_code = '#4CAF50'; // Default Green (Normal)
        if (scores.anxiety > 8 || scores.mood < 3 || scores.energy > 8) {
            color_code = '#E53935'; // Red (High Risk/Severity)
        } else if (scores.anxiety > 5 || scores.mood < 5) {
            color_code = '#FB8C00'; // Orange (Moderate)
        }

        const symptom = await Symptom.create({
            patient: patient._id,
            consult_id: consult_id || null,
            scores,
            notes: notes || null,
            color_code
        });

        sendSuccess(res, 201, 'Symptom scores saved successfully', symptom);
    } catch (err) {
        logger.error('Save Symptoms Error: %s', err.message);
        next(err);
    }
};

/**
 * @desc    Get symptom history for a patient
 * @route   GET /api/v1/symptoms/patient/:patientId
 * @access  Private
 */
exports.getSymptomsByPatient = async (req, res, next) => {
    try {
        const { patientId } = req.params;
        const { limit = 10, page = 1 } = req.query;

        // Resolve patient
        let patient;
        if (/^[0-9a-fA-F]{24}$/.test(String(patientId))) {
            patient = await User.findById(patientId);
        } else {
            patient = await User.findOne({ userId: patientId });
        }

        if (!patient) {
            return sendError(res, 404, `Patient ${patientId} not found`);
        }

        // Security check: Patients can only see their own symptom records
        if (req.user.role === 'patient' && patient._id.toString() !== req.user._id.toString()) {
            return sendError(res, 403, 'Not authorized to view this record');
        }

        const skip = (parseInt(page) - 1) * parseInt(limit);

        const symptoms = await Symptom.find({ patient: patient._id })
            .sort({ createdAt: -1 })
            .skip(skip)
            .limit(parseInt(limit));

        const total = await Symptom.countDocuments({ patient: patient._id });

        sendSuccess(res, 200, 'Symptom history retrieved successfully', {
            symptoms,
            pagination: {
                total,
                page: parseInt(page),
                limit: parseInt(limit),
                pages: Math.ceil(total / limit)
            }
        });
    } catch (err) {
        logger.error('Get Symptoms Error: %s', err.message);
        next(err);
    }
};

/**
 * @desc    Get specific symptom record
 * @route   GET /api/v1/symptoms/:id
 * @access  Private
 */
exports.getSymptomById = async (req, res, next) => {
    try {
        const symptom = await Symptom.findById(req.params.id).populate('patient', 'firstName lastName userId');

        if (!symptom) {
            return sendError(res, 404, 'Symptom record not found');
        }

        // Security check: Patients can only see their own symptom records
        if (req.user.role === 'patient' && symptom.patient._id.toString() !== req.user._id.toString()) {
            return sendError(res, 403, 'Not authorized to view this record');
        }

        sendSuccess(res, 200, 'Symptom record retrieved successfully', symptom);
    } catch (err) {
        logger.error('Get Symptom By Id Error: %s', err.message);
        next(err);
    }
};
