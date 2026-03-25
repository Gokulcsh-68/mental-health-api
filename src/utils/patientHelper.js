const User = require('../models/User');

/**
 * @desc  Resolve a patient by userId (number) or MongoDB ObjectId
 *        Returns { patient, resolvedId, age, gender }
 */
const resolvePatient = async (patient_id) => {
    let patient;
    if (/^[0-9a-fA-F]{24}$/.test(String(patient_id))) {
        patient = await User.findById(patient_id)
            .select('_id userId dateOfBirth gender firstName lastName');
    } else {
        patient = await User.findOne({ userId: patient_id })
            .select('_id userId dateOfBirth gender firstName lastName');
    }

    if (!patient) return null;

    const dob = patient.dateOfBirth;
    const age = dob
        ? Math.floor((Date.now() - new Date(dob).getTime()) / (1000 * 60 * 60 * 24 * 365.25))
        : null;

    return {
        patient,
        resolvedId: patient._id,
        age,
        gender: patient.gender || null
    };
};

module.exports = { resolvePatient };
