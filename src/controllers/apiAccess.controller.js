const ApiAccess = require('../models/ApiAccess');
const { sendSuccess, sendError } = require('../utils/responseHelper');

// @desc    Get all API access rules
// @route   GET /api/v1/api-access
// @access  Private (Admin)
exports.getApiAccessRules = async (req, res, next) => {
    try {
        const rules = await ApiAccess.find().sort({ role_code: 1, resource: 1 });
        sendSuccess(res, 200, 'API access rules fetched successfully', rules);
    } catch (err) {
        next(err);
    }
};

// @desc    Create a new API access rule
// @route   POST /api/v1/api-access
// @access  Private (Admin)
exports.createApiAccessRule = async (req, res, next) => {
    try {
        const { role_code, resource, permissions } = req.body;

        if (!role_code || !resource) {
            return sendError(res, 400, 'Please provide role_code and resource');
        }

        const rule = await ApiAccess.create({ role_code, resource, permissions });
        sendSuccess(res, 201, 'API access rule created successfully', rule);
    } catch (err) {
        next(err);
    }
};

// @desc    Get API access rule by ID
// @route   GET /api/v1/api-access/:id
// @access  Private (Admin)
exports.getApiAccessRule = async (req, res, next) => {
    try {
        const rule = await ApiAccess.findById(req.params.id);

        if (!rule) {
            return sendError(res, 404, 'API access rule not found');
        }

        sendSuccess(res, 200, 'API access rule details fetched successfully', rule);
    } catch (err) {
        next(err);
    }
};

// @desc    Update API access rule
// @route   PUT /api/v1/api-access/:id
// @access  Private (Admin)
exports.updateApiAccessRule = async (req, res, next) => {
    try {
        const rule = await ApiAccess.findByIdAndUpdate(req.params.id, req.body, {
            new: true,
            runValidators: true
        });

        if (!rule) {
            return sendError(res, 404, 'API access rule not found');
        }

        sendSuccess(res, 200, 'API access rule updated successfully', rule);
    } catch (err) {
        next(err);
    }
};

// @desc    Delete API access rule
// @route   DELETE /api/v1/api-access/:id
// @access  Private (Admin)
exports.deleteApiAccessRule = async (req, res, next) => {
    try {
        const rule = await ApiAccess.findByIdAndDelete(req.params.id);

        if (!rule) {
            return sendError(res, 404, 'API access rule not found');
        }

        sendSuccess(res, 200, 'API access rule deleted successfully');
    } catch (err) {
        next(err);
    }
};
