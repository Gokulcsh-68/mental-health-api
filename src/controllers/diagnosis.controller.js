const Diagnosis = require('../models/Diagnosis');
const Patient = require('../models/Patient');
const User = require('../models/User');
const openAIService = require('../services/OpenAIService');
const { sendSuccess, sendError } = require('../utils/responseHelper');
const notificationService = require('../services/notificationService');
const logger = require('../config/logger');

// Helper to find patient by user_id ObjectId or numeric patient_id
const findPatient = async (targetId) => {
  if (!targetId) return null;

  // Normalize gender to match Patient schema enum: 'Male' | 'Female' | 'Other'
  const normalizeGender = (g) => {
    if (!g) return undefined;
    const lower = g.toLowerCase();
    if (lower === 'male') return 'Male';
    if (lower === 'female') return 'Female';
    return 'Other';
  };
  // If targetId looks like a Mongo ObjectId, treat it as a user_id reference
  const isObjectId = /^[0-9a-fA-F]{24}$/.test(String(targetId));
  if (isObjectId) {
    let patient = await Patient.findOne({ user_id: targetId });
    if (patient) return patient;
    const user = await User.findById(targetId);
    if (user) {
      const age = user.dateOfBirth
        ? Math.floor((Date.now() - new Date(user.dateOfBirth).getTime()) / (1000 * 60 * 60 * 24 * 365.25))
        : undefined;
      return await Patient.create({
        patient_id: user.userId || 1,
        user_id: user._id,
        age: age,
        gender: normalizeGender(user.gender),
        symptoms: [],
        vitals: {},
        allergies: [],
        medications: [],
        medical_history: [],
        assessments: []
      });
    }
    return null;
  }
  // Numeric id could be either patient_id or the auto‑incremented userId
  const numericId = parseInt(targetId);
  // First try finding a patient by patient_id
  let patient = await Patient.findOne({ patient_id: numericId });
  if (patient) return patient;
  // If not found, look up User by userId and then find patient by that user ObjectId
  const user = await User.findOne({ userId: numericId });
  if (!user) return null;
  
  patient = await Patient.findOne({ user_id: user._id });
  if (patient) return patient;

  const age = user.dateOfBirth
    ? Math.floor((Date.now() - new Date(user.dateOfBirth).getTime()) / (1000 * 60 * 60 * 24 * 365.25))
    : undefined;
  return await Patient.create({
    patient_id: user.userId,
    user_id: user._id,
    age: age,
    gender: normalizeGender(user.gender),
    symptoms: [],
    vitals: {},
    allergies: [],
    medications: [],
    medical_history: [],
    assessments: []
  });
};


// GET /api/v1/diagnosis/:consult_id
exports.getDiagnosis = async (req, res, next) => {
  try {
    const { consult_id } = req.params;
    // The route is /:consult_id, but the function finds patient using this param
    const patient = await findPatient(consult_id);
    if (!patient) { return sendError(res, 404, 'Patient not found'); }
    const diagnosis = await Diagnosis.findOne({ patientId: patient._id }).sort({ createdAt: -1 });

    if (!diagnosis) {
      return sendError(res, 404, 'Diagnosis not found');
    }

    return sendSuccess(
      res,
      200,
      'Diagnosis retrieved',
      {
        diagnosis: diagnosis.diagnosis,
        prescription: diagnosis.prescription
      }
    );
  } catch (err) {
    next(err);
  }
};
// Helper to generate a user‑friendly summary
function formatFriendly(diagnosis, prescription) {
  let summary = `🩺 Diagnosis: ${diagnosis?.primary || 'N/A'}\n`;

  if (diagnosis?.details) {
    summary += `\n${diagnosis.details}\n`;
  }

  if (Array.isArray(prescription) && prescription.length) {
    summary += `\n💊 Prescribed Medications:\n`;

    prescription.forEach((med, i) => {
      summary += `${i + 1}. ${med.name || med} - ${med.dosage || 'dosage not specified'
        }\n`;
    });
  } else {
    summary += '\n💊 No prescription recommended.';
  }

  return summary;
}

// GET friendly diagnosis for end‑users
exports.getFriendlyDiagnosis = async (req, res, next) => {
  try {
    const { user_id } = req.params;

    const patient = await findPatient(user_id);
    if (!patient) { return sendError(res, 404, 'Patient not found'); }
    const diagnosis = await Diagnosis.findOne({ patientId: patient._id }).sort({ createdAt: -1 });

    if (!diagnosis) {
      return sendError(res, 404, 'Diagnosis not found');
    }

    const summary = formatFriendly(
      diagnosis.diagnosis,
      diagnosis.prescription
    );

    return sendSuccess(
      res,
      200,
      'Friendly diagnosis retrieved',
      { summary }
    );
  } catch (err) {
    next(err);
  }
};

exports.createDiagnosis = async (req, res, next) => {
  try {
    const { user_id, condition } = req.body || {};
    if (!user_id) {
      return sendError(res, 400, 'user_id is required');
    }

    // Find the user directly – no Patient document required
    const user = await User.findById(user_id) || await User.findOne({ userId: parseInt(user_id) });
    if (!user) {
      return sendError(res, 404, 'User not found');
    }

    const clinicalData = { condition };

    // Perform AI analysis using user demographics
    const aiResult = await openAIService.analyzeClinicalInference(clinicalData, {
      age: user.dateOfBirth
        ? Math.floor((Date.now() - new Date(user.dateOfBirth).getTime()) / (1000 * 60 * 60 * 24 * 365.25))
        : undefined,
      gender: user.gender
    });

    const diagnosis = aiResult.diagnosis;
    const prescription = aiResult.prescription?.medications || [];

    // Persist the diagnosis – link directly to the User ID
    const newDiagnosis = await Diagnosis.create({
      patientId: user._id,
      patient_id: user.userId || 0,
      diagnosis,
      prescription,
      createdBy: req.user?._id
    });

    // Optional notification to the user
    try {
      await notificationService.notify({
        userId: user._id,
        title: 'New Diagnosis Added',
        message: diagnosis?.primary || 'New diagnosis generated',
        type: 'clinical',
        data: { patient_id: user.userId }
      });
    } catch (notificationError) {
      logger.warn(`Diagnosis notification failed: ${notificationError.message}`);
    }

    return sendSuccess(res, 201, 'Diagnosis created successfully', newDiagnosis);
  } catch (err) {
    next(err);
  }
};

/** GET AI diagnosis without persisting */
exports.getAIDiagnosis = async (req, res, next) => {
  try {
    const { user_id, consult_id } = req.params;
    const targetUserId = user_id || consult_id || req.body.user_id;

    if (!targetUserId) {
      return sendError(
        res,
        400,
        'user_id is required'
      );
    }


    const patient = await findPatient(targetUserId);

    if (!patient) {
      return sendError(res, 404, 'Patient not found');
    }

    const clinicalData = {
      symptoms: patient.symptoms || [],
      vitals: patient.vitals || {},
      allergies: patient.allergies || [],
      medications: patient.medications || [],
      medical_history: patient.medical_history || [],
      assessments: patient.assessments || []
    };

    const aiResult =
      await openAIService.analyzeClinicalInference(
        clinicalData,
        {
          age: patient.age,
          gender: patient.gender
        }
      );

    return sendSuccess(
      res,
      200,
      'AI diagnosis generated',
      {
        diagnosis: aiResult.diagnosis,
        prescription:
          aiResult.prescription?.medications || []
      }
    );
  } catch (err) {
    next(err);
  }
};

/** AI-only diagnosis & prescription */
exports.aiDiagnose = async (req, res, next) => {
  try {
    const { user_id, patient_id, symptoms, condition } = req.body || {};
    const targetUserId = user_id || patient_id;

    // If symptoms are provided directly, use them for AI diagnosis
    if (symptoms && (Array.isArray(symptoms) ? symptoms.length : symptoms.trim())) {
      const clinicalData = {
        symptoms: Array.isArray(symptoms) ? symptoms : [symptoms],
        condition
      };
      const aiResult = await openAIService.analyzeClinicalInference(clinicalData, {});
      return sendSuccess(res, 200, 'AI diagnosis generated', {
        diagnosis: aiResult.diagnosis,
        prescription: aiResult.prescription?.medications || []
      });
    }

    // Otherwise, require patient_id/user_id and fetch patient data
    if (!targetUserId) {
      return sendError(res, 400, 'patient_id or symptoms is required');
    }

    // Find the User first by userId
    let user;
    let patient;
    try {
      const isObjectId = /^[0-9a-fA-F]{24}$/.test(String(targetUserId));
      if (isObjectId) {
        user = await User.findById(targetUserId);
      } else {
        user = await User.findOne({ userId: parseInt(targetUserId) });
      }
    } catch (e) {
      logger.error('User lookup failed:', e);
      return sendError(res, 500, `User lookup failed: ${e.message}`);
    }
    if (!user) {
      return sendError(res, 404, 'User not found');
    }

    // Optionally fetch Patient record for extra clinical data
    try {
      patient = await Patient.findOne({ user_id: user._id });
    } catch (e) {
      logger.warn('Patient lookup failed, continuing without patient data:', e.message);
    }

    const narrative = req.body.narrative || '';
    const clinicalData = {
      symptoms: patient?.symptoms || [],
      vitals: patient?.vitals || {},
      allergies: patient?.allergies || [],
      medications: patient?.medications || [],
      medical_history: patient?.medical_history || [],
      assessments: patient?.assessments || [],
      narrative,
      condition
    };

    const age = patient?.age || (user.dateOfBirth
      ? Math.floor((Date.now() - new Date(user.dateOfBirth).getTime()) / (1000 * 60 * 60 * 24 * 365.25))
      : undefined);

    const aiResult = await openAIService.analyzeClinicalInference(clinicalData, {
      age,
      gender: user.gender
    });

    const diagnosisData = aiResult.diagnosis;
    const prescriptionData = aiResult.prescription?.medications || [];

    // Persist the AI diagnosis to history
    let saved;
    try {
      saved = await Diagnosis.create({
        patientId: patient?._id || user._id,
        patient_id: patient?.patient_id || user.userId || 0,
        diagnosis: diagnosisData,
        prescription: prescriptionData,
        narrative,
        aiGenerated: true,
        createdBy: req.user?._id
      });
    } catch (saveErr) {
      logger.warn('Failed to save AI diagnosis to history:', saveErr.message);
    }

    return sendSuccess(res, 200, 'AI diagnosis generated', {
      id: saved?._id,
      diagnosis: diagnosisData,
      prescription: prescriptionData,
      narrative,
      created_at: saved?.createdAt || new Date().toISOString()
    });
  } catch (err) {
    next(err);
  }
};

/** GET /api/v1/diagnosis/ai?user_id=... — Diagnosis history for a user */
exports.getDiagnosisHistory = async (req, res, next) => {
  try {
    const { user_id } = req.query;
    if (!user_id) {
      return sendError(res, 400, 'user_id query parameter is required');
    }

    // Resolve user directly – no Patient lookup needed
    const isObjectId = /^[0-9a-fA-F]{24}$/.test(String(user_id));
    const user = isObjectId ? await User.findById(user_id) : await User.findOne({ userId: parseInt(user_id) });
    if (!user) {
      return sendError(res, 404, 'User not found');
    }

    // Query diagnoses that belong to this user either by creator, numeric userId or direct reference
    const query = {
      $or: [
        { createdBy: user._id },
        { patient_id: user.userId },
        { patientId: user._id }
      ]
    };

    const diagnoses = await Diagnosis.find(query)
      .sort({ createdAt: -1 })
      .limit(50)
      .lean();

    const results = diagnoses.map(d => ({
      id: d._id,
      diagnosis: d.diagnosis,
      prescription: d.prescription,
      narrative: d.narrative || '',
      created_at: d.createdAt
    }));

    return sendSuccess(res, 200, 'Diagnosis history retrieved', results);
  } catch (err) {
    next(err);
  }
};

// GET latest diagnosis for a user by user_id (protected)
exports.getLatestDiagnosisByUser = async (req, res, next) => {
  try {
    const { user_id } = req.params;
    if (!user_id) {
      return sendError(res, 400, 'user_id parameter is required');
    }
    const isObjectId = /^[0-9a-fA-F]{24}$/.test(String(user_id));
    const user = isObjectId ? await User.findById(user_id) : await User.findOne({ userId: parseInt(user_id) });
    if (!user) {
      return sendError(res, 404, 'User not found');
    }
    const diagnosis = await Diagnosis.findOne({ patientId: user._id }).sort({ createdAt: -1 });
    if (!diagnosis) {
      return sendError(res, 404, 'Diagnosis not found');
    }
    return sendSuccess(res, 200, 'Latest diagnosis retrieved', {
      diagnosis: diagnosis.diagnosis,
      prescription: diagnosis.prescription,
      narrative: diagnosis.narrative || '',
      created_at: diagnosis.createdAt,
    });
  } catch (err) {
    next(err);
  }
};
