const express = require('express');
const {
    getSpecialists,
    getSpecialistById,
    updateSpecialistProfile
} = require('../controllers/specialist.controller');
const { getSpecialistDirectory } = require('../controllers/specialistSchedule.controller');
const { protect } = require('../middleware/auth');

const router = express.Router();

/**
 * @route   GET /api/v1/specialists/directory
 * @desc    Get specialist directory with next slots
 * @access  Private
 */
router.get('/directory', protect, getSpecialistDirectory);

/**
 * @route   GET /api/v1/specialists
 * @desc    Get all specialists
 * @access  Private
 */
router.get('/', protect, getSpecialists);

/**
 * @route   GET /api/v1/specialists/:id
 * @desc    Get single specialist by ID
 * @access  Private
 */
router.get('/:id', protect, getSpecialistById);

/**
 * @route   PATCH /api/v1/specialists/profile
 * @desc    Update own specialist profile
 * @access  Private
 */
router.patch('/profile', protect, updateSpecialistProfile);

module.exports = router;
