const timedNotificationService = require('../services/TimedNotificationService');
const ScheduledJob = require('../models/ScheduledJob');
const { sendSuccess, sendError } = require('../utils/responseHelper');

/**
 * @desc    Get all scheduled background jobs (Fixed & Dynamic)
 * @route   GET /api/v1/scheduled-jobs
 * @access  Private (Super Admin)
 */
exports.getJobs = async (req, res, next) => {
    try {
        const jobs = timedNotificationService.getJobStatus();
        sendSuccess(res, 200, 'Scheduled jobs fetched successfully', jobs);
    } catch (err) {
        next(err);
    }
};

/**
 * @desc    Create a new custom automation job
 * @route   POST /api/v1/scheduled-jobs
 * @access  Private (Super Admin)
 */
exports.createJob = async (req, res, next) => {
    try {
        const { name, description, cron, actionType, payload } = req.body;

        if (!name || !cron || !actionType) {
            return sendError(res, 400, 'Please provide name, cron expression, and actionType');
        }

        const job = await ScheduledJob.create({
            name,
            description,
            cron,
            actionType,
            payload,
            createdBy: req.user._id
        });

        // Register in the active service memory
        timedNotificationService.registerCustomJob(job);

        sendSuccess(res, 201, 'Automation job created and scheduled successfully', job);
    } catch (err) {
        if (err.code === 11000) {
            return sendError(res, 400, 'A job with this name already exists');
        }
        next(err);
    }
};

/**
 * @desc    Start/Stop a background job
 * @route   PUT /api/v1/scheduled-jobs/:name/toggle
 * @access  Private (Super Admin)
 */
exports.toggleJob = async (req, res, next) => {
    try {
        const { name } = req.params;
        const { action } = req.body; // 'start' or 'stop'

        // 1. Update Database if it exists
        const dbJob = await ScheduledJob.findOne({ name });
        if (dbJob) {
            dbJob.isActive = (action === 'start');
            await dbJob.save();
        }

        // 2. Update Memory/Scheduler
        if (action === 'stop') {
            const success = timedNotificationService.stopJob(name);
            if (!success) return sendError(res, 404, `Job ${name} not found in scheduler`);
            return sendSuccess(res, 200, `Job ${name} stopped successfully`);
        }

        if (action === 'start') {
            // If it's a DB job, re-register to ensure fresh payload/cron
            if (dbJob) {
                timedNotificationService.registerCustomJob(dbJob);
            } else {
                const success = timedNotificationService.startJob(name);
                if (!success) return sendError(res, 404, `Job ${name} not found in scheduler`);
            }
            return sendSuccess(res, 200, `Job ${name} started successfully`);
        }

        sendError(res, 400, 'Invalid action. Use start or stop.');
    } catch (err) {
        next(err);
    }
};

/**
 * @desc    Delete a custom automation job
 * @route   DELETE /api/v1/scheduled-jobs/:id
 * @access  Private (Super Admin)
 */
exports.deleteJob = async (req, res, next) => {
    try {
        const job = await ScheduledJob.findById(req.params.id);

        if (!job) {
            return sendError(res, 404, 'Automation job not found');
        }

        // Stop in memory
        timedNotificationService.stopJob(job.name);

        // Remove from DB
        await job.deleteOne();

        sendSuccess(res, 200, 'Automation job deleted successfully');
    } catch (err) {
        next(err);
    }
};
