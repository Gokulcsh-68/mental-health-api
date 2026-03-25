const express = require('express');
const {
    extractHistoryOfIllness,
    confirmHistoryOfIllness,
    getHistoryOfIllnesses,
    getHistoryOfIllnessById,
    updateHistoryOfIllness,
    deleteHistoryOfIllness
} = require('../controllers/historyOfIllness.controller');
const { protect, authorize } = require('../middleware/auth');
const { upload } = require('../services/S3Service');
const auditLog = require('../middleware/audit');

const router = express.Router();

/**
 * @route   POST /api/v1/history-of-illness/extract
 * @desc    Submit narrative → AI extraction → return preview (NO database save)
 * @access  Private
 */
router.post('/extract', protect, extractHistoryOfIllness);

/**
 * @route   POST /api/v1/history-of-illness
 * @desc    Save the reviewed/confirmed HPI to the database
 * @access  Private
 */
router.post('/', protect, upload.none(), auditLog('WRITE', 'HistoryOfIllness'), confirmHistoryOfIllness);

/**
 * @route   GET /api/v1/history-of-illness
 * @desc    List all HPI records
 * @access  Private
 */
router.get('/', protect, auditLog('READ', 'HistoryOfIllness'), getHistoryOfIllnesses);

/**
 * @route   GET /api/v1/history-of-illness/:hpiId
 * @desc    Get a single HPI record
 * @access  Private
 */
router.get('/:hpiId', protect, getHistoryOfIllnessById);

/**
 * @route   PATCH /api/v1/history-of-illness/:hpiId
 * @desc    Doctor manually overrides / corrects AI-extracted fields
 * @access  Private (Specialist)
 */
router.patch('/:hpiId', protect, authorize('psychiatrist', 'psychologist', 'super_admin'), auditLog('OVERRIDE', 'HistoryOfIllness'), updateHistoryOfIllness);

/**
 * @route   DELETE /api/v1/history-of-illness/:hpiId
 * @desc    Delete an HPI record
 * @access  Private (Specialist / Admin)
 */
router.delete('/:hpiId', protect, authorize('psychiatrist', 'super_admin'), deleteHistoryOfIllness);

module.exports = router;
