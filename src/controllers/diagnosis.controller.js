const Diagnosis = require('../models/Diagnosis');
const openAIService = require('../services/OpenAIService');
const Consult = require('../models/Consult');
const User = require('../models/User');
const { sendSuccess, sendError } = require('../utils/responseHelper');
const notificationService = require('../services/notificationService');
const logger = require('../config/logger');


// POST /api/v1/diagnosis
exports.getDiagnosis = async (req, res, next) => {
  try {
    const { consult_id } = req.params;
    const diagnosis = await Diagnosis.findOne({ consultId: parseInt(consult_id) });
    if (!diagnosis) return sendError(res, 404, 'Diagnosis not found');
    sendSuccess(res, 200, 'Diagnosis retrieved', { diagnosis: diagnosis.diagnosis, prescription: diagnosis.prescription });
  } catch (err) {
    next(err);
  }
};
// Helper to generate a user‑friendly summary
function formatFriendly(diagnosis, prescription) {
  let summary = `🩺 **Diagnosis:** ${diagnosis?.primary || 'N/A'}\n`;
  if (diagnosis?.details) {
    summary += `\n> ${diagnosis.details}\n`;
  }
  if (Array.isArray(prescription) && prescription.length) {
    summary += `\n💊 **Prescribed Medications:**\n`;
    prescription.forEach((med, i) => {
      summary += `${i + 1}. ${med.name || med} - ${med.dosage || 'dosage not specified'}\n`;
    });
  } else {
    summary += `\n💊 No prescription recommended.`;
  }
  return summary;
}

// GET friendly diagnosis for end‑users
exports.getFriendlyDiagnosis = async (req, res, next) => {
  try {
    const { consult_id } = req.params;
    const diagnosis = await Diagnosis.findOne({ consultId: parseInt(consult_id) });
    if (!diagnosis) return sendError(res, 404, 'Diagnosis not found');
    const friendly = formatFriendly(diagnosis.diagnosis, diagnosis.prescription);
    sendSuccess(res, 200, 'Friendly diagnosis retrieved', { summary: friendly });
  } catch (err) {
    next(err);
  }
};

exports.createDiagnosis = async (req, res, next) => {
  try {
    const { consult_id, condition } = req.body || {};
    if (!consult_id) return sendError(res, 400, 'consult_id is required');

    const consult = await Consult.findOne({ consult_id: parseInt(consult_id) });
    if (!consult) return sendError(res, 404, 'Consultation not found');

    // Gather clinical data for AI inference
    const clinicalData = {
      clinical_record: consult.clinical_record,
      notes: consult.notes,
      participants: consult.participants,
      condition: condition
    };

    // Call AI service to generate diagnosis and prescription
    const aiResult = await openAIService.analyzeClinicalInference(clinicalData, { age: consult.age, gender: consult.gender });
    const diagnosis = aiResult.diagnosis;
    const prescription = aiResult.prescription?.medications || [];

    const newDiag = await Diagnosis.create({
      consultId: consult._id,
      diagnosis,
      prescription
    });

    // Optionally embed reference in consult for quick access
    await Consult.updateOne({ consult_id: parseInt(consult_id) }, {
      $set: {
        'clinical_record.diagnosis': diagnosis,
        'clinical_record.prescription': prescription,
        updatedAt: new Date()
      }
    });

    // Notify patient if possible
    try {
      const patientRef = consult.participants.find(p => p.role === 'subscriber');
      if (patientRef) {
        const patient = await User.findOne({ userId: parseInt(patientRef.ref_number) });
        if (patient) {
          await notificationService.notify({
            userId: patient._id,
            title: 'New Diagnosis Added',
            message: `Your consultation has a new diagnosis: ${diagnosis.primary}`,
            type: 'clinical',
            data: { consult_id }
          });
        }
      }
    } catch (nerr) {
      logger.warn('Notification failed after adding diagnosis: ' + nerr.message);
    }

    sendSuccess(res, 201, 'Diagnosis created successfully', newDiag);
  } catch (err) {
    next(err);
  }
};

/** GET AI diagnosis without persisting */
exports.getAIDiagnosis = async (req, res, next) => {
  try {
    const { consult_id } = req.params;
    if (!consult_id) return sendError(res, 400, 'consult_id is required');
    const consult = await Consult.findOne({ consult_id: parseInt(consult_id) });
    if (!consult) return sendError(res, 404, 'Consultation not found');
    const clinicalData = {
      clinical_record: consult.clinical_record,
      notes: consult.notes,
      participants: consult.participants
    };
    const aiResult = await openAIService.analyzeClinicalInference(clinicalData, { age: consult.age, gender: consult.gender });
    const diagnosis = aiResult.diagnosis;
    const prescription = aiResult.prescription?.medications || [];
    sendSuccess(res, 200, 'AI diagnosis generated', { diagnosis, prescription });
  } catch (err) {
    next(err);
  }
};

/** AI-only diagnosis & prescription */
exports.aiDiagnose = async (req, res, next) => {
  try {
    const { consult_id, patient_id, condition } = req.body || {};
    if (!consult_id && !patient_id) return sendError(res, 400, 'consult_id or patient_id is required');
    let consult;
    if (consult_id) {
      consult = await Consult.findOne({ consult_id: parseInt(consult_id) });
    } else {
      // Find consult where a participant with role 'subscriber' matches patient_id
      consult = await Consult.findOne({
        participants: { $elemMatch: { role: 'subscriber', ref_number: String(patient_id) } }
      });
    }
    if (!consult) return sendError(res, 404, 'Consultation not found');

    const clinicalData = {
      clinical_record: consult.clinical_record,
      notes: consult.notes,
      participants: consult.participants,
      condition: condition
    };

    const aiResult = await openAIService.analyzeClinicalInference(clinicalData, { age: consult.age, gender: consult.gender });
    const diagnosis = aiResult.diagnosis;
    const prescription = aiResult.prescription?.medications || [];

    // Return without persisting
    sendSuccess(res, 200, 'AI diagnosis generated', { diagnosis, prescription });
  } catch (err) {
    next(err);
  }
};
