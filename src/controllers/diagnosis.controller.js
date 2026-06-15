const Diagnosis = require('../models/Diagnosis');
const Patient = require('../models/Patient');
const User = require('../models/User');
const openAIService = require('../services/OpenAIService');
const { sendSuccess, sendError } = require('../utils/responseHelper');
const notificationService = require('../services/notificationService');
const logger = require('../config/logger');


// POST /api/v1/diagnosis
exports.getDiagnosis = async (req, res, next) => {
  try {
    const { patient_id } = req.params;

    const diagnosis = await Diagnosis.findOne({
      patient_id: parseInt(patient_id)
    }).sort({ createdAt: -1 });

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
      summary += `${i + 1}. ${med.name || med} - ${
        med.dosage || 'dosage not specified'
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
    const { patient_id } = req.params;

    const diagnosis = await Diagnosis.findOne({
      patient_id: parseInt(patient_id)
    }).sort({ createdAt: -1 });

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
    const { patient_id, condition } = req.body || {};

    if (!patient_id) {
      return sendError(res, 400, 'patient_id is required');
    }

    // const Patient = require('../models/Patient'); // Duplicate import removed
    const patient = await Patient.findOne({
      patient_id: parseInt(patient_id)
    });

    if (!patient) {
      return sendError(res, 404, 'Patient not found');
    }

    const clinicalData = {
      symptoms: patient.symptoms || [],
      vitals: patient.vitals || {},
      allergies: patient.allergies || [],
      medications: patient.medications || [],
      medical_history: patient.medical_history || [],
      assessments: patient.assessments || [],
      condition
    };

    const aiResult =
      await openAIService.analyzeClinicalInference(
        clinicalData,
        {
          age: patient.age,
          gender: patient.gender
        }
      );

    const diagnosis = aiResult.diagnosis;
    const prescription =
      aiResult.prescription?.medications || [];

    const newDiagnosis = await Diagnosis.create({
      patientId: patient._id,
      patient_id: patient.patient_id,
      diagnosis,
      prescription,
      createdBy: req.user?._id
    });

    await Patient.updateOne(
      {
        patient_id: parseInt(patient_id)
      },
      {
        $set: {
          latestDiagnosis: diagnosis,
          latestPrescription: prescription,
          diagnosisUpdatedAt: new Date()
        }
      }
    );

    try {
      if (patient.user_id) {
        const user = await User.findById(
          patient.user_id
        );

        if (user) {
          await notificationService.notify({
            userId: user._id,
            title: 'New Diagnosis Added',
            message:
              diagnosis?.primary ||
              'New diagnosis generated',
            type: 'clinical',
            data: {
              patient_id
            }
          });
        }
      }
    } catch (notificationError) {
      logger.warn(
        `Diagnosis notification failed: ${notificationError.message}`
      );
    }

    return sendSuccess(
      res,
      201,
      'Diagnosis created successfully',
      newDiagnosis
    );
  } catch (err) {
    next(err);
  }
};

/** GET AI diagnosis without persisting */
exports.getAIDiagnosis = async (req, res, next) => {
  try {
    const { patient_id } = req.params;

    if (!patient_id) {
      return sendError(
        res,
        400,
        'patient_id is required'
      );
    }


    const patient = await Patient.findOne({
      patient_id: parseInt(patient_id)
    });

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
    const { patient_id, symptoms, condition } = req.body || {};

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

    // Otherwise, require patient_id and fetch patient data
    if (!patient_id) {
      return sendError(res, 400, 'patient_id or symptoms is required');
    }

    let patient;
    try {

      patient = await Patient.findOne({ patient_id: parseInt(patient_id) });
    } catch (e) {
      return sendError(res, 500, 'Patient model unavailable');
    }
    if (!patient) {
      return sendError(res, 404, 'Patient not found');
    }

    const clinicalData = {
      symptoms: patient.symptoms || [],
      vitals: patient.vitals || {},
      allergies: patient.allergies || [],
      medications: patient.medications || [],
      medical_history: patient.medical_history || [],
      assessments: patient.assessments || [],
      condition
    };
    const aiResult = await openAIService.analyzeClinicalInference(clinicalData, {
      age: patient.age,
      gender: patient.gender
    });
    return sendSuccess(res, 200, 'AI diagnosis generated', {
      diagnosis: aiResult.diagnosis,
      prescription: aiResult.prescription?.medications || []
    });
  } catch (err) {
    next(err);
  }
};
