const AuditLog = require('../models/AuditLog');
const logger = require('../config/logger');

/**
 * Middleware to log clinical actions for audit trail
 * @param {string} action - Action type (READ, WRITE, etc.)
 * @param {string} resource - Resource name
 */
const auditLog = (action, resource) => {
    return async (req, res, next) => {
        const originalSend = res.send;

        res.send = function (data) {
            res.send = originalSend;

            // Perform logging after response is sent (async)
            process.nextTick(async () => {
                try {
                    const auditEntry = {
                        user: req.user ? req.user._id : null,
                        action: action,
                        resource: resource,
                        resourceId: req.params?.id || req.body?.consult_id || req.query?.consult_id || null,
                        ipAddress: req.ip || req.connection?.remoteAddress,
                        userAgent: req.headers['user-agent'],
                        details: {
                            method: req.method,
                            url: req.originalUrl,
                            statusCode: res.statusCode
                        }
                    };

                    if (req.user) {
                        await AuditLog.create(auditEntry);
                    }
                } catch (err) {
                    logger.error('[AuditMiddleware] Failed to create audit log: %s', err.message);
                }
            });

            return res.send(data);
        };

        next();
    };
};

module.exports = auditLog;
