const Diagnosis = require('../models/Diagnosis');
const openAIService = require('../services/OpenAIService');
const Consult = require('../models/Consult');
const User = require('../models/User');
const { sendSuccess, sendError } = require('../utils/responseHelper');
const notificationService = require('../services/notificationService');
const logger = require('../config/logger');

// GET diagnosis by consult_id
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
    const { consult_id, condition } = req.body || {};
    if (!consult_id) return sendError(res, 400, 'consult_id is required');

    const consult = await Consult.findOne({ consult_id: parseInt(consult_id) });
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

/** GET diagnosis history for a user */
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

/** GET latest diagnosis for a user by user_id (protected) */
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

    // Fetch all diagnoses for this user, sorted newest first
    const diagnoses = await Diagnosis.find({ patientId: user._id })
      .sort({ createdAt: -1 })
      .lean();

    if (!diagnoses || diagnoses.length === 0) {
      return sendError(res, 404, 'Diagnosis not found');
    }

    const results = diagnoses.map(d => ({
      diagnosis: d.diagnosis,
      prescription: d.prescription,
      narrative: d.narrative || '',
      created_at: d.createdAt
    }));

    return sendSuccess(res, 200, 'All diagnoses retrieved', results);
  } catch (err) {
    next(err);
  }
};
