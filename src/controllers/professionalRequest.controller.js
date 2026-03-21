const ProfessionalRequest = require('../models/ProfessionalRequest');
const User = require('../models/User');
const { sendSuccess, sendError } = require('../utils/responseHelper');
const { notify } = require('../services/notificationService');

/**
 * @desc    Create a new professional request for a patient
 * @route   POST /api/v1/professional-requests
 * @access  Private (Staff)
 */
exports.createRequest = async (req, res, next) => {
    try {
        const { patientId, category, message } = req.body;

        if (!patientId || !category) {
            return sendError(res, 400, 'Please provide patientId and category');
        }

        const patient = await User.findOne({ userId: parseInt(patientId) });
        if (!patient) {
            return sendError(res, 404, `Patient with ID ${patientId} not found`);
        }

        if (patient.role !== 'patient') {
            return sendError(res, 400, 'Requests can only be sent to patients');
        }

        const request = await ProfessionalRequest.create({
            professional: req.user._id,
            patient: patient._id,
            patientId: patient.userId,
            category,
            message,
            status: 'pending'
        });

        // Send Notification to Patient
        notify({
            userId: patient._id,
            title: 'New Assessment Request 📝',
            message: `Dr. ${req.user.lastName} has requested you to complete a '${category}' assessment.`,
            type: 'alert',
            createdBy: req.user._id,
            data: {
                requestId: request.requestId.toString(),
                category: category,
                screen: 'Assessment'
            }
        });

        sendSuccess(res, 201, 'Professional request sent successfully', request);
    } catch (err) {
        next(err);
    }
};

/**
 * @desc    Get pending requests for the logged-in patient
 * @route   GET /api/v1/professional-requests/my-requests
 * @access  Private (Patient)
 */
exports.getMyRequests = async (req, res, next) => {
    try {
        const requests = await ProfessionalRequest.find({
            patient: req.user._id,
            status: 'pending'
        })
        .populate('professional', 'firstName lastName role')
        .sort({ createdAt: -1 });

        sendSuccess(res, 200, 'My pending requests fetched', requests);
    } catch (err) {
        next(err);
    }
};

/**
 * @desc    Get requests sent by the logged-in professional
 * @route   GET /api/v1/professional-requests/sent-requests
 * @access  Private (Staff)
 */
exports.getSentRequests = async (req, res, next) => {
    try {
        const requests = await ProfessionalRequest.find({
            professional: req.user._id
        })
        .populate('patient', 'firstName lastName userId')
        .sort({ createdAt: -1 });

        sendSuccess(res, 200, 'Sent requests fetched', requests);
    } catch (err) {
        next(err);
    }
};

/**
 * @desc    Get a single request by requestId
 * @route   GET /api/v1/professional-requests/:requestId
 * @access  Private
 */
exports.getRequestById = async (req, res, next) => {
    try {
        const request = await ProfessionalRequest.findOne({ requestId: req.params.requestId })
            .populate('professional', 'firstName lastName role')
            .populate('patient', 'firstName lastName userId');

        if (!request) {
            return sendError(res, 404, 'Request not found');
        }

        // Security check
        if (req.user.role === 'patient' && request.patient._id.toString() !== req.user._id.toString()) {
            return sendError(res, 403, 'Not authorized to access this request');
        }

        sendSuccess(res, 200, 'Request details fetched', request);
    } catch (err) {
        next(err);
    }
};
/**
 * @desc    Cancel a pending professional request
 * @route   PATCH /api/v1/professional-requests/:requestId/cancel
 * @access  Private (Staff/Owner)
 */
exports.cancelRequest = async (req, res, next) => {
    try {
        const request = await ProfessionalRequest.findOne({ requestId: req.params.requestId });

        if (!request) {
            return sendError(res, 404, 'Request not found');
        }

        // Security check: Only the professional who created it OR an admin can cancel it
        if (request.professional.toString() !== req.user._id.toString() && 
            !['admin', 'super_admin'].includes(req.user.role)) {
            return sendError(res, 403, 'Not authorized to cancel this request');
        }

        if (request.status !== 'pending') {
            return sendError(res, 400, `Cannot cancel a request that is already ${request.status}`);
        }

        request.status = 'cancelled';
        await request.save();

        sendSuccess(res, 200, 'Request cancelled successfully', request);
    } catch (err) {
        next(err);
    }
};

