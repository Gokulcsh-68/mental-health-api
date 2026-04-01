const SystemSetting = require('../models/SystemSetting');
const { sendSuccess, sendError } = require('../utils/responseHelper');

/**
 * @desc    Get all system settings
 * @route   GET /api/v1/settings
 * @access  Private (Super Admin)
 */
exports.getSettings = async (req, res, next) => {
    try {
        const settings = await SystemSetting.find();
        sendSuccess(res, 200, 'System settings fetched successfully', settings);
    } catch (err) {
        next(err);
    }
};

/**
 * @desc    Get setting by key
 * @route   GET /api/v1/settings/:key
 * @access  Public (or Private depending on key)
 */
exports.getSettingByKey = async (req, res, next) => {
    try {
        const setting = await SystemSetting.findOne({ key: req.params.key });
        if (!setting) {
            return sendError(res, 404, 'Setting not found');
        }
        sendSuccess(res, 200, 'Setting fetched successfully', setting);
    } catch (err) {
        next(err);
    }
};

/**
 * @desc    Update or create system setting
 * @route   POST /api/v1/settings
 * @access  Private (Super Admin)
 */
exports.updateSetting = async (req, res, next) => {
    try {
        const { key, value, description, category } = req.body;

        if (!key || value === undefined) {
            return sendError(res, 400, 'Please provide key and value');
        }

        const setting = await SystemSetting.findOneAndUpdate(
            { key: key.toLowerCase() },
            { 
                value, 
                description, 
                category: category || 'general',
                updatedBy: req.user._id,
                updatedAt: Date.now()
            },
            { upsert: true, returnDocument: 'after', runValidators: true }
        );

        sendSuccess(res, 200, 'Setting updated successfully', setting);
    } catch (err) {
        next(err);
    }
};

/**
 * @desc    Initialize default settings
 */
exports.initializeDefaultSettings = async () => {
    const defaults = [
        { key: 'support_email', value: 'support@mentalhealth.com', description: 'Public support contact email', category: 'general' },
        { key: 'maintenance_mode', value: false, description: 'Disable app for maintenance', category: 'maintenance' },
        { key: 'api_version', value: '1.0.0', description: 'Current API version', category: 'general' },
        { key: 'app_name', value: 'Skyheal Mental Health', description: 'Platform display name', category: 'general' }
    ];

    for (const s of defaults) {
        await SystemSetting.findOneAndUpdate({ key: s.key }, s, { upsert: true });
    }
    console.log('✅ Default system settings initialized');
};
