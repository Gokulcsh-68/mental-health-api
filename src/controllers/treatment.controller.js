const TreatmentStage = require('../models/TreatmentStage');
const { sendSuccess, sendError } = require('../utils/responseHelper');
const User = require('../models/User');

const DEFAULT_STAGES = [
    { stage: 'Onboarding', order: 1, status: 'completed' },
    { stage: 'Assessment', order: 2, status: 'pending' },
    { stage: 'Therapy', order: 3, status: 'pending' },
    { stage: 'Monitoring', order: 4, status: 'pending' },
    { stage: 'Completed', order: 5, status: 'pending' }
];

/**
 * @desc    Initialize treatment plan for a patient
 * @route   POST /api/v1/treatment/initialize
 * @access  Private (Professional/Hospital)
 */
exports.initializeTreatment = async (req, res, next) => {
    try {
        const { patientId, customStages } = req.body;

        if (!patientId) return sendError(res, 400, 'patientId is required');

        const patient = await User.findOne({ userId: parseInt(patientId) });
        if (!patient) return sendError(res, 404, 'Patient not found');

        const stages = customStages || DEFAULT_STAGES;

        const stageDocs = stages.map(s => ({
            ...s,
            patient: patient._id,
            updatedBy: req.user._id
        }));

        // Delete existing stages if any (re-initialize)
        await TreatmentStage.deleteMany({ patient: patient._id });
        const results = await TreatmentStage.insertMany(stageDocs);

        sendSuccess(res, 201, 'Treatment plan initialized successfully', results);
    } catch (err) {
        next(err);
    }
};

/**
 * @desc    Get treatment progress for a patient
 * @route   GET /api/v1/treatment/progress/:patientId
 * @access  Private (Patient/Professional/Hospital)
 */
exports.getPatientProgress = async (req, res, next) => {
    try {
        const { patientId } = req.params;
        const patient = await User.findOne({ userId: parseInt(patientId) });
        if (!patient) return sendError(res, 404, 'Patient not found');

        // RBAC: Patients can only see their own progress
        if (req.user.role === 'patient' && String(req.user._id) !== String(patient._id)) {
            return sendError(res, 403, 'Not authorized to view this progress');
        }

        const stages = await TreatmentStage.find({ patient: patient._id }).sort({ order: 1 });

        sendSuccess(res, 200, 'Treatment progress fetched successfully', stages);
    } catch (err) {
        next(err);
    }
};

/**
 * @desc    Update a treatment stage status
 * @route   PATCH /api/v1/treatment/:id
 * @access  Private (Professional/Hospital)
 */
exports.updateStageStatus = async (req, res, next) => {
    try {
        const { id } = req.params;
        const { status, notes, completedAt } = req.body;

        if (!status) return sendError(res, 400, 'Status is required');

        const stage = await TreatmentStage.findById(id);
        if (!stage) return sendError(res, 404, 'Treatment stage not found');

        // RBAC check: only pros/hospitals/admins
        if (!['psychiatrist', 'psychologist', 'nurse', 'social_worker', 'counselor', 'hospital', 'admin', 'super_admin'].includes(req.user.role)) {
            return sendError(res, 403, 'Only professionals can update treatment status');
        }

        stage.status = status;
        if (notes) stage.notes = notes;
        if (status === 'completed') {
            stage.completedAt = completedAt || new Date();
        }
        stage.updatedBy = req.user._id;

        await stage.save();

        sendSuccess(res, 200, 'Treatment stage updated successfully', stage);
    } catch (err) {
        next(err);
    }
};

/**
 * @desc    Add a new treatment plan (clinical impression, medications)
 * @route   POST /api/v1/treatment/plan
 * @access  Private (Professional/Hospital)
 */
exports.addTreatmentPlan = async (req, res, next) => {
    try {
        const { patientId, consultId, plan, medications, next_steps } = req.body;

        if (!patientId || !plan) {
            return sendError(res, 400, 'patientId and plan are required');
        }

        // Resolve patient ObjectId from numeric userId or exact ObjectId
        let patientDoc;
        if (/^[0-9a-fA-F]{24}$/.test(String(patientId))) {
            patientDoc = await User.findById(patientId);
        } else {
            patientDoc = await User.findOne({ userId: parseInt(patientId) });
        }

        if (!patientDoc) return sendError(res, 404, 'Patient not found');

        // RBAC check: only pros/hospitals/admins
        if (!['psychiatrist', 'psychologist', 'nurse', 'social_worker', 'counselor', 'hospital', 'admin', 'super_admin'].includes(req.user.role)) {
            return sendError(res, 403, 'Only professionals can create treatment plans');
        }

        const TreatmentPlan = require('../models/TreatmentPlan');
        
        const newPlan = await TreatmentPlan.create({
            patient: patientDoc._id,
            consultId: consultId ? parseInt(consultId) : undefined,
            plan,
            medications,
            next_steps,
            createdBy: req.user._id
        });

        // Notify patient optional...
        const notificationService = require('../services/notificationService');
        await notificationService.notify({
            userId: patientDoc._id,
            title: 'New Treatment Plan Added',
            message: `A new treatment plan has been added to your clinical record.`,
            type: 'general',
            data: { consult_id: consultId }
        }).catch(err => console.error('Notification error:', err.message));

        sendSuccess(res, 201, 'Treatment plan added successfully', newPlan);
    } catch (err) {
        next(err);
    }
};

/**
 * @desc    Get treatment plan history for a patient
 * @route   GET /api/v1/treatment/plan/history/:patientId
 * @access  Private
 */
exports.getTreatmentHistory = async (req, res, next) => {
    try {
        const { patientId } = req.params;

        let patientDoc;
        if (/^[0-9a-fA-F]{24}$/.test(String(patientId))) {
            patientDoc = await User.findById(patientId);
        } else {
            patientDoc = await User.findOne({ userId: parseInt(patientId) });
        }

        if (!patientDoc) return sendError(res, 404, 'Patient not found');

        // RBAC: Patients can only see their own history
        if (req.user.role === 'patient' && String(req.user._id) !== String(patientDoc._id)) {
            return sendError(res, 403, 'Not authorized to view this treatment history');
        }

        const TreatmentPlan = require('../models/TreatmentPlan');
        const history = await TreatmentPlan.find({ patient: patientDoc._id })
            .sort({ createdAt: -1 })
            .populate('createdBy', 'firstName lastName role');

        sendSuccess(res, 200, 'Treatment plan history fetched successfully', history);
    } catch (err) {
        next(err);
    }
};
