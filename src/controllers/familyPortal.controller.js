const User = require('../models/User');
const Consult = require('../models/Consult');
const logger = require('../config/logger');

/**
 * @desc    Get Patient clinical data for a family member (based on consent)
 * @route   GET /api/v1/family-portal/patients/:patientId
 * @access  Private/Family
 */
exports.getPatientDataForFamily = async (req, res) => {
    try {
        const { patientId } = req.params;
        const familyMemberId = req.user._id;

        // Resolve patient and check for family consent
        const patient = await User.findById(patientId).select('firstName lastName family_consents');
        if (!patient) {
            return res.status(404).json({ code: 404, message: 'Patient not found', data: null });
        }

        const consent = patient.family_consents.find(c =>
            c.familyUserId.toString() === familyMemberId.toString() && c.is_active
        );

        if (!consent) {
            return res.status(403).json({ code: 403, message: 'No active consent found for this family member', data: null });
        }

        // Fetch the latest consult
        const consult = await Consult.findOne({ patient: patientId }).sort({ createdAt: -1 });
        if (!consult) {
            return res.status(404).json({ code: 404, message: 'No clinical records found for this patient', data: null });
        }

        // Filter data based on consented modules
        const filteredData = {
            patient_name: `${patient.firstName} ${patient.lastName}`,
            last_checked: consult.createdAt
        };

        if (consent.modules.includes('prescriptions')) {
            filteredData.prescription = consult.clinical_record.prescription;
        }
        if (consent.modules.includes('follow_up')) {
            filteredData.follow_up = consult.clinical_record.follow_up;
        }
        if (consent.modules.includes('clinical_record')) {
            filteredData.clinical_summary = {
                risk_level: consult.clinical_record.ai_inference.risk_stratification.level,
                diagnosis_summary: consult.clinical_record.diagnosis.primary.condition
            };
        }

        res.status(200).json({
            code: 200,
            message: 'Patient data retrieved for family member',
            data: filteredData
        });
    } catch (err) {
        logger.error('Family Portal Error: %s', err.message);
        res.status(500).json({ code: 500, message: err.message, data: null });
    }
};
