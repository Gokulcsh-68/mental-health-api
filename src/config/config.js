const dotenv = require('dotenv');

// Load env vars
dotenv.config();

module.exports = {
    PORT: process.env.PORT || 5000,
    NODE_ENV: process.env.NODE_ENV || 'development',
    MONGO_URI: process.env.MONGO_URI,
    JWT_SECRET: process.env.JWT_SECRET,
    JWT_EXPIRE: process.env.JWT_EXPIRE,
    JWT_COOKIE_EXPIRE: process.env.JWT_COOKIE_EXPIRE,
    REFRESH_TOKEN_SECRET: process.env.REFRESH_TOKEN_SECRET || 'refresh_secret_123',
    REFRESH_TOKEN_EXPIRE: process.env.REFRESH_TOKEN_EXPIRE || '7d',
    API_KEY: process.env.API_KEY,
    CORS_ORIGIN: process.env.CORS_ORIGIN || '*',
    OPENAI_MODEL: process.env.OPENAI_API_MODEL || 'gpt-3.5-turbo',
    CURESELECT_CATEGORY_ID: process.env.CURESELECT_CATEGORY_ID || 2,


    // Email (SMTP) — leave empty for log mode
    SMTP_HOST: process.env.SMTP_HOST,
    SMTP_PORT: process.env.SMTP_PORT || 587,
    SMTP_USER: process.env.SMTP_USER,
    SMTP_PASS: process.env.SMTP_PASS,
    SMTP_FROM: process.env.SMTP_FROM || '"Mental Health Platform" <noreply@mentalhealth.com>',

    // SMS (Twilio) — leave empty for log mode
    TWILIO_SID: process.env.TWILIO_SID,
    TWILIO_AUTH_TOKEN: process.env.TWILIO_AUTH_TOKEN,
    TWILIO_PHONE: process.env.TWILIO_PHONE,

    // Push (FCM) — leave empty for log mode
    FCM_SERVER_KEY: process.env.FCM_SERVER_KEY,

    // S3 Configuration
    S3: {
        KEY: process.env.S3_KEY,
        SECRET: process.env.S3_SECRET,
        REGION: process.env.S3_REGION,
        BUCKET: process.env.S3_BUCKET,
        BASE_PATH: process.env.S3_BASE_PATH || 'temp/',
        PUBLIC_BASE_PATH: process.env.S3_PUBLIC_BASE_PATH
    }
};
