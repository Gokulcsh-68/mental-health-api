const User = require('../models/User');
const Consult = require('../models/Consult');
const { sendSuccess, sendError } = require('../utils/responseHelper');
const { uploadFromUrl, s3 } = require('../services/S3Service');
const { PutObjectCommand } = require('@aws-sdk/client-s3');
const config = require('../config/config');
const path = require('path');

// @desc    Get logged-in user info
// @route   GET /api/v1/users/info
// @access  Private
exports.getUserInfo = async (req, res, next) => {
    try {
        const user = await User.findById(req.user._id);

        if (!user) {
            return sendError(res, 404, 'User not found');
        }

        sendSuccess(res, 200, 'User info fetched successfully', user);
    } catch (err) {
        next(err);
    }
};

// @desc    Update current user profile
// @route   PUT /api/v1/users/update-me
// @access  Private
exports.updateProfile = async (req, res, next) => {
    try {
        const fieldsToUpdate = { ...req.body };

        // Prevent updating sensitive fields
        delete fieldsToUpdate.password;
        delete fieldsToUpdate.role;
        delete fieldsToUpdate.userId;
        delete fieldsToUpdate.resetPasswordToken;
        delete fieldsToUpdate.resetPasswordExpire;

        // Handle profile image upload (file or URL)
        if (req.file) {
            // If file was uploaded via multer-s3, the URL is in req.file.location
            fieldsToUpdate.profileImage = req.file.location;
        } else if (req.body.profileImage && req.body.profileImage.startsWith('http')) {
            // If a URL is provided, upload it to S3 to ensure it's stored in our bucket
            try {
                const s3Url = await uploadFromUrl(req.body.profileImage);
                fieldsToUpdate.profileImage = s3Url;
            } catch (err) {
                console.error('Failed to upload profile image from URL:', err.message);
                // Continue without updating image if upload fails
            }
        }

        const user = await User.findByIdAndUpdate(
            req.user._id,
            fieldsToUpdate,
            { returnDocument: 'after', runValidators: true }
        );

        sendSuccess(res, 200, 'Profile updated successfully', user);
    } catch (err) {
        next(err);
    }
};

// @desc    Get user by ID
// @route   GET /api/v1/users/:id
// @access  Private
exports.getUserById = async (req, res, next) => {
    try {
        const user = await User.findOne({ userId: parseInt(req.params.id) })
            .populate([
                { path: 'reportingTo', select: 'userId firstName lastName role' },
                { path: 'hospital', select: 'userId firstName lastName role' },
                { path: 'professional', select: 'userId firstName lastName role' }
            ]);

        if (!user) {
            return sendError(res, 404, 'User not found');
        }

        sendSuccess(res, 200, 'User fetched successfully', user);
    } catch (err) {
        next(err);
    }
};

// @desc    Update user
// @route   PUT /api/v1/users/:id
// @access  Private
exports.updateUser = async (req, res, next) => {
    try {
        const fieldsToUpdate = { ...req.body };

        // Prevent updating sensitive fields
        delete fieldsToUpdate.password;
        delete fieldsToUpdate.role;
        delete fieldsToUpdate.userId;
        delete fieldsToUpdate.resetPasswordToken;
        delete fieldsToUpdate.resetPasswordExpire;

        fieldsToUpdate.updatedBy = req.user._id;

        // Handle profile image upload (file or URL)
        if (req.file) {
            fieldsToUpdate.profileImage = req.file.location;
        } else if (req.body.profileImage && req.body.profileImage.startsWith('http')) {
            try {
                const s3Url = await uploadFromUrl(req.body.profileImage);
                fieldsToUpdate.profileImage = s3Url;
            } catch (err) {
                console.error('Failed to upload profile image from URL:', err.message);
            }
        }

        const user = await User.findOneAndUpdate(
            { userId: parseInt(req.params.id) },
            fieldsToUpdate,
            { returnDocument: 'after', runValidators: true }
        );

        if (!user) {
            return sendError(res, 404, 'User not found');
        }

        sendSuccess(res, 200, 'User updated successfully', user);
    } catch (err) {
        next(err);
    }
};

// @desc    Deactivate user (soft delete)
// @route   DELETE /api/v1/users/:id
// @access  Private
exports.deleteUser = async (req, res, next) => {
    try {
        const user = await User.findOneAndUpdate(
            { userId: parseInt(req.params.id) },
            { isActive: false, updatedBy: req.user._id },
            { returnDocument: 'after' }
        );

        if (!user) {
            return sendError(res, 404, 'User not found');
        }

        sendSuccess(res, 200, 'User deactivated successfully', user);
    } catch (err) {
        next(err);
    }
};

// @desc    Toggle user active status
// @route   PUT /api/v1/users/:id/toggle-status
// @access  Private
exports.toggleUserStatus = async (req, res, next) => {
    try {
        const user = await User.findOne({ userId: parseInt(req.params.id) });

        if (!user) {
            return sendError(res, 404, 'User not found');
        }

        user.isActive = !user.isActive;
        user.updatedBy = req.user._id;
        await user.save();

        sendSuccess(res, 200, `User ${user.isActive ? 'activated' : 'deactivated'} successfully`, user);
    } catch (err) {
        next(err);
    }
};

// @desc    Create user by role (patient, psychologist, etc.)
// @route   POST /api/v1/users/:role
// @access  Private
const ALL_ROLES = ['super_admin', 'admin', 'hospital', 'psychiatrist', 'psychologist', 'nurse', 'social_worker', 'counselor', 'patient'];
const PROFESSIONAL_ROLES = ['psychiatrist', 'psychologist', 'nurse', 'social_worker', 'counselor'];

exports.createUserByRole = async (req, res, next) => {
    try {
        const { role } = req.params;
        const creator = req.user;

        if (!ALL_ROLES.includes(role)) {
            return sendError(res, 400, `Invalid role '${role}'. Allowed: ${ALL_ROLES.join(', ')}`);
        }

        // ─────────────────────────────────────────────
        // RBAC: Who can create whom?
        // super_admin  → anyone
        // admin        → anyone except super_admin
        // hospital     → professionals + patient
        // professional → patient only
        // ─────────────────────────────────────────────
        const canCreate = (creatorRole, targetRole) => {
            if (creatorRole === 'super_admin') return true;
            if (creatorRole === 'admin') return targetRole !== 'super_admin';
            if (creatorRole === 'hospital') return [...PROFESSIONAL_ROLES, 'patient'].includes(targetRole);
            if (PROFESSIONAL_ROLES.includes(creatorRole)) return targetRole === 'patient';
            return false;
        };

        if (!canCreate(creator.role, role)) {
            return sendError(res, 403, `User with role '${creator.role}' is not authorized to create a '${role}'`);
        }

        const {
            firstName, lastName, username, email, password, phone,
            dateOfBirth, gender, address, emergencyContact,
            isdCode, mobile, profileImage, bloodGroup, timezoneId, countryIso,
            is2fa, secret, isActive, communicationPreferences, fcmTokens,
            reportingTo: manualReportingTo, hospitalId, professionalId,
            specialization, about, experienceYears, qualifications, languages, consultationFee, skills
        } = req.body;

        // ─────────────────────────────────────────────
        // Hierarchy auto-mapping (deterministic by creator role)
        //
        //  super_admin → creates admin
        //      reportingTo = super_admin
        //      hospital    = null
        //      professional= null
        //
        //  admin → creates hospital
        //      reportingTo = admin
        //      hospital    = null (hospital IS the user)
        //      professional= null
        //
        //  admin → creates admin (under themselves)
        //      reportingTo = admin
        //
        //  hospital → creates professional / patient
        //      reportingTo = hospital
        //      hospital    = hospital._id
        //      professional= null  (set when professional creates patient)
        //
        //  professional → creates patient
        //      reportingTo = professional
        //      hospital    = professional.hospital
        //      professional= professional._id
        // ─────────────────────────────────────────────

        let assignedHospital = null;
        let assignedProfessional = null;
        let finalReportingTo = creator._id; // default: report to creator

        if (creator.role === 'super_admin') {
            // Creating admin → just report to super_admin, no hospital
            finalReportingTo = creator._id;
            assignedHospital = null;
            assignedProfessional = null;

        } else if (creator.role === 'admin') {
            // Creating hospital or any lower role → report to admin
            finalReportingTo = creator._id;
            assignedHospital = null;
            assignedProfessional = null;

            // Admin can manually assign a hospital context if creating a professional/patient
            if (hospitalId) {
                const h = await User.findOne({ userId: parseInt(hospitalId), role: 'hospital' });
                if (h) assignedHospital = h._id;
            }

        } else if (creator.role === 'hospital') {
            // Creating professional or patient → tagged under this hospital
            finalReportingTo = creator._id;
            assignedHospital = creator._id;
            assignedProfessional = null;

            // Admin can optionally assign a specific professional as reportingTo for patients
            if (professionalId && role === 'patient') {
                const p = await User.findOne({ userId: parseInt(professionalId) });
                if (p && PROFESSIONAL_ROLES.includes(p.role)) {
                    assignedProfessional = p._id;
                    finalReportingTo = p._id; // patient reports to professional
                }
            }

        } else if (PROFESSIONAL_ROLES.includes(creator.role)) {
            // Creating patient → tagged under same hospital, reports to this professional
            finalReportingTo = creator._id;
            assignedHospital = creator.hospital || null;
            assignedProfessional = creator._id;
        }

        // Manual override for reportingTo (admins and super_admins only)
        if (manualReportingTo && ['super_admin', 'admin'].includes(creator.role)) {
            const r = await User.findOne({ userId: parseInt(manualReportingTo) });
            if (r) finalReportingTo = r._id;
        }

        const userData = {
            firstName, lastName, username, email, password, phone,
            role,
            dateOfBirth, gender, address, emergencyContact,
            isdCode, mobile, profileImage, bloodGroup, timezoneId, countryIso,
            is2fa, secret, isActive, communicationPreferences, fcmTokens,
            reportingTo: finalReportingTo,
            hospital: assignedHospital,
            professional: assignedProfessional,
            createdBy: creator._id,
            // Professional-specific fields
            specialization, about, experienceYears, qualifications, languages, consultationFee, skills
        };

        const user = await User.create(userData);

        await user.populate([
            { path: 'reportingTo', select: 'userId firstName lastName role' },
            { path: 'hospital', select: 'userId firstName lastName role' },
            { path: 'professional', select: 'userId firstName lastName role' }
        ]);

        sendSuccess(res, 201, `${role} created successfully`, user);
    } catch (err) {
        next(err);
    }
};

// @desc    Get all users (with filters)
// @route   GET /api/v1/users/list?role=patient&page=1&limit=10
// @access  Private
exports.listUsers = async (req, res, next) => {
    try {
        const { role, page = 1, limit = 10, search, isActive, isVerified, hospitalId, professionalId } = req.query;

        const query = {};

        // Role filter
        if (role) query.role = role;

        // Active status filter
        if (isActive !== undefined) query.isActive = isActive === 'true';

        // Verification status filter
        if (isVerified !== undefined) query.isVerified = isVerified === 'true';

        // Name search
        if (search) {
            query.$or = [
                { firstName: { $regex: search, $options: 'i' } },
                { lastName: { $regex: search, $options: 'i' } },
                { username: { $regex: search, $options: 'i' } },
                { email: { $regex: search, $options: 'i' } }
            ];
        }

        // Hierarchy filters
        if (hospitalId) {
            const h = await User.findOne({ userId: parseInt(hospitalId) });
            if (h) query.hospital = h._id;
        }

        if (professionalId) {
            const p = await User.findOne({ userId: parseInt(professionalId) });
            if (p) query.professional = p._id;
        }

        const total = await User.countDocuments(query);
        const users = await User.find(query)
            .populate([
                { path: 'reportingTo', select: 'userId firstName lastName role' },
                { path: 'hospital', select: 'userId firstName lastName role' },
                { path: 'professional', select: 'userId firstName lastName role' }
            ])
            .skip((page - 1) * limit)
            .limit(parseInt(limit))
            .sort({ createdAt: -1 });

        sendSuccess(res, 200, 'Users fetched successfully', {
            users,
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

// @desc    Get user statistics (counts by role)
// @route   GET /api/v1/users/stats
// @access  Private
exports.getUserStats = async (req, res, next) => {
    try {
        const stats = await User.aggregate([
            {
                $group: {
                    _id: '$role',
                    count: { $sum: 1 }
                }
            }
        ]);

        const formattedStats = stats.reduce((acc, curr) => {
            acc[curr._id] = curr.count;
            return acc;
        }, {});

        // Add additional stats
        const activeCount = await User.countDocuments({ isActive: true });
        const totalCount = await User.countDocuments();

        sendSuccess(res, 200, 'User statistics fetched successfully', {
            byRole: formattedStats,
            activeCount,
            totalCount
        });
    } catch (err) {
        next(err);
    }
};

// @desc    Get hospital-specific user statistics (counts by role, excluding admins)
// @route   GET /api/v1/users/hospital-stats
// @access  Private (Admin/Hospital)
exports.getHospitalUserStats = async (req, res, next) => {
    try {
        let hospitalObjectId = null;

        // 1. Determine Hospital ID
        if (['super_admin', 'admin'].includes(req.user.role)) {
            const { hospitalId } = req.query;
            if (!hospitalId) {
                return sendError(res, 400, 'Please provide a hospitalId');
            }
            const hospital = await User.findOne({ userId: parseInt(hospitalId), role: 'hospital' });
            if (!hospital) {
                return sendError(res, 404, 'Hospital not found');
            }
            hospitalObjectId = hospital._id;
        } else if (req.user.role === 'hospital') {
            hospitalObjectId = req.user._id;
        } else if (req.user.hospital) {
            hospitalObjectId = req.user.hospital;
        } else {
            return sendError(res, 403, 'Not authorized to view hospital statistics');
        }

        // 2. Aggregate counts by role, excluding admins
        const stats = await User.aggregate([
            {
                $match: {
                    hospital: hospitalObjectId,
                    role: { $nin: ['super_admin', 'admin'] }
                }
            },
            {
                $group: {
                    _id: '$role',
                    count: { $sum: 1 }
                }
            }
        ]);

        const formattedStats = stats.reduce((acc, curr) => {
            acc[curr._id] = curr.count;
            return acc;
        }, {});

        // 3. Get all userIds for users in this hospital
        //    (Includes hospital itself + all staff/patients tagged under it)
        const hospitalUsers = await User.find({
            $or: [
                { _id: hospitalObjectId },                 // the hospital entity itself
                { hospital: hospitalObjectId }             // all users under it
            ]
        }).select('userId');

        const hospitalUserIds = hospitalUsers.map(u => String(u.userId));

        // 4. Counts: users + consults (via hospital field OR participant ref_numbers)
        //    This handles old records (no hospital field) and new records equally.
        const [activeCount, totalCount, consultCount] = await Promise.all([
            User.countDocuments({ hospital: hospitalObjectId, isActive: true, role: { $nin: ['super_admin', 'admin'] } }),
            User.countDocuments({ hospital: hospitalObjectId, role: { $nin: ['super_admin', 'admin'] } }),
            Consult.countDocuments({
                $or: [
                    { hospital: hospitalObjectId },                                            // new consults with hospital field
                    { 'participants.ref_number': { $in: hospitalUserIds } }  // old consults matched by userId
                ]
            })
        ]);

        sendSuccess(res, 200, 'Hospital user statistics fetched successfully', {
            hospitalId: hospitalObjectId,
            byRole: formattedStats,
            activeCount,
            totalCount,
            consultCount
        });
    } catch (err) {
        next(err);
    }
};

// @desc    Get my subordinates (logged-in user's direct reports)
// @route   GET /api/v1/users/my-subordinates
// @access  Private
exports.getMySubordinates = async (req, res, next) => {
    try {
        const subordinates = await User.find({ reportingTo: req.user._id })
            .populate([
                { path: 'reportingTo', select: 'userId firstName lastName role' },
                { path: 'hospital', select: 'userId firstName lastName role' },
                { path: 'professional', select: 'userId firstName lastName role' }
            ])
            .sort({ createdAt: -1 });

        sendSuccess(res, 200, 'My subordinates fetched successfully', {
            count: subordinates.length,
            subordinates
        });
    } catch (err) {
        next(err);
    }
};

// @desc    Assign user under another user
// @route   PUT /api/v1/users/:id/assign
// @access  Private
exports.assignUser = async (req, res, next) => {
    try {
        const { reportingTo, hospitalId, professionalId } = req.body;

        const updateData = { updatedBy: req.user._id };

        if (reportingTo) {
            const supervisor = await User.findOne({ userId: parseInt(reportingTo) });
            if (supervisor) updateData.reportingTo = supervisor._id;
        }

        if (hospitalId) {
            const h = await User.findOne({ userId: parseInt(hospitalId), role: 'hospital' });
            if (h) updateData.hospital = h._id;
        }

        if (professionalId) {
            const p = await User.findOne({ userId: parseInt(professionalId) });
            const professionalRoles = ['psychiatrist', 'psychologist', 'nurse', 'social_worker', 'counselor'];
            if (p && professionalRoles.includes(p.role)) updateData.professional = p._id;
        }

        const user = await User.findOneAndUpdate(
            { userId: parseInt(req.params.id) },
            updateData,
            { returnDocument: 'after' }
        ).populate([
            { path: 'reportingTo', select: 'userId firstName lastName role' },
            { path: 'hospital', select: 'userId firstName lastName role' },
            { path: 'professional', select: 'userId firstName lastName role' }
        ]);

        if (!user) {
            return sendError(res, 404, 'User not found');
        }

        sendSuccess(res, 200, 'User mapping updated successfully', user);
    } catch (err) {
        next(err);
    }
};

// @desc    Get subordinates (users under a specific user)
// @route   GET /api/v1/users/:id/subordinates
// @access  Private
exports.getSubordinates = async (req, res, next) => {
    try {
        const user = await User.findOne({ userId: parseInt(req.params.id) });

        if (!user) {
            return sendError(res, 404, 'User not found');
        }

        const subordinates = await User.find({ reportingTo: user._id })
            .populate('reportingTo', 'userId firstName lastName role')
            .sort({ role: 1, firstName: 1 });

        sendSuccess(res, 200, `Subordinates of ${user.firstName} ${user.lastName}`, {
            supervisor: {
                userId: user.userId,
                firstName: user.firstName,
                lastName: user.lastName,
                role: user.role
            },
            subordinates,
            count: subordinates.length
        });
    } catch (err) {
        next(err);
    }
};

// @desc    Get full hierarchy tree
// @route   GET /api/v1/users/hierarchy
// @access  Private
exports.getHierarchy = async (req, res, next) => {
    try {
        const allUsers = await User.find()
            .populate('reportingTo', 'userId firstName lastName role')
            .sort({ userId: 1 });

        // Build tree
        const buildTree = (parentId = null) => {
            return allUsers
                .filter(u => {
                    if (parentId === null) return !u.reportingTo;
                    return u.reportingTo && u.reportingTo._id.toString() === parentId.toString();
                })
                .map(u => ({
                    userId: u.userId,
                    name: `${u.firstName} ${u.lastName}`,
                    role: u.role,
                    isActive: u.isActive,
                    subordinates: buildTree(u._id)
                }));
        };

        const hierarchy = buildTree();

        sendSuccess(res, 200, 'User hierarchy fetched successfully', hierarchy);
    } catch (err) {
        next(err);
    }
};

/**
 * @desc    Toggle user verification status
 * @route   PUT /api/v1/users/:id/toggle-verification
 * @access  Private (Super Admin)
 */
exports.toggleVerification = async (req, res, next) => {
    try {
        const user = await User.findById(req.params.id);

        if (!user) {
            return sendError(res, 404, 'User not found');
        }

        user.isVerified = !user.isVerified;
        user.verifiedAt = user.isVerified ? Date.now() : null;
        await user.save();

        sendSuccess(res, 200, `User verification set to ${user.isVerified}`, user);
    } catch (err) {
        next(err);
    }
};

/**
 * @desc    Get comprehensive patient view (Profile + History)
 * @route   GET /api/v1/users/patient-view
 * @access  Private (Patient)
 */
exports.getPatientComprehensiveView = async (req, res, next) => {
    try {
        const patientObjectId = req.user._id;
        const Assessment = require('../models/Assessment');
        const Consult = require('../models/Consult');

        // 1. Get Basic Profile
        const user = await User.findById(patientObjectId).select('firstName lastName email phone gender dateOfBirth profileImage address bloodGroup createdAt');

        if (!user) {
            return sendError(res, 404, 'Patient profile not found');
        }

        // 2. Get Professional History: Consultations
        // We match by the patient's userId in participants.ref_number
        const consultations = await Consult.find({
            'participants.ref_number': String(req.user.userId)
        }).sort({ scheduled_at: -1 });

        const mappedConsultations = consultations.map(c => {
            // Find the professional/provider in participants
            const provider = c.participants.find(p => p.role === 'publisher' || p.participant_info?.role === 'specialist');
            
            return {
                id: c._id,
                consultId: c.consultId,
                date: c.scheduled_at,
                type: c.consult_type,
                reason: c.reason,
                status: c.consult_current_status?.name || c.consult_status?.name,
                provider: provider ? provider.participant_info?.name : 'Unknown Specialist',
                hasPrescription: !!(c.clinical_record?.prescription?.medications?.length > 0)
            };
        });

        // 3. Get Professional History: Assessments
        const assessments = await Assessment.find({ user: patientObjectId }).sort({ createdAt: -1 });

        const mappedAssessments = assessments.map(a => ({
            id: a._id,
            assessmentId: a.assessmentId,
            category: a.category,
            wellnessAspect: a.wellnessAspect || a.category,
            isSelfAssessment: a.isSelfAssessment,
            recordedBy: a.isSelfAssessment ? 'Patient' : 'Specialist',
            date: a.createdAt,
            score: a.totalScore,
            percentage: a.percentage,
            interpretation: a.clinicalResults ? Array.from(a.clinicalResults.values())[0]?.interpretation : 'Completed'
        }));

        sendSuccess(res, 200, 'Comprehensive patient view fetched', {
            profile: user,
            history: {
                consultations: mappedConsultations,
                assessments: mappedAssessments
            }
        });
    } catch (err) {
        next(err);
    }
};

/**
 * @desc    Get detailed professional history for a patient (Staff View)
 * @route   GET /api/v1/users/professional-history/:id
 * @access  Private (Staff)
 */
exports.getProfessionalHistory = async (req, res, next) => {
    try {
        const patientId = parseInt(req.params.id);
        const Assessment = require('../models/Assessment');
        const Consult = require('../models/Consult');
        const PastHistory = require('../models/PastHistory');

        // 1. Get Patient Profile
        const patient = await User.findOne({ userId: patientId });
        if (!patient) {
            return sendError(res, 404, 'Patient not found');
        }

        // 2. Get consultations involving this patient
        const consultations = await Consult.find({
            'participants.ref_number': String(patientId)
        }).sort({ scheduled_at: -1 });

        const mappedConsultations = consultations.map(c => {
            const provider = c.participants.find(p => p.role === 'publisher' || p.participant_info?.role === 'specialist');
            return {
                id: c._id,
                consultId: c.consultId,
                date: c.scheduled_at,
                type: c.consult_type,
                reason: c.reason,
                status: c.consult_current_status?.name || 'Scheduled',
                provider: provider ? provider.participant_info?.name : 'Unknown Specialist',
                clinicalNotesPreview: c.clinical_record?.hpi?.ai_summary || null
            };
        });

        // 3. Get professional assessments for this patient
        const assessments = await Assessment.find({ user: patient._id }).sort({ createdAt: -1 });
        const mappedAssessments = assessments.map(a => ({
            id: a._id,
            assessmentId: a.assessmentId,
            category: a.category,
            isSelfAssessment: a.isSelfAssessment,
            recordedBy: a.isSelfAssessment ? 'Patient' : 'Specialist',
            date: a.createdAt,
            score: a.totalScore,
            interpretation: a.clinicalResults ? Array.from(a.clinicalResults.values())[0]?.interpretation : 'Completed'
        }));

        // 4. Get latest Past History (A-Z)
        const pastHistory = await PastHistory.findOne({ patient: patient._id }).sort({ createdAt: -1 });

        sendSuccess(res, 200, 'Professional history fetched successfully', {
            patient: {
                userId: patient.userId,
                name: `${patient.firstName} ${patient.lastName}`,
                gender: patient.gender,
                age: patient.dateOfBirth ? (new Date().getFullYear() - new Date(patient.dateOfBirth).getFullYear()) : null
            },
            clinical_summary: {
                past_history: pastHistory ? {
                    id: pastHistory._id,
                    diagnoses: pastHistory.psychiatric_history?.previous_diagnosis || [],
                    risk_flags: pastHistory.risk_flags || [],
                    color_code: pastHistory.color_code
                } : null,
                consultations_count: consultations.length,
                assessments_count: assessments.length
            },
            history: {
                consultations: mappedConsultations,
                assessments: mappedAssessments
            }
        });
    } catch (err) {
        next(err);
    }
};
