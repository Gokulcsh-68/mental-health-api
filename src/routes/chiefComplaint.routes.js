const express = require('express');
const {
    extractChiefComplaint,
    confirmChiefComplaint,
    getChiefComplaints,
    getChiefComplaintById,
    overrideChiefComplaint,
    deleteChiefComplaint
} = require('../controllers/chiefComplaint.controller');
const { protect, authorize } = require('../middleware/auth');
const { upload } = require('../services/S3Service');
const auditLog = require('../middleware/audit');

const router = express.Router();

/**
 * @route   POST /api/v1/chief-complaints/extract
 * @desc    Submit narrative → AI extraction → return preview (NO database save)
 * @body    { patient_id, narrative }
 * @access  Private
 */
router.post('/extract', protect, extractChiefComplaint);

/**
 * @route   POST /api/v1/chief-complaints
 * @desc    Save the reviewed/confirmed chief complaint to the database
 * @body    { consult_id, patient_id, narrative, ai_summary, structured, risk_markers,
 *            previous_episodes, color_code, voice_recording_url?, transcription_language?,
 *            transcription_confidence?, ai_extraction_metadata? }
 * @access  Private
 */
router.post('/', protect, upload.none(), auditLog('WRITE', 'ChiefComplaint'), confirmChiefComplaint);

/**
 * @route   GET /api/v1/chief-complaints?consult_id=X&page=1&limit=10
 * @desc    List all chief complaints (optionally filtered by consult_id)
 * @access  Private
 */
router.get('/', protect, auditLog('READ', 'ChiefComplaint'), getChiefComplaints);

/**
 * @route   GET /api/v1/chief-complaints/:ccId
 * @desc    Get a single chief complaint by chiefComplaintId
 * @access  Private
 */
router.get('/:ccId', protect, getChiefComplaintById);

/**
 * @route   PATCH /api/v1/chief-complaints/:ccId
 * @desc    Doctor manually overrides / corrects AI-extracted fields
 * @body    { narrative?, structured?, risk_markers?, previous_episodes?, override_notes? }
 * @access  Private (Specialist)
 */
router.patch('/:ccId', protect, authorize('psychiatrist', 'psychologist', 'super_admin'), auditLog('OVERRIDE', 'ChiefComplaint'), overrideChiefComplaint);

/**
 * @route   DELETE /api/v1/chief-complaints/:ccId
 * @desc    Delete a chief complaint record
 * @access  Private (Specialist / Admin)
 */
router.delete('/:ccId', protect, authorize('psychiatrist', 'super_admin'), deleteChiefComplaint);

module.exports = router;
