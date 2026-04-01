const StepLog = require('../models/StepLog');
const { sendSuccess, sendError } = require('../utils/responseHelper');

/**
 * @desc    Log physical steps for a patient
 * @route   POST /api/v1/steps
 * @access  Private (Patient/Professional/Hospital)
 */
exports.logSteps = async (req, res, next) => {
    try {
        const { count, date, source, notes, patientId } = req.body;

        if (count === undefined) {
            return sendError(res, 400, 'Step count is required');
        }

        let targetPatientId = req.user._id;

        // If a pro/hospital is logging for a patient
        if (['psychiatrist', 'psychologist', 'nurse', 'social_worker', 'counselor', 'hospital', 'admin', 'super_admin'].includes(req.user.role)) {
            if (patientId) {
                const User = require('../models/User');
                const patient = await User.findOne({ userId: parseInt(patientId) });
                if (!patient) return sendError(res, 404, 'Patient not found');
                targetPatientId = patient._id;
            }
        }

        // Normalize date to midnight
        const logDate = date ? new Date(date) : new Date();
        logDate.setHours(0, 0, 0, 0);

        const stepLog = await StepLog.findOneAndUpdate(
            { patient: targetPatientId, date: logDate },
            {
                count,
                source: source || 'Manual',
                notes,
                createdBy: req.user._id
            },
            { upsert: true, returnDocument: 'after', runValidators: true }
        );

        sendSuccess(res, 200, 'Steps logged successfully', stepLog);
    } catch (err) {
        next(err);
    }
};

/**
 * @desc    Get step history for a patient
 * @route   GET /api/v1/steps
 * @access  Private (Patient/Professional/Hospital)
 */
exports.getStepHistory = async (req, res, next) => {
    try {
        const { patientId, startDate, endDate } = req.query;
        let targetPatientId = req.user._id;

        // Determine target patient
        if (['psychiatrist', 'psychologist', 'nurse', 'social_worker', 'counselor', 'hospital', 'admin', 'super_admin'].includes(req.user.role)) {
            if (patientId) {
                const User = require('../models/User');
                const patient = await User.findOne({ userId: parseInt(patientId) });
                if (!patient) return sendError(res, 404, 'Patient not found');
                targetPatientId = patient._id;
            }
        }

        const query = { patient: targetPatientId };

        if (startDate || endDate) {
            query.date = {};
            if (startDate) query.date.$gte = new Date(startDate);
            if (endDate) query.date.$lte = new Date(endDate);
        }

        const history = await StepLog.find(query).sort({ date: -1 });

        sendSuccess(res, 200, 'Step history fetched successfully', history);
    } catch (err) {
        next(err);
    }
};

/**
 * @desc    Get step stats (total, average)
 * @route   GET /api/v1/steps/stats
 * @access  Private (Patient/Professional/Hospital)
 */
exports.getStepStats = async (req, res, next) => {
    try {
        const { patientId, days = 7 } = req.query;
        let targetPatientId = req.user._id;

        if (['psychiatrist', 'psychologist', 'nurse', 'social_worker', 'counselor', 'hospital', 'admin', 'super_admin'].includes(req.user.role)) {
            if (patientId) {
                const User = require('../models/User');
                const patient = await User.findOne({ userId: parseInt(patientId) });
                if (!patient) return sendError(res, 404, 'Patient not found');
                targetPatientId = patient._id;
            }
        }

        const startDate = new Date();
        startDate.setDate(startDate.getDate() - parseInt(days));
        startDate.setHours(0, 0, 0, 0);

        const stats = await StepLog.aggregate([
            { $match: { patient: targetPatientId, date: { $gte: startDate } } },
            {
                $group: {
                    _id: null,
                    totalSteps: { $sum: '$count' },
                    averageSteps: { $avg: '$count' },
                    maxSteps: { $max: '$count' },
                    minSteps: { $min: '$count' },
                    daysTracked: { $sum: 1 }
                }
            }
        ]);

        sendSuccess(res, 200, 'Step statistics fetched successfully', stats[0] || {
            totalSteps: 0,
            averageSteps: 0,
            maxSteps: 0,
            minSteps: 0,
            daysTracked: 0
        });
    } catch (err) {
        next(err);
    }
};
