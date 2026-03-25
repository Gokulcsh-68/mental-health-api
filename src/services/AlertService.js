const socketService = require('./socketService');
const logger = require('../config/logger');

/**
 * Alert Service for critical clinical events
 */
class AlertService {
    /**
     * Broadcast a critical relapse risk alert to the assigned psychiatrist
     * @param {Object} psychiatristId - MongoDB ObjectId or ID
     * @param {Object} patient - { id, name }
     * @param {Object} prediction - { relapse_probability, risk_level, primary_drivers }
     */
    static async triggerRelapseAlert(psychiatristId, patient, prediction) {
        const alertData = {
            type: 'CRITICAL_RELAPSE_RISK',
            severity: prediction.risk_level,
            message: `URGENT: High relapse probability (${prediction.relapse_probability}%) detected for patient ${patient.name}.`,
            patient_id: patient.id,
            drivers: prediction.primary_drivers,
            timestamp: new Date().toISOString()
        };

        logger.warn(`[RELAPSE ALERT] Psychiatrist: ${psychiatristId} | Patient: ${patient.name} | Risk: ${prediction.risk_level}`);

        // Emit via Socket.io for real-time dashboard update
        socketService.emitToUser(psychiatristId.toString(), 'clinical_alert', alertData);

        // Integrate with notification service for push, email, and SMS
        const { notify } = require('./notificationService');
        await notify({
            userId: psychiatristId,
            title: `Critical Alert: ${patient.name}`,
            message: alertData.message,
            type: 'alert',
            data: {
                patientId: patient.id,
                alertType: 'RELAPSE_RISK',
                riskLevel: prediction.risk_level
            }
        });
    }

    /**
     * Broadcast a specific Red Flag alert (Self-harm, Violence, Psychosis)
     * @param {Object} psychiatristId - MongoDB ObjectId or ID
     * @param {Object} patient - { id, name }
     * @param {Array} redFlags - Array of detected symptoms
     */
    static async triggerRedFlagAlert(psychiatristId, patient, redFlags) {
        const symptoms = redFlags.join(', ');
        const alertData = {
            type: 'RED_FLAG_DETECTION',
            severity: 'Critical',
            message: `RED FLAG DETECTED: [${symptoms}] found for patient ${patient.name}. Immediate review required.`,
            patient_id: patient.id,
            symptoms: redFlags,
            timestamp: new Date().toISOString()
        };

        logger.error(`[RED FLAG ALERT] Psychiatrist: ${psychiatristId} | Patient: ${patient.name} | Symptoms: ${symptoms}`);

        // Emit via Socket.io
        socketService.emitToUser(psychiatristId.toString(), 'clinical_alert', alertData);

        // Notify via all channels
        const { notify } = require('./notificationService');
        await notify({
            userId: psychiatristId,
            title: `🚩 RED FLAG: ${patient.name}`,
            message: alertData.message,
            type: 'alert',
            data: {
                patientId: patient.id,
                alertType: 'RED_FLAG',
                symptoms: redFlags
            }
        });
    }
}

module.exports = AlertService;
