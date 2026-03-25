/**
 * Standard API Response Helper
 * Use these functions across all controllers and middleware
 * to maintain a consistent response format.
 */

/**
 * Send a success response
 * @param {Object} res - Express response object
 * @param {Number} statusCode - HTTP status code (200, 201, etc.)
 * @param {String} message - Human-readable message
 * @param {Object|Array|null} data - Response data
 */
const sendSuccess = (res, statusCode, message, data = null) => {
    const response = {
        code: statusCode,
        message,
        data
    };

    return res.status(statusCode).json(response);
};

/**
 * Send a paginated success response
 * @param {Object} res - Express response object
 * @param {Number} statusCode - HTTP status code
 * @param {String} message - Human-readable message
 * @param {Array} data - Array of results
 * @param {Object} pagination - { page, limit, total, totalPages }
 */
const sendPaginated = (res, statusCode, message, data, pagination) => {
    const response = {
        code: statusCode,
        message,
        data,
        pagination
    };

    return res.status(statusCode).json(response);
};

/**
 * Send an error response
 * @param {Object} res - Express response object
 * @param {Number} statusCode - HTTP status code (400, 401, 404, 500, etc.)
 * @param {String} message - Human-readable error message
 */
const sendError = (res, statusCode, message) => {
    const response = {
        code: statusCode,
        message,
        data: null
    };

    return res.status(statusCode).json(response);
};

module.exports = { sendSuccess, sendPaginated, sendError };
