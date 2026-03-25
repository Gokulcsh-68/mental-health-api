const User = require('../models/User');
const ChiefComplaint = require('../models/ChiefComplaint');
const HPI = require('../models/HPI');
const ROS = require('../models/ROS');
const PastHistory = require('../models/PastHistory');
const MSE = require('../models/MSE');
const Symptom = require('../models/Symptom');
const Consult = require('../models/Consult');
const openAIService = require('../services/OpenAIService');
const logger = require('../config/logger');

/**
 * Calculate age from date of birth
 */
const calcAge = (dob) => {
    if (!dob) return null;
    const diff = Date.now() - new Date(dob).getTime();
    return Math.floor(diff / (1000 * 60 * 60 * 24 * 365.25));
};

/**
 * @desc    Get full patient clinical summary (Chief Complaints → MSE)
 * @route   GET /api/v1/patients/:patientId/clinical-summary
 * @access  Private
 * @param   patientId — numeric userId or MongoDB ObjectId
 */
exports.getClinicalSummary = async (req, res) => {
    try {
        const { patientId } = req.params;
        const { consult_id } = req.query;

        // Resolve patient
        let patient;
        if (/^[0-9a-fA-F]{24}$/.test(String(patientId))) {
            patient = await User.findById(patientId).select('userId firstName lastName gender dateOfBirth profileImage bloodGroup');
        } else {
            patient = await User.findOne({ userId: patientId }).select('userId firstName lastName gender dateOfBirth profileImage bloodGroup');
        }

        if (!patient) {
            return res.status(404).json({ code: 404, message: `Patient ${patientId} not found`, data: null });
        }

        // Security: Patient can only view their own clinical summary
        if (req.user.role === 'patient' && patient._id.toString() !== req.user._id.toString()) {
            return res.status(403).json({ code: 403, message: 'Not authorized to view this clinical summary' });
        }

        const patientObjectId = patient._id;
        const filter = consult_id
            ? { patient: patientObjectId, consult_id }
            : { patient: patientObjectId };

        // Fetch all clinical modules in parallel
        const [chiefComplaints, hpis, ros, pastHistory, mse, symptoms] = await Promise.all([
            ChiefComplaint.find(filter).sort({ createdAt: -1 }),
            HPI.find(filter).sort({ createdAt: -1 }),
            ROS.find(filter).sort({ createdAt: -1 }),
            PastHistory.find(filter).sort({ createdAt: -1 }),
            MSE.find(filter).sort({ createdAt: -1 }),
            Symptom.find(filter).sort({ createdAt: -1 })
        ]);

        const age = calcAge(patient.dateOfBirth);

        res.status(200).json({
            code: 200,
            message: 'Patient clinical summary retrieved successfully',
            data: {
                patient: {
                    id: patient._id,
                    userId: patient.userId,
                    name: `${patient.firstName} ${patient.lastName}`,
                    gender: patient.gender || null,
                    age: age,
                    date_of_birth: patient.dateOfBirth || null,
                    blood_group: patient.bloodGroup || null,
                    profile_image: patient.profileImage || null
                },
                summary: {
                    total_modules_completed: [
                        chiefComplaints.length > 0,
                        hpis.length > 0,
                        ros.length > 0,
                        pastHistory.length > 0,
                        mse.length > 0,
                        symptoms.length > 0
                    ].filter(Boolean).length,
                    modules_status: {
                        chief_complaints: chiefComplaints.length > 0 ? 'completed' : 'pending',
                        hpi: hpis.length > 0 ? 'completed' : 'pending',
                        ros: ros.length > 0 ? 'completed' : 'pending',
                        past_history: pastHistory.length > 0 ? 'completed' : 'pending',
                        mse: mse.length > 0 ? 'completed' : 'pending',
                        symptoms: symptoms.length > 0 ? 'completed' : 'pending'
                    },
                    // Overall risk — highest color_code across all modules
                    overall_color_code: resolveHighestRisk([
                        ...chiefComplaints.map(d => d.color_code),
                        ...hpis.map(d => d.color_code),
                        ...ros.map(d => d.color_code),
                        ...pastHistory.map(d => d.color_code),
                        ...mse.map(d => d.color_code),
                        ...symptoms.map(d => d.color_code)
                    ])
                },
                clinical_data: {
                    chief_complaints: chiefComplaints,
                    hpi: hpis,
                    ros: ros,
                    past_history: pastHistory,
                    mse: mse,
                    symptoms: symptoms
                }
            }
        });

    } catch (err) {
        logger.error('Clinical Summary Error: %s', err.message);
        res.status(500).json({ code: 500, message: err.message, data: null });
    }
};

/**
 * Resolve the highest severity color code across all modules
 */
const COLOR_PRIORITY = ['#E53935', '#FB8C00', '#FDD835', '#4CAF50'];
const resolveHighestRisk = (codes = []) => {
    const valid = codes.filter(Boolean);
    if (!valid.length) return '#4CAF50';
    for (const color of COLOR_PRIORITY) {
        if (valid.includes(color)) return color;
    }
    return '#4CAF50';
};

/**
 * @desc    Generate AI Clinical Inference (Differential Diagnosis)
 * @route   POST /api/v1/patients/:patientId/clinical-inference
 * @access  Private
 */
exports.generateClinicalInference = async (req, res) => {
    try {
        const { patientId } = req.params;
        const { consult_id } = req.query;

        // Resolve patient
        let patient;
        if (/^[0-9a-fA-F]{24}$/.test(String(patientId))) {
            patient = await User.findById(patientId).select('_id userId firstName lastName gender dateOfBirth');
        } else {
            patient = await User.findOne({ userId: patientId }).select('_id userId firstName lastName gender dateOfBirth');
        }

        if (!patient) {
            return res.status(404).json({ code: 404, message: `Patient ${patientId} not found`, data: null });
        }

        // Security: Patient can only generate inferences for themselves
        if (req.user.role === 'patient' && patient._id.toString() !== req.user._id.toString()) {
            return res.status(403).json({ code: 403, message: 'Not authorized to generate clinical inference for this patient' });
        }

        const patientObjectId = patient._id;
        const filter = consult_id ? { patient: patientObjectId, consult_id } : { patient: patientObjectId };

        // Fetch all clinical data
        const [chiefComplaints, hpis, ros, pastHistory, mse, symptoms] = await Promise.all([
            ChiefComplaint.find(filter).sort({ createdAt: -1 }).limit(1),
            HPI.find(filter).sort({ createdAt: -1 }).limit(1),
            ROS.find(filter).sort({ createdAt: -1 }).limit(1),
            PastHistory.find(filter).sort({ createdAt: -1 }).limit(1),
            MSE.find(filter).sort({ createdAt: -1 }).limit(1),
            Symptom.find(filter).sort({ createdAt: -1 }).limit(1)
        ]);

        const clinicalData = {
            chief_complaint: chiefComplaints[0] || null,
            hpi: hpis[0] || null,
            ros: ros[0] || null,
            past_history: pastHistory[0] || null,
            mse: mse[0] || null,
            symptoms: symptoms[0] || null
        };

        const age = calcAge(patient.dateOfBirth);

        // Generate inference
        const aiInference = await openAIService.analyzeClinicalInference(clinicalData, { age, gender: patient.gender });


        // Optionally update consult record if consult_id is provided
        if (consult_id) {
            // aiInference now contains: differential_diagnosis, rule_outs, criteria_matched, criteria_missing, red_flag_alerts, risk_stratification
            const consult = await Consult.findOneAndUpdate(
                { consult_id: consult_id },
                { $set: { "clinical_record.ai_inference": aiInference } },
                { new: true }
            );

            // Trigger Automated Psychiatrist Alert if risk is High or Critical OR if Red Flags are found
            const redFlagAlerts = aiInference.red_flag_alerts || [];
            const isHighRisk = aiInference.risk_stratification?.level === 'High' || aiInference.risk_stratification?.level === 'Critical';

            if (isHighRisk || redFlagAlerts.length > 0) {
                const AlertService = require('../services/AlertService');

                // Find psychiatrist assigned to this consult or patient
                const psychiatrist = await User.findOne({ role: 'psychiatrist' });

                if (psychiatrist) {
                    const patientInfo = { id: patient._id, name: `${patient.firstName} ${patient.lastName}` };

                    // 1. Trigger High Risk/Relapse Alert if applicable
                    if (isHighRisk) {
                        await AlertService.triggerRelapseAlert(
                            psychiatrist._id,
                            patientInfo,
                            {
                                relapse_probability: aiInference.risk_stratification.score,
                                risk_level: aiInference.risk_stratification.level,
                                primary_drivers: redFlagAlerts
                            }
                        );
                    }

                    // 2. Trigger Specific Red Flag Alert if symptoms present
                    if (redFlagAlerts.length > 0) {
                        await AlertService.triggerRedFlagAlert(
                            psychiatrist._id,
                            patientInfo,
                            redFlagAlerts
                        );
                    }
                }
            }
        }

        res.status(200).json({
            code: 200,
            message: 'Clinical inference generated successfully',
            data: aiInference
        });

    } catch (err) {
        logger.error('Generate Clinical Inference Error: %s', err.message);
        res.status(500).json({ code: 500, message: err.message, data: null });
    }
};
