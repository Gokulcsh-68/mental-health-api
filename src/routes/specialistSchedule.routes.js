const express = require('express');
const {
    getSchedules,
    getMySchedules,
    createMySchedule,
    upsertMyWeeklySchedule,
    getScheduleById,
    createSchedule,
    updateSchedule,
    deleteSchedule,
    getAvailableSlots,
    getSpecialistDirectory
} = require('../controllers/specialistSchedule.controller');
const { protect, optionalProtect, authorize } = require('../middleware/auth');

const clinicalRoles = ['psychiatrist', 'psychologist', 'nurse', 'social_worker', 'counselor'];
const adminRoles = ['admin', 'super_admin'];
const managementRoles = [...clinicalRoles, ...adminRoles];

const router = express.Router();

/**
 * @route   GET /api/v1/specialists/schedule
 * @desc    Get all schedule entries
 * @access  Private
 */
router.get('/', optionalProtect, getSchedules);

/**
 * @route   GET /api/v1/specialists/schedule/me
 * @desc    Get current user's schedules
 * @access  Private (Professional roles only)
 */
router.get('/me', protect, authorize(...clinicalRoles, ...adminRoles), getMySchedules);

/**
 * @route   POST /api/v1/specialists/schedule/me
 * @desc    Create schedule for current user
 * @access  Private (Professional roles only)
 */
router.post('/me', protect, authorize(...clinicalRoles, ...adminRoles), createMySchedule);

/**
 * @route   PUT /api/v1/specialists/schedule/me/weekly
 * @desc    Upsert weekly schedule for current user
 * @access  Private (Professional roles only)
 */
router.put('/me/weekly', protect, authorize(...clinicalRoles, ...adminRoles), upsertMyWeeklySchedule);

/**
 * @route   GET /api/v1/specialists/schedule/slots
 * @desc    Get available slots for a specialist
 * @access  Private
 */
router.get('/slots', optionalProtect, getAvailableSlots);


/**
 * @route   GET /api/v1/specialists/schedule/directory
 * @desc    Get specialist directory (Convenience route)
 * @access  Private
 */
router.get('/directory', optionalProtect, getSpecialistDirectory);

/**
 * @route   GET /api/v1/specialists/schedule/:id
 * @desc    Get single schedule entry by ID
 * @access  Private
 */
router.get('/:id', optionalProtect, getScheduleById);

/**
 * @route   POST /api/v1/specialists/schedule
 * @desc    Create a new schedule entry
 * @access  Private (Admin/Professional)
 */
router.post('/', protect, authorize(...managementRoles), createSchedule);

/**
 * @route   PUT /api/v1/specialists/schedule/:id
 * @desc    Update a schedule entry
 * @access  Private (Admin/Professional)
 */
router.put('/:id', protect, authorize(...managementRoles), updateSchedule);


/**
 * @route   DELETE /api/v1/specialists/schedule/:id
 * @desc    Delete a schedule entry
 * @access  Private (Admin/Professional)
 */
router.delete('/:id', protect, authorize(...managementRoles), deleteSchedule);

module.exports = router;
