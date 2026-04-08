const express = require('express');
const {
    getTaxCodes,
    getTaxCodeById,
    createTaxCode,
    updateTaxCode,
    deleteTaxCode
} = require('../controllers/taxCode.controller');
const { protect, authorize } = require('../middleware/auth');

const router = express.Router();

/**
 * @route   GET /api/v1/tax-codes
 * @desc    Get all tax codes (with filters & pagination)
 * @access  Private
 */
router.get('/', protect, getTaxCodes);

/**
 * @route   GET /api/v1/tax-codes/:id
 * @desc    Get single tax code by ID
 * @access  Private
 */
router.get('/:id', protect, getTaxCodeById);

/**
 * @route   POST /api/v1/tax-codes
 * @desc    Create a new tax code
 * @access  Private (Admin)
 */
router.post('/', protect, authorize('super_admin', 'admin'), createTaxCode);

/**
 * @route   PUT /api/v1/tax-codes/:id
 * @desc    Update a tax code
 * @access  Private (Admin)
 */
router.put('/:id', protect, authorize('super_admin', 'admin'), updateTaxCode);

/**
 * @route   PATCH /api/v1/tax-codes/:id
 * @desc    Update a tax code (partial)
 * @access  Private (Admin)
 */
router.patch('/:id', protect, authorize('super_admin', 'admin'), updateTaxCode);

/**
 * @route   DELETE /api/v1/tax-codes/:id
 * @desc    Delete a tax code
 * @access  Private (Admin)
 */
router.delete('/:id', protect, authorize('super_admin', 'admin'), deleteTaxCode);

module.exports = router;
