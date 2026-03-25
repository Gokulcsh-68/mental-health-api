const config = require('../config/config');

/**
 * Send an SMS
 * @param {Object} options - { to, message }
 */
const sendSMS = async ({ to, message }) => {
    if (config.TWILIO_SID && config.TWILIO_AUTH_TOKEN && config.TWILIO_PHONE) {
        try {
            const twilio = require('twilio')(config.TWILIO_SID, config.TWILIO_AUTH_TOKEN);
            const result = await twilio.messages.create({
                body: message,
                from: config.TWILIO_PHONE,
                to
            });
            console.log(`  📱 SMS sent to ${to} (SID: ${result.sid})`);
            return { success: true, sid: result.sid };
        } catch (err) {
            console.error(`  ❌ SMS failed to ${to}:`, err.message);
            return { success: false, error: err.message };
        }
    } else {
        // Log mode
        console.log(`  📱 [LOG MODE] SMS → ${to}`);
        console.log(`     Message: ${message}`);
        return { success: true, mode: 'log' };
    }
};

module.exports = { sendSMS };
