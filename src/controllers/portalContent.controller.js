const Master = require('../models/Master');
const { sendSuccess, sendError } = require('../utils/responseHelper');

/**
 * @desc    Get portal content (Privacy, ToS, FAQ)
 * @route   GET /api/v1/portal/content/:type
 * @access  Public
 */
exports.getPortalContent = async (req, res, next) => {
    try {
        const { type } = req.params;
        const content = await Master.findOne({ master_type_slug: 'portal_content', slug: type, is_active: 1 });

        if (!content) {
            return sendError(res, 404, `Portal content for '${type}' not found`);
        }

        sendSuccess(res, 200, 'Portal content fetched successfully', {
            type: content.slug,
            title: content.name,
            content: content.attributes.body,
            updatedAt: content.updatedAt
        });
    } catch (err) {
        next(err);
    }
};

/**
 * @desc    Get all help center articles/topics
 * @route   GET /api/v1/portal/help-center
 * @access  Public
 */
exports.getHelpCenterList = async (req, res, next) => {
    try {
        const contents = await Master.find({ master_type_slug: 'portal_content', is_active: 1 })
            .sort({ name: 1 })
            .select('name slug attributes.body updatedAt');

        const helpTopics = contents.map(item => ({
            id: item._id,
            title: item.name,
            slug: item.slug,
            preview: item.attributes.body ? item.attributes.body.substring(0, 100) + '...' : '',
            updatedAt: item.updatedAt
        }));

        sendSuccess(res, 200, 'Help center topics fetched successfully', helpTopics);
    } catch (err) {
        next(err);
    }
};

/**
 * @desc    Update portal content (Privacy, ToS, FAQ)
 * @route   PUT /api/v1/portal/content/:type
 * @access  Private (Super Admin)
 */
exports.updatePortalContent = async (req, res, next) => {
    try {
        const { type } = req.params;
        const { title, body } = req.body;

        if (!title || !body) {
            return sendError(res, 400, 'Please provide title and body');
        }

        let content = await Master.findOne({ master_type_slug: 'portal_content', slug: type });

        if (content) {
            content.name = title;
            content.attributes = { ...content.attributes, body };
            await content.save();
        } else {
            content = await Master.create({
                name: title,
                slug: type,
                master_type_slug: 'portal_content',
                attributes: { body },
                is_active: 1
            });
        }

        sendSuccess(res, 200, `Portal content for '${type}' updated successfully`, content);
    } catch (err) {
        next(err);
    }
};
