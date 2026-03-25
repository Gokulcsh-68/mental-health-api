const timedNotificationService = require('../services/TimedNotificationService');
const { sendSuccess, sendError } = require('../utils/responseHelper');

/**
 * @desc    Get all scheduled background jobs
 * @route   GET /api/v1/scheduled-jobs
 * @access  Private (Super Admin)
 */
exports.getJobs = (req, res) => {
    const jobs = timedNotificationService.getJobStatus();
    sendSuccess(res, 200, 'Scheduled jobs fetched successfully', jobs);
};

/**
 * @desc    Start/Stop a background job
 * @route   PUT /api/v1/scheduled-jobs/:name/toggle
 * @access  Private (Super Admin)
 */
exports.toggleJob = (req, res) => {
    const { name } = req.params;
    const { action } = req.body; // 'start' or 'stop'

    if (action === 'stop') {
        const success = timedNotificationService.stopJob(name);
        if (!success) return sendError(res, 404, `Job ${name} not found`);
        return sendSuccess(res, 200, `Job ${name} stopped successfully`);
    }

    if (action === 'start') {
        const success = timedNotificationService.startJob(name);
        if (!success) return sendError(res, 404, `Job ${name} not found`);
        return sendSuccess(res, 200, `Job ${name} started successfully`);
    }

    sendError(res, 400, 'Invalid action. Use start or stop.');
};
