const TaxCode = require('../models/TaxCode');
const { sendSuccess, sendError } = require('../utils/responseHelper');

// @desc    Get all tax codes
// @route   GET /api/v1/tax-codes
// @access  Private
exports.getTaxCodes = async (req, res, next) => {
    try {
        const {
            page = 1,
            limit = 10,
            search,
            is_active
        } = req.query;

        const query = {};

        if (is_active !== undefined) {
            query.is_active = parseInt(is_active);
        }

        if (search) {
            query.$or = [
                { code: { $regex: search, $options: 'i' } },
                { name: { $regex: search, $options: 'i' } }
            ];
        }

        const total = await TaxCode.countDocuments(query);
        const taxCodes = await TaxCode.find(query)
            .skip((page - 1) * limit)
            .limit(parseInt(limit))
            .sort({ createdAt: -1 });

        sendSuccess(res, 200, 'Tax codes fetched successfully', {
            tax_codes: taxCodes.map(t => ({
                id: t.taxCodeId,
                code: t.code,
                name: t.name,
                rate: t.rate,
                description: t.description,
                is_active: t.is_active,
                createdAt: t.createdAt
            })),
            pagination: {
                page: parseInt(page),
                limit: parseInt(limit),
                total,
                totalPages: Math.ceil(total / limit)
            }
        });
    } catch (err) {
        next(err);
    }
};

// @desc    Get single tax code by ID
// @route   GET /api/v1/tax-codes/:id
// @access  Private
exports.getTaxCodeById = async (req, res, next) => {
    try {
        const taxCode = await TaxCode.findOne({ taxCodeId: parseInt(req.params.id) });

        if (!taxCode) {
            return sendError(res, 404, 'Tax code not found');
        }

        sendSuccess(res, 200, 'Tax code fetched successfully', {
            id: taxCode.taxCodeId,
            code: taxCode.code,
            name: taxCode.name,
            rate: taxCode.rate,
            description: taxCode.description,
            is_active: taxCode.is_active,
            createdAt: taxCode.createdAt
        });
    } catch (err) {
        next(err);
    }
};

// @desc    Create a tax code
// @route   POST /api/v1/tax-codes
// @access  Private
exports.createTaxCode = async (req, res, next) => {
    try {
        const { code, name, rate, description, is_active } = req.body;

        if (!code || !name || rate === undefined) {
            return sendError(res, 400, 'code, name, and rate are required');
        }

        const existing = await TaxCode.findOne({ code: code.toUpperCase() });
        if (existing) {
            return sendError(res, 409, `Tax code '${code.toUpperCase()}' already exists`);
        }

        const taxCode = await TaxCode.create({ code, name, rate, description, is_active });

        sendSuccess(res, 201, 'Tax code created successfully', {
            id: taxCode.taxCodeId,
            code: taxCode.code,
            name: taxCode.name,
            rate: taxCode.rate
        });
    } catch (err) {
        next(err);
    }
};

// @desc    Update a tax code
// @route   PUT /api/v1/tax-codes/:id
// @access  Private
exports.updateTaxCode = async (req, res, next) => {
    try {
        const { name, rate, description, is_active } = req.body;

        const taxCode = await TaxCode.findOne({ taxCodeId: parseInt(req.params.id) });
        if (!taxCode) {
            return sendError(res, 404, 'Tax code not found');
        }

        if (name !== undefined) taxCode.name = name;
        if (rate !== undefined) taxCode.rate = rate;
        if (description !== undefined) taxCode.description = description;
        if (is_active !== undefined) taxCode.is_active = is_active;

        await taxCode.save();

        sendSuccess(res, 200, 'Tax code updated successfully', {
            id: taxCode.taxCodeId,
            code: taxCode.code,
            name: taxCode.name,
            rate: taxCode.rate,
            is_active: taxCode.is_active
        });
    } catch (err) {
        next(err);
    }
};

// @desc    Delete a tax code
// @route   DELETE /api/v1/tax-codes/:id
// @access  Private
exports.deleteTaxCode = async (req, res, next) => {
    try {
        const taxCode = await TaxCode.findOne({ taxCodeId: parseInt(req.params.id) });
        if (!taxCode) {
            return sendError(res, 404, 'Tax code not found');
        }

        await TaxCode.deleteOne({ taxCodeId: parseInt(req.params.id) });

        sendSuccess(res, 200, 'Tax code deleted successfully', null);
    } catch (err) {
        next(err);
    }
};
