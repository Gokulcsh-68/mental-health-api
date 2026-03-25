const config = require('../config/config');
const { sendError } = require('../utils/responseHelper');

/**
 * Validate x-api-key header on all incoming requests.
 * Rejects requests without a valid API key.
 */
const validateApiKey = (req, res, next) => {
    const apiKey = req.headers['x-api-key'];

    if (!apiKey) {
        console.log('[API Key Middleware] Missing API Key');
        return sendError(res, 401, 'API key is required. Provide x-api-key header');
    }

    if (apiKey !== config.API_KEY) {
        console.log('[API Key Middleware] Invalid API Key:', apiKey);
        return sendError(res, 401, 'Invalid API key');
    }

    console.log('[API Key Middleware] Success');

    next();
};

module.exports = validateApiKey;
