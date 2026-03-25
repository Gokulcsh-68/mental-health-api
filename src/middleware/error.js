const logger = require('../config/logger');
const config = require('../config/config');
const { sendError } = require('../utils/responseHelper');

const errorHandler = (err, req, res, next) => {
    // Log for server-side debugging
    if (config.NODE_ENV === 'development') {
        console.error(err.stack);
    }
    logger.error(err.stack);

    let statusCode = err.statusCode || 500;
    let message = err.message || 'Server Error';

    // Mongoose bad ObjectId
    if (err.name === 'CastError') {
        statusCode = 400;
        message = `Invalid ${err.path}: ${err.value}`;
    }

    // Mongoose duplicate key
    if (err.code === 11000) {
        statusCode = 409;
        const field = Object.keys(err.keyValue)[0];
        const value = err.keyValue[field];
        message = `The ${field} '${value}' is already in use. Please choose a different one`;
    }

    // Mongoose validation error
    if (err.name === 'ValidationError') {
        statusCode = 400;
        message = Object.values(err.errors).map(val => val.message).join(', ');
    }

    // Prepare response
    const errorResponse = {
        success: false,
        statusCode,
        message,
        data: null
    };

    // Include stack trace only in development
    if (config.NODE_ENV === 'development') {
        errorResponse.stack = err.stack;
    }

    res.status(statusCode).json(errorResponse);
};

module.exports = errorHandler;
