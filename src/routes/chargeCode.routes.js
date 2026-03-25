const express = require('express');
const {
    getChargeCodes,
    getChargeCodeById,
    createChargeCode,
    updateChargeCode,
    deleteChargeCode
} = require('../controllers/chargeCode.controller');
const { protect, authorize } = require('../middleware/auth');

const router = express.Router();

/**
 * @route   GET /api/v1/charge-codes
 * @desc    Get all charge codes (filter by specialist_id, is_active, search)
 * @access  Private
 */
router.get('/', protect, getChargeCodes);

/**
 * @route   GET /api/v1/charge-codes/:id
 * @desc    Get single charge code by ID (with tax codes expanded)
 * @access  Private
 */
router.get('/:id', protect, getChargeCodeById);

/**
 * @route   POST /api/v1/charge-codes
 * @desc    Create a new charge code linked to a specialist
 * @access  Private (Admin)
 */
router.post('/', protect, authorize('super_admin', 'admin'), createChargeCode);

/**
 * @route   PUT /api/v1/charge-codes/:id
 * @desc    Update a charge code
 * @access  Private (Admin)
 */
router.put('/:id', protect, authorize('super_admin', 'admin'), updateChargeCode);

/**
 * @route   DELETE /api/v1/charge-codes/:id
 * @desc    Delete a charge code
 * @access  Private (Admin)
 */
router.delete('/:id', protect, authorize('super_admin', 'admin'), deleteChargeCode);

module.exports = router;
