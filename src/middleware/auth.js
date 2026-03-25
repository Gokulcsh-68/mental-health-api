const jwt = require('jsonwebtoken');
const config = require('../config/config');
const User = require('../models/User');
const { sendError } = require('../utils/responseHelper');

// Protect routes
exports.protect = async (req, res, next) => {
    let token;

    if (
        req.headers.authorization &&
        req.headers.authorization.startsWith('Bearer')
    ) {
        // Set token from Bearer token in header
        token = req.headers.authorization.split(' ')[1];
    }

    // Make sure token exists
    if (!token) {
        console.log('[Auth Middleware] No token found in headers');
        return sendError(res, 401, 'Not authorized to access this route');
    }

    try {
        // Verify token
        const decoded = jwt.verify(token, config.JWT_SECRET);
        console.log('[Auth Middleware] Token verified for userId:', decoded._id);

        req.user = await User.findById(decoded._id);

        if (!req.user) {
            return sendError(res, 401, 'User not found');
        }

        next();
    } catch (err) {
        console.error('[Auth Middleware] JWT Verification Failed:', err.message);
        return sendError(res, 401, 'Not authorized to access this route');
    }
};
// Authorize roles
exports.authorize = (...roles) => {
    return (req, res, next) => {
        if (!roles.includes(req.user.role)) {
            return sendError(
                res,
                403,
                `User role '${req.user.role}' is not authorized to access this route`
            );
        }
        next();
    };
};
