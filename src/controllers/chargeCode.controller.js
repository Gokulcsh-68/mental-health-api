const ChargeCode = require('../models/ChargeCode');
const TaxCode = require('../models/TaxCode');
const User = require('../models/User');
const { sendSuccess, sendError } = require('../utils/responseHelper');

// @desc    Get all charge codes
// @route   GET /api/v1/charge-codes
// @access  Private
exports.getChargeCodes = async (req, res, next) => {
    try {
        const {
            page = 1,
            limit = 10,
            search,
            specialist_id,
            is_active
        } = req.query;

        const query = {};

        if (is_active !== undefined) {
            query.is_active = parseInt(is_active);
        }

        if (specialist_id !== undefined) {
            query.specialist_id = parseInt(specialist_id);
        }

        if (search) {
            query.$or = [
                { code: { $regex: search, $options: 'i' } },
                { name: { $regex: search, $options: 'i' } }
            ];
        }

        const total = await ChargeCode.countDocuments(query);
        const chargeCodes = await ChargeCode.find(query)
            .skip((page - 1) * limit)
            .limit(parseInt(limit))
            .sort({ createdAt: -1 });

        // Enrich with tax code details
        const chargeCodeIds = [...new Set(chargeCodes.flatMap(c => c.tax_codes))];
        const taxCodesMap = {};
        if (chargeCodeIds.length > 0) {
            const taxCodes = await TaxCode.find({ taxCodeId: { $in: chargeCodeIds } });
            taxCodes.forEach(t => { taxCodesMap[t.taxCodeId] = t; });
        }

        sendSuccess(res, 200, 'Charge codes fetched successfully', {
            charge_codes: chargeCodes.map(c => formatChargeCode(c, taxCodesMap)),
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

// @desc    Get single charge code by ID
// @route   GET /api/v1/charge-codes/:id
// @access  Private
exports.getChargeCodeById = async (req, res, next) => {
    try {
        const chargeCode = await ChargeCode.findOne({ chargeCodeId: parseInt(req.params.id) });

        if (!chargeCode) {
            return sendError(res, 404, 'Charge code not found');
        }

        // Fetch associated tax codes
        const taxCodesMap = {};
        if (chargeCode.tax_codes && chargeCode.tax_codes.length > 0) {
            const taxCodes = await TaxCode.find({ taxCodeId: { $in: chargeCode.tax_codes } });
            taxCodes.forEach(t => { taxCodesMap[t.taxCodeId] = t; });
        }

        sendSuccess(res, 200, 'Charge code fetched successfully', formatChargeCode(chargeCode, taxCodesMap));
    } catch (err) {
        next(err);
    }
};

// @desc    Create a charge code
// @route   POST /api/v1/charge-codes
// @access  Private
exports.createChargeCode = async (req, res, next) => {
    try {
        const { code, name, amount, specialist_id, specialist_role, tax_codes, description, is_active } = req.body;

        if (!code || !name || amount === undefined || !specialist_id) {
            return sendError(res, 400, 'code, name, amount, and specialist_id are required');
        }

        // Validate specialist exists
        const specialist = await User.findOne({ userId: parseInt(specialist_id) });
        if (!specialist) {
            return sendError(res, 404, `Specialist with userId ${specialist_id} not found`);
        }

        // Check duplicate code
        const existing = await ChargeCode.findOne({ code: code.toUpperCase() });
        if (existing) {
            return sendError(res, 409, `Charge code '${code.toUpperCase()}' already exists`);
        }

        // Validate tax_codes if provided
        if (tax_codes && tax_codes.length > 0) {
            const foundTaxCodes = await TaxCode.find({ taxCodeId: { $in: tax_codes } });
            if (foundTaxCodes.length !== tax_codes.length) {
                return sendError(res, 400, 'One or more tax_code IDs are invalid');
            }
        }

        const chargeCode = await ChargeCode.create({
            code,
            name,
            amount,
            specialist_id: parseInt(specialist_id),
            specialist_role: specialist_role || specialist.role || null,
            tax_codes: tax_codes || [],
            description,
            is_active
        });

        sendSuccess(res, 201, 'Charge code created successfully', {
            id: chargeCode.chargeCodeId,
            code: chargeCode.code,
            name: chargeCode.name,
            amount: chargeCode.amount,
            specialist_id: chargeCode.specialist_id
        });
    } catch (err) {
        next(err);
    }
};

// @desc    Update a charge code
// @route   PUT /api/v1/charge-codes/:id
// @access  Private
exports.updateChargeCode = async (req, res, next) => {
    try {
        const { name, amount, specialist_id, specialist_role, tax_codes, description, is_active } = req.body;

        const chargeCode = await ChargeCode.findOne({ chargeCodeId: parseInt(req.params.id) });
        if (!chargeCode) {
            return sendError(res, 404, 'Charge code not found');
        }

        // Validate new specialist if being updated
        if (specialist_id !== undefined) {
            const specialist = await User.findOne({ userId: parseInt(specialist_id) });
            if (!specialist) {
                return sendError(res, 404, `Specialist with userId ${specialist_id} not found`);
            }
            chargeCode.specialist_id = parseInt(specialist_id);
            chargeCode.specialist_role = specialist_role || specialist.role || chargeCode.specialist_role;
        }

        // Validate new tax_codes if being updated
        if (tax_codes !== undefined) {
            if (tax_codes.length > 0) {
                const foundTaxCodes = await TaxCode.find({ taxCodeId: { $in: tax_codes } });
                if (foundTaxCodes.length !== tax_codes.length) {
                    return sendError(res, 400, 'One or more tax_code IDs are invalid');
                }
            }
            chargeCode.tax_codes = tax_codes;
        }

        if (name !== undefined) chargeCode.name = name;
        if (amount !== undefined) chargeCode.amount = amount;
        if (specialist_role !== undefined && specialist_id === undefined) chargeCode.specialist_role = specialist_role;
        if (description !== undefined) chargeCode.description = description;
        if (is_active !== undefined) chargeCode.is_active = is_active;

        await chargeCode.save();

        sendSuccess(res, 200, 'Charge code updated successfully', {
            id: chargeCode.chargeCodeId,
            code: chargeCode.code,
            name: chargeCode.name,
            amount: chargeCode.amount,
            specialist_id: chargeCode.specialist_id,
            tax_codes: chargeCode.tax_codes,
            is_active: chargeCode.is_active
        });
    } catch (err) {
        next(err);
    }
};

// @desc    Delete a charge code
// @route   DELETE /api/v1/charge-codes/:id
// @access  Private
exports.deleteChargeCode = async (req, res, next) => {
    try {
        const chargeCode = await ChargeCode.findOne({ chargeCodeId: parseInt(req.params.id) });
        if (!chargeCode) {
            return sendError(res, 404, 'Charge code not found');
        }

        await ChargeCode.deleteOne({ chargeCodeId: parseInt(req.params.id) });

        sendSuccess(res, 200, 'Charge code deleted successfully', null);
    } catch (err) {
        next(err);
    }
};

// --- Helper ---
function formatChargeCode(c, taxCodesMap = {}) {
    return {
        id: c.chargeCodeId,
        code: c.code,
        name: c.name,
        amount: c.amount,
        specialist_id: c.specialist_id,
        specialist_role: c.specialist_role,
        tax_codes: c.tax_codes.map(tcId => {
            const tc = taxCodesMap[tcId];
            return tc
                ? { id: tc.taxCodeId, code: tc.code, name: tc.name, rate: tc.rate }
                : { id: tcId };
        }),
        description: c.description,
        is_active: c.is_active,
        createdAt: c.createdAt
    };
}
