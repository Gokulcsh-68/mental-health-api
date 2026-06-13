const express = require('express');
const router = express.Router();
const groupTherapyController = require('../controllers/groupTherapy.controller');

// Create a new group
router.post('/', groupTherapyController.createGroup);

// Get group details
router.get('/:groupId', groupTherapyController.getGroup);

// Manage members
router.post('/:groupId/members', groupTherapyController.addMember);
router.delete('/:groupId/members/:memberId', groupTherapyController.removeMember);

// Sessions
router.post('/:groupId/sessions', groupTherapyController.createSession);
router.post('/:groupId/sessions/:sessionId/attendance', groupTherapyController.recordAttendance);
router.post('/:groupId/sessions/:sessionId/notes', groupTherapyController.addSessionNote);

// Outcome measures
router.post('/:groupId/outcomes', groupTherapyController.addOutcome);

module.exports = router;
