const Master = require('../models/Master');
const User = require('../models/User');
const { sendSuccess, sendError } = require('../utils/responseHelper');

/**
 * Helper to calculate age from date of birth
 */
const calculateAge = (dob) => {
    if (!dob) return null;
    const today = new Date();
    const birthDate = new Date(dob);
    let age = today.getFullYear() - birthDate.getFullYear();
    const monthDiff = today.getMonth() - birthDate.getMonth();
    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
        age--;
    }
    return age;
};

/**
 * Helper to get patient profile for filtering
 * Priority: Path Param -> Query Param -> Authenticated User
 */
const getPatientProfile = async (req) => {
    const userIdInput = req.params.userId || req.query.patientId;

    if (userIdInput) {
        return await User.findOne({ userId: parseInt(userIdInput) });
    }

    // Fallback to authenticated user if they are a patient
    if (req.user && req.user.role === 'patient') {
        return req.user;
    }

    return null;
};

// @desc    Get all resource masters
// @route   GET /api/v1/resource/masters/:userId?
// @access  Private
exports.getMasters = async (req, res, next) => {
    try {
        const {
            page = 1,
            limit = 10,
            search,
            master_type_slug,
            is_active
        } = req.query;

        const query = {};

        // Active filter
        query.is_active = is_active !== undefined ? parseInt(is_active) : 1;

        // Master type filter
        if (master_type_slug) {
            query.master_type_slug = master_type_slug;
        }

        // Search filter
        if (search) {
            query.$or = [
                { name: { $regex: search, $options: 'i' } },
                { slug: { $regex: search, $options: 'i' } }
            ];
        }

        // Automated demographics filtering
        const patient = await getPatientProfile(req);
        const finalAge = req.query.age ? parseInt(req.query.age) : (patient ? calculateAge(patient.dateOfBirth) : null);
        const finalGender = req.query.gender || (patient ? patient.gender : null);

        if (finalAge !== null) {
            query.minAge = { $lte: finalAge };
            query.maxAge = { $gte: finalAge };
        }

        if (finalGender) {
            query.gender = { $in: [finalGender, 'all'] };
        }

        const total = await Master.countDocuments(query);
        const masters = await Master.find(query)
            .skip((page - 1) * limit)
            .limit(parseInt(limit))
            .sort({ createdAt: -1 });

        const formattedMasters = masters.map(m => ({
            id: m.masterId,
            attributes: m.attributes,
            master_type_slug: m.master_type_slug,
            name: m.name,
            slug: m.slug,
            is_active: m.is_active
        }));

        sendSuccess(res, 200, 'Resource masters fetched successfully', {
            masters: formattedMasters,
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

// @desc    Get all resource masters (No pagination)
// @route   GET /api/v1/resource/masters/all/:userId?
// @access  Private
exports.getAllMasters = async (req, res, next) => {
    try {
        const {
            search,
            master_type_slug,
            is_active
        } = req.query;

        const query = {};

        // Active filter
        query.is_active = is_active !== undefined ? parseInt(is_active) : 1;

        // Master type filter
        if (master_type_slug) {
            query.master_type_slug = master_type_slug;
        }

        // Search filter
        if (search) {
            query.$or = [
                { name: { $regex: search, $options: 'i' } },
                { slug: { $regex: search, $options: 'i' } }
            ];
        }

        // Automated demographics filtering
        const patient = await getPatientProfile(req);
        const finalAge = req.query.age ? parseInt(req.query.age) : (patient ? calculateAge(patient.dateOfBirth) : null);
        const finalGender = req.query.gender || (patient ? patient.gender : null);

        if (finalAge !== null) {
            query.minAge = { $lte: finalAge };
            query.maxAge = { $gte: finalAge };
        }

        if (finalGender) {
            query.gender = { $in: [finalGender, 'all'] };
        }

        const masters = await Master.find(query).sort({ createdAt: -1 });

        const formattedMasters = masters.map(m => ({
            id: m.masterId,
            attributes: m.attributes,
            master_type_slug: m.master_type_slug,
            name: m.name,
            slug: m.slug,
            is_active: m.is_active
        }));

        sendSuccess(res, 200, 'All resource masters fetched successfully', {
            masters: formattedMasters,
            total: masters.length
        });
    } catch (err) {
        next(err);
    }
};
