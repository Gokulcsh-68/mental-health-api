const User = require('../models/User');
const { sendSuccess, sendError } = require('../utils/responseHelper');

/**
 * @desc    Get all specialists with filters
 * @route   GET /api/v1/specialists
 * @access  Private
 */
exports.getSpecialists = async (req, res, next) => {
    try {
        const professionalRoles = ['psychiatrist', 'psychologist', 'nurse', 'social_worker', 'counselor'];
        const { role, search, specialization } = req.query;

        const query = { role: { $in: professionalRoles }, isActive: true };

        if (role) query.role = role;
        if (specialization) query.specialization = { $regex: specialization, $options: 'i' };

        if (search) {
            query.$or = [
                { firstName: { $regex: search, $options: 'i' } },
                { lastName: { $regex: search, $options: 'i' } },
                { specialization: { $regex: search, $options: 'i' } }
            ];
        }

        const specialists = await User.find(query).select('-password -fcmTokens -secret');

        sendSuccess(res, 200, 'Specialists fetched successfully', specialists);
    } catch (err) {
        next(err);
    }
};

/**
 * @desc    Get single specialist by ID
 * @route   GET /api/v1/specialists/:id
 * @access  Private
 */
exports.getSpecialistById = async (req, res, next) => {
    try {
        const specialist = await User.findOne({
            userId: parseInt(req.params.id),
            role: { $in: ['psychiatrist', 'psychologist', 'nurse', 'social_worker', 'counselor'] }
        }).select('-password -fcmTokens -secret');

        if (!specialist) {
            return sendError(res, 404, 'Specialist not found');
        }

        sendSuccess(res, 200, 'Specialist profile fetched successfully', specialist);
    } catch (err) {
        next(err);
    }
};

/**
 * @desc    Update specialist profile (Own profile)
 * @route   PATCH /api/v1/specialists/profile
 * @access  Private
 */
exports.updateSpecialistProfile = async (req, res, next) => {
    try {
        const professionalRoles = ['psychiatrist', 'psychologist', 'nurse', 'social_worker', 'counselor'];

        if (!professionalRoles.includes(req.user.role)) {
            return sendError(res, 403, 'Only specialists can update professional profile details');
        }

        const fieldsToUpdate = [
            'firstName', 'lastName', 'specialization', 'about',
            'experienceYears', 'qualifications', 'languages',
            'consultationFee', 'skills', 'profileImage'
        ];

        const updateData = {};
        fieldsToUpdate.forEach(field => {
            if (req.body[field] !== undefined) {
                updateData[field] = req.body[field];
            }
        });

        const user = await User.findByIdAndUpdate(req.user._id, updateData, {
            returnDocument: 'after',
            runValidators: true
        }).select('-password -fcmTokens -secret');

        sendSuccess(res, 200, 'Profile updated successfully', user);
    } catch (err) {
        next(err);
    }
};
