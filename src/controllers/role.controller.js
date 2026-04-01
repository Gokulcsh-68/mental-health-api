const Role = require('../models/Role');
const { sendSuccess, sendError } = require('../utils/responseHelper');

// @desc    Get all roles
// @route   GET /api/v1/roles
// @access  Private (Admin)
exports.getRoles = async (req, res, next) => {
    try {
        const roles = await Role.find().sort({ id: 1 });
        sendSuccess(res, 200, 'Roles fetched successfully', roles);
    } catch (err) {
        next(err);
    }
};

// @desc    Create a new role
// @route   POST /api/v1/roles
// @access  Private (Admin)
exports.createRole = async (req, res, next) => {
    try {
        const { id, code, name } = req.body;

        if (!id || !code || !name) {
            return sendError(res, 400, 'Please provide id, code and name');
        }

        const role = await Role.create({ id, code, name });
        sendSuccess(res, 201, 'Role created successfully', role);
    } catch (err) {
        next(err);
    }
};

// @desc    Get role by MongoDB ID or code
// @route   GET /api/v1/roles/:id
// @access  Private (Admin)
exports.getRole = async (req, res, next) => {
    try {
        const role = await Role.findOne({
            $or: [
                { _id: req.params.id.match(/^[0-9a-fA-F]{24}$/) ? req.params.id : null },
                { code: req.params.id },
                { id: isNaN(req.params.id) ? null : parseInt(req.params.id) }
            ].filter(q => q !== null)
        });

        if (!role) {
            return sendError(res, 404, 'Role not found');
        }

        sendSuccess(res, 200, 'Role details fetched successfully', role);
    } catch (err) {
        next(err);
    }
};

// @desc    Update role
// @route   PUT /api/v1/roles/:id
// @access  Private (Admin)
exports.updateRole = async (req, res, next) => {
    try {
        const role = await Role.findOneAndUpdate(
            {
                $or: [
                    { _id: req.params.id.match(/^[0-9a-fA-F]{24}$/) ? req.params.id : null },
                    { code: req.params.id }
                ].filter(q => q !== null)
            },
                req.body,
                { returnDocument: 'after', runValidators: true }
        );

        if (!role) {
            return sendError(res, 404, 'Role not found');
        }

        sendSuccess(res, 200, 'Role updated successfully', role);
    } catch (err) {
        next(err);
    }
};

// @desc    Delete role
// @route   DELETE /api/v1/roles/:id
// @access  Private (Admin)
exports.deleteRole = async (req, res, next) => {
    try {
        const role = await Role.findOneAndDelete({
            $or: [
                { _id: req.params.id.match(/^[0-9a-fA-F]{24}$/) ? req.params.id : null },
                { code: req.params.id }
            ].filter(q => q !== null)
        });

        if (!role) {
            return sendError(res, 404, 'Role not found');
        }

        sendSuccess(res, 200, 'Role deleted successfully');
    } catch (err) {
        next(err);
    }
};
