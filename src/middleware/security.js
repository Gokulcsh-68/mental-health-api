const helmet = require('helmet');
const cors = require('cors');
const hpp = require('hpp');
const xss = require('xss-clean');
const rateLimit = require('express-rate-limit');
const config = require('../config/config');

const setupSecurity = (app) => {
    // 1. Set security headers (XSS protection, MIME sniffing, etc.)
    app.use(helmet());

    // 2. Prevent NoSQL Injection & XSS (Express 5 compatible custom sanitizer)
    app.use((req, res, next) => {
        const sanitizeValue = (val) => {
            if (typeof val === 'string') {
                // Basic XSS: escape < and >
                return val.replace(/</g, '&lt;').replace(/>/g, '&gt;');
            }
            if (val && typeof val === 'object') {
                Object.keys(val).forEach(key => {
                    // NoSQL Injection: remove keys starting with $
                    if (key.startsWith('$')) {
                        console.warn(`[Security] NoSQL operator ${key} removed`);
                        delete val[key];
                    } else {
                        val[key] = sanitizeValue(val[key]);
                    }
                });
            }
            return val;
        };

        if (req.body) req.body = sanitizeValue(req.body);
        if (req.params) req.params = sanitizeValue(req.params);
        
        // Handling req.query carefully for Express 5
        if (req.query) {
            try {
                // We don't reassign req.query itself, just sanitize its properties
                Object.keys(req.query).forEach(key => {
                    if (key.startsWith('$')) {
                        delete req.query[key];
                    } else {
                        req.query[key] = sanitizeValue(req.query[key]);
                    }
                });
            } catch (e) {
                // If the query object is completely immutable, we log it
                console.error('[Security] Failed to sanitize req.query:', e.message);
            }
        }
        next();
    });

    // 3. Prevent HTTP Parameter Pollution
    app.use(hpp());

    // 4. Global Rate Limiting
    const globalLimiter = rateLimit({
        windowMs: 10 * 60 * 1000, // 10 minutes
        max: 1000,
        message: {
            success: false,
            statusCode: 429,
            message: 'Too many requests, please try again after 10 minutes',
            data: null
        }
    });
    app.use(globalLimiter);

    // 6. Stricter Rate Limiting for Auth Routes (Login, Register)
    const authLimiter = rateLimit({
        windowMs: 15 * 60 * 1000, // 15 minutes
        max: 50, // limit each IP to 50 requests per windowMs
        message: {
            success: false,
            statusCode: 429,
            message: 'Too many authentication attempts, please try again after 15 minutes',
            data: null
        }
    });
    app.use('/api/v1/auth', authLimiter);
    app.use('/auth', authLimiter);

    // 7. Enable CORS
    const corsOptions = {
        origin: config.CORS_ORIGIN === '*' ? '*' : config.CORS_ORIGIN.split(','),
        methods: ['GET', 'POST', 'PUT', 'DELETE', 'PATCH'],
        allowedHeaders: ['Content-Type', 'Authorization', 'x-api-key'],
        credentials: true
    };
    app.use(cors(corsOptions));
};

module.exports = setupSecurity;
