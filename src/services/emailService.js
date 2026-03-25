const nodemailer = require('nodemailer');
const config = require('../config/config');

// Create transporter — uses real SMTP if configured, otherwise logs to console
const createTransporter = () => {
    if (config.SMTP_HOST && config.SMTP_USER && config.SMTP_PASS) {
        return nodemailer.createTransport({
            host: config.SMTP_HOST,
            port: config.SMTP_PORT || 587,
            secure: config.SMTP_PORT === '465',
            auth: {
                user: config.SMTP_USER,
                pass: config.SMTP_PASS
            }
        });
    }
    return null;
};

const transporter = createTransporter();

/**
 * Send an email
 * @param {Object} options - { to, subject, text, html }
 */
const sendEmail = async ({ to, subject, text, html }) => {
    const mailOptions = {
        from: config.SMTP_FROM || '"Mental Health Platform" <noreply@mentalhealth.com>',
        to,
        subject,
        text,
        html
    };

    if (transporter) {
        try {
            const info = await transporter.sendMail(mailOptions);
            console.log(`  📧 Email sent to ${to} (${info.messageId})`);
            return { success: true, messageId: info.messageId };
        } catch (err) {
            console.error(`  ❌ Email failed to ${to}:`, err.message);
            return { success: false, error: err.message };
        }
    } else {
        // Log mode
        console.log(`  📧 [LOG MODE] Email → ${to}`);
        console.log(`     Subject: ${subject}`);
        console.log(`     Body: ${text || '(HTML content)'}`);
        return { success: true, mode: 'log' };
    }
};

module.exports = { sendEmail };
