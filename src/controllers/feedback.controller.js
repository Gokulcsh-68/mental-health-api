const Feedback = require('../models/Feedback');
const { sendSuccess, sendError } = require('../utils/responseHelper');
const { notify } = require('../services/notificationService');

/**
 * @desc    Submit feedback/support ticket
 * @route   POST /api/v1/feedback
 * @access  Public (or Private)
 */
exports.submitFeedback = async (req, res, next) => {
    try {
        const { subject, message, category } = req.body;

        if (!subject || !message) {
            return sendError(res, 400, 'Please provide subject and message');
        }

        const feedback = await Feedback.create({
            userId: req.user ? req.user._id : null,
            subject,
            message,
            category: category || 'support'
        });

        sendSuccess(res, 201, 'Feedback submitted successfully', feedback);
    } catch (err) {
        next(err);
    }
};

/**
 * @desc    Get my feedback history
 * @route   GET /api/v1/feedback/my
 * @access  Private
 */
exports.getMyFeedback = async (req, res, next) => {
    try {
        const feedbacks = await Feedback.find({ userId: req.user._id })
            .sort({ createdAt: -1 });

        sendSuccess(res, 200, 'Your feedback history fetched successfully', feedbacks);
    } catch (err) {
        next(err);
    }
};

/**
 * @desc    Get latest app rating for user
 * @route   GET /api/v1/feedback/latest-rating
 * @access  Private
 */
exports.getLatestRating = async (req, res, next) => {
    try {
        const rating = await Feedback.findOne({ 
            userId: req.user._id,
            category: 'app_rating'
        }).sort({ createdAt: -1 });

        sendSuccess(res, 200, 'Latest rating fetched successfully', rating);
    } catch (err) {
        next(err);
    }
};

/**
 * @desc    Submit app rating
 * @route   POST /api/v1/feedback/rate
 * @access  Private
 */
exports.rateApp = async (req, res, next) => {
    try {
        const { rating, message } = req.body;

        if (!rating) {
            return sendError(res, 400, 'Please provide a rating between 1 and 5');
        }

        const feedback = await Feedback.create({
            userId: req.user._id,
            subject: 'App Store Rating',
            message: message || 'No comment provided',
            category: 'app_rating',
            rating
        });

        // Send thank you notification (fire-and-forget)
        notify({
            userId: req.user._id,
            title: 'Thank You for Your Feedback! ⭐',
            message: `We've received your ${rating}-star rating. Your feedback helps us improve the platform for everyone!`,
            type: 'alert'
        });

        sendSuccess(res, 201, 'Rating submitted successfully. Thank you!', feedback);
    } catch (err) {
        next(err);
    }
};

/**
 * @desc    Get all feedback tickets
 * @route   GET /api/v1/feedback
 * @access  Private (Super Admin)
 */
exports.getAllFeedback = async (req, res, next) => {
    try {
        const { status, category, page = 1, limit = 20 } = req.query;
        const query = {};

        if (status) query.status = status;
        if (category) query.category = category;

        const total = await Feedback.countDocuments(query);
        const feedbacks = await Feedback.find(query)
            .populate('userId', 'firstName lastName email role')
            .sort({ createdAt: -1 })
            .skip((page - 1) * limit)
            .limit(parseInt(limit));

        sendSuccess(res, 200, 'Feedback tickets fetched successfully', {
            feedbacks,
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
 * @desc    Update feedback status/notes
 * @route   PUT /api/v1/feedback/:id
 * @access  Private (Super Admin)
 */
exports.updateFeedback = async (req, res, next) => {
    try {
        const { status, adminNotes } = req.body;
        const updateData = {};

        if (status) {
            updateData.status = status;
            if (status === 'resolved') updateData.resolvedAt = Date.now();
        }
        if (adminNotes !== undefined) updateData.adminNotes = adminNotes;

        const feedback = await Feedback.findByIdAndUpdate(
            req.params.id,
            updateData,
            { returnDocument: 'after', runValidators: true }
        );

        if (!feedback) {
            return sendError(res, 404, 'Feedback ticket not found');
        }

        sendSuccess(res, 200, 'Feedback ticket updated successfully', feedback);
    } catch (err) {
        next(err);
    }
};
