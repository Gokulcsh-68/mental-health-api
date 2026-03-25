const AuditLog = require('../models/AuditLog');
const { sendSuccess, sendError } = require('../utils/responseHelper');

/**
 * @desc    Get all audit logs
 * @route   GET /api/v1/audit-logs
 * @access  Private (Super Admin)
 */
exports.getAuditLogs = async (req, res, next) => {
    try {
        const {
            page = 1,
            limit = 20,
            user,
            action,
            resource,
            startDate,
            endDate
        } = req.query;

        const query = {};

        if (user) query.user = user;
        if (action) query.action = action;
        if (resource) query.resource = resource;

        if (startDate || endDate) {
            query.timestamp = {};
            if (startDate) query.timestamp.$gte = new Date(startDate);
            if (endDate) query.timestamp.$lte = new Date(endDate);
        }

        const total = await AuditLog.countDocuments(query);
        const logs = await AuditLog.find(query)
            .populate('user', 'userId firstName lastName role')
            .sort({ timestamp: -1 })
            .skip((page - 1) * limit)
            .limit(parseInt(limit));

        sendSuccess(res, 200, 'Audit logs fetched successfully', {
            logs,
            pagination: {
                page: parseInt(page),
                limit: parseInt(limit),
                total,
                totalPages: Math.ceil(total / limit)
            }
        });
    } catch (err) {
        next(err);
    }
};

/**
 * @desc    Get audit log by ID
 * @route   GET /api/v1/audit-logs/:id
 * @access  Private (Super Admin)
 */
exports.getAuditLogById = async (req, res, next) => {
    try {
        const log = await AuditLog.findById(req.params.id)
            .populate('user', 'userId firstName lastName role');

        if (!log) {
            return sendError(res, 404, 'Audit log not found');
        }

        sendSuccess(res, 200, 'Audit log details fetched successfully', log);
    } catch (err) {
        next(err);
    }
};
