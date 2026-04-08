const crypto = require('crypto');
const User = require('../models/User');
const RefreshToken = require('../models/RefreshToken');
const jwt = require('jsonwebtoken');
const config = require('../config/config');
const { sendSuccess, sendError } = require('../utils/responseHelper');
const { notify } = require('../services/notificationService');
const openAIService = require('../services/OpenAIService');
const logger = require('../config/logger');

// @desc    Register user (super_admin, admin, hospital only)
// @route   POST /api/v1/auth/register
// @access  Public
const ALLOWED_REGISTER_ROLES = ['super_admin', 'admin', 'hospital', 'patient', 'psychiatrist', 'psychologist', 'nurse', 'social_worker', 'counselor'];

exports.register = async (req, res, next) => {
    try {
        const {
            firstName, lastName, username, email, password, phone, role,
            dateOfBirth, gender, address, emergencyContact,
            isdCode, mobile, profileImage, bloodGroup, timezoneId, countryIso,
            is2fa, secret, isActive, communicationPreferences, fcmTokens,
            // Professional fields
            specialization, about, experienceYears, qualifications,
            languages, consultationFee, skills
        } = req.body;

        if (role && !ALLOWED_REGISTER_ROLES.includes(role)) {
            return sendError(res, 403, `Role '${role}' cannot self-register. Please contact an administrator`);
        }

        const user = await User.create({
            firstName, lastName, username, email, password, phone, role,
            dateOfBirth, gender, address, emergencyContact,
            isdCode, mobile, profileImage, bloodGroup, timezoneId, countryIso,
            is2fa, secret, isActive, communicationPreferences, fcmTokens,
            specialization, about, experienceYears, qualifications,
            languages, consultationFee, skills
        });

        // Send AI-generated welcome notification (background)
        (async () => {
            try {
                const aiGreeting = await openAIService.generateWelcomeMessage(user, true); // true = isNewUser
                notify({
                    userId: user._id,
                    title: aiGreeting?.title || 'Welcome Home! 🏠',
                    message: aiGreeting?.message || `Welcome to MindBalance, ${user.firstName}! We are excited to support you on your journey.`,
                    type: 'welcome',
                    imageUrl: openAIService.getMentalHealthImages()[aiGreeting?.imageIndex || 2]
                });
            } catch (err) {
                logger.error('Background New User Welcome Error: %s', err.message);
            }
        })();

        await sendTokenResponse(user, 201, 'User registered successfully', res);
    } catch (err) {
        next(err);
    }
};

// @desc    Login user
// @route   POST /api/v1/auth/login
// @access  Public
exports.login = async (req, res, next) => {
    try {
        const { username, password, role, fcmToken } = req.body;

        if (!username || !password || !role) {
            return sendError(res, 400, 'Please provide username, password and role');
        }

        const user = await User.findOne({ username, role }).select('+password');

        if (!user) {
            return sendError(res, 401, 'Invalid credentials');
        }

        // Check if account is locked
        if (user.lockUntil && user.lockUntil > Date.now()) {
            const minutesLeft = Math.ceil((user.lockUntil - Date.now()) / (60 * 1000));
            return sendError(res, 403, `Account is temporarily locked due to multiple failed attempts. Please try again in ${minutesLeft} minutes`);
        }

        const isMatch = await user.matchPassword(password);

        if (!isMatch) {
            // Increment login attempts
            user.loginAttempts += 1;
            
            if (user.loginAttempts >= 5) {
                user.lockUntil = Date.now() + 60 * 60 * 1000; // Lock for 1 hour
                await user.save({ validateBeforeSave: false });
                return sendError(res, 403, 'Account is temporarily locked due to multiple failed attempts. Please try again in 1 hour');
            }

            await user.save({ validateBeforeSave: false });
            return sendError(res, 401, 'Invalid credentials');
        }

        // Reset login attempts on successful login
        if (user.loginAttempts > 0 || user.lockUntil) {
            user.loginAttempts = 0;
            user.lockUntil = undefined;
            // No await here, we'll save below if needed
        }

        // ─────────────────────────────────────────────
        // Handle FCM Token from login body (prevents race conditions for welcome push)
        // ─────────────────────────────────────────────
        if (fcmToken) {
            if (!user.fcmTokens) user.fcmTokens = [];
            if (!user.fcmTokens.includes(fcmToken)) {
                user.fcmTokens.push(fcmToken);
            }
        }
        
        // Always save if we changed attempts, tokens, or lock status
        await user.save({ validateBeforeSave: false });


        // Send AI-generated welcome notification (background)
        (async () => {
            try {
                const aiGreeting = await openAIService.generateWelcomeMessage(user, false);
                notify({
                    userId: user._id,
                    title: aiGreeting?.title || 'Welcome Back! 👋',
                    message: aiGreeting?.message || `Hello ${user.firstName}, welcome back to Mental Health Platform.`,
                    type: 'welcome',
                    imageUrl: openAIService.getMentalHealthImages()[aiGreeting?.imageIndex || 6]
                });
            } catch (err) {
                logger.error('Background Welcome Notification Error: %s', err.message);
            }
        })();

        await sendTokenResponse(user, 200, 'Login successful', res);
    } catch (err) {
        next(err);
    }
};

// @desc    Get current logged in user
// @route   GET /api/v1/auth/me
// @access  Private
exports.getMe = async (req, res, next) => {
    try {
        const user = await User.findById(req.user._id);
        sendSuccess(res, 200, 'User details fetched successfully', user);
    } catch (err) {
        next(err);
    }
};

// @desc    Forgot password (generate reset token)
// @route   POST /api/v1/auth/forgot-password
// @access  Public
exports.forgotPassword = async (req, res, next) => {
    try {
        const { email } = req.body;

        if (!email) {
            return sendError(res, 400, 'Please provide an email address');
        }

        const user = await User.findOne({ email });

        if (!user) {
            return sendError(res, 404, 'No account found with that email address');
        }

        const resetToken = user.getResetPasswordToken();
        await user.save({ validateBeforeSave: false });

        // Send reset OTP via notification (fire-and-forget)
        notify({
            userId: user._id,
            title: 'Password Reset OTP',
            message: `You requested a password reset. Your OTP is: ${resetToken}. This code expires in 10 minutes.`,
            type: 'alert'
        });

        sendSuccess(res, 200, 'Password reset OTP generated and sent', {
            resetToken,
            expiresIn: '10 minutes'
        });
    } catch (err) {
        next(err);
    }
};

// @desc    Reset password (using token)
// @route   PUT /api/v1/auth/reset-password/:token
// @access  Public
exports.resetPassword = async (req, res, next) => {
    try {
        const { password } = req.body;

        if (!password) {
            return sendError(res, 400, 'Please provide a new password');
        }

        const resetPasswordToken = crypto
            .createHash('sha256')
            .update(req.params.token)
            .digest('hex');

        const user = await User.findOne({
            resetPasswordToken,
            resetPasswordExpire: { $gt: Date.now() }
        });

        if (!user) {
            return sendError(res, 400, 'Invalid or expired OTP');
        }

        user.password = password;
        user.resetPasswordToken = undefined;
        user.resetPasswordExpire = undefined;
        await user.save();

        // Notify user about password change
        notify({
            userId: user._id,
            title: 'Password Changed',
            message: 'Your password has been successfully reset. If you did not make this change, please contact support immediately.',
            type: 'alert'
        });

        await sendTokenResponse(user, 200, 'Password reset successful', res);
    } catch (err) {
        next(err);
    }
};

// @desc    Change password (logged-in user, no token needed)
// @route   PUT /api/v1/auth/change-password
// @access  Private
exports.changePassword = async (req, res, next) => {
    try {
        const { currentPassword, newPassword, confirmPassword } = req.body;

        if (!currentPassword || !newPassword || !confirmPassword) {
            return sendError(res, 400, 'Please provide current password, new password and confirm password');
        }

        if (newPassword !== confirmPassword) {
            return sendError(res, 400, 'New password and confirm password do not match');
        }

        const user = await User.findById(req.user._id).select('+password');

        const isMatch = await user.matchPassword(currentPassword);

        if (!isMatch) {
            return sendError(res, 401, 'Current password is incorrect');
        }

        user.password = newPassword;
        await user.save();

        // Notify user about password change
        notify({
            userId: user._id,
            title: 'Password Changed',
            message: 'Your password was changed successfully. If you did not make this change, please reset your password immediately.',
            type: 'alert'
        });

        await sendTokenResponse(user, 200, 'Password changed successfully', res);
    } catch (err) {
        next(err);
    }
};

// @desc    Refresh token
// @route   POST /api/v1/auth/refresh-token
// @access  Public
exports.refreshToken = async (req, res, next) => {
    try {
        const { refreshToken: requestToken } = req.body;

        if (!requestToken) {
            return sendError(res, 400, 'Refresh token is required');
        }

        const refreshToken = await RefreshToken.findOne({ token: requestToken });

        if (!refreshToken) {
            return sendError(res, 403, 'Invalid refresh token');
        }

        if (refreshToken.isRevoked) {
            return sendError(res, 403, 'Refresh token has been revoked');
        }

        if (refreshToken.isExpired()) {
            await RefreshToken.findByIdAndDelete(refreshToken._id);
            return sendError(res, 403, 'Refresh token has expired. Please login again');
        }

        const user = await User.findById(refreshToken.user);
        if (!user) {
            return sendError(res, 404, 'User not found');
        }

        // Generate new access token
        const newAccessToken = user.getSignedJwtToken();

        // Optional: Rotate refresh token
        const newRefreshTokenStr = crypto.randomBytes(40).toString('hex');
        const expiryDate = new Date();
        const days = parseInt(config.REFRESH_TOKEN_EXPIRE) || 7;
        expiryDate.setDate(expiryDate.getDate() + days);

        refreshToken.token = newRefreshTokenStr;
        refreshToken.expiryDate = expiryDate;
        await refreshToken.save();

        sendSuccess(res, 200, 'Token refreshed successfully', {
            token: newAccessToken,
            refreshToken: newRefreshTokenStr
        });
    } catch (err) {
        next(err);
    }
};

// @desc    Logout / Revoke token
// @route   POST /api/v1/auth/logout
// @access  Public
exports.logout = async (req, res, next) => {
    try {
        const { refreshToken: requestToken } = req.body;

        if (!requestToken) {
            return sendError(res, 400, 'Refresh token is required');
        }

        await RefreshToken.findOneAndDelete({ token: requestToken });

        sendSuccess(res, 200, 'Logged out successfully');
    } catch (err) {
        next(err);
    }
};

// Get token from model and send response
const sendTokenResponse = async (user, statusCode, message, res) => {
    const token = user.getSignedJwtToken();

    // Create refresh token
    const refreshTokenStr = crypto.randomBytes(40).toString('hex');
    const expiryDate = new Date();
    // Use REFRESH_TOKEN_EXPIRE from config if possible, otherwise default to 7 days
    const days = parseInt(config.REFRESH_TOKEN_EXPIRE) || 7;
    expiryDate.setDate(expiryDate.getDate() + days);

    await RefreshToken.create({
        token: refreshTokenStr,
        user: user._id,
        expiryDate
    });

    sendSuccess(res, statusCode, message, {
        token,
        refreshToken: refreshTokenStr,
        user: {
            _id: user._id,
            userId: user.userId,
            firstName: user.firstName,
            lastName: user.lastName,
            email: user.email,
            role: user.role
        }
    });
};
