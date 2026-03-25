const express = require('express');
const { createRequest, getMyRequests, getSentRequests, getRequestById, cancelRequest } = require('../controllers/professionalRequest.controller');
const { protect, authorize } = require('../middleware/auth');

const router = express.Router();

router.use(protect);

// Professionals (Staff) can create and view sent requests
router.post('/', authorize('psychiatrist', 'psychologist', 'nurse', 'social_worker', 'counselor', 'hospital', 'admin', 'super_admin'), createRequest);
router.get('/sent-requests', authorize('psychiatrist', 'psychologist', 'nurse', 'social_worker', 'counselor', 'hospital', 'admin', 'super_admin'), getSentRequests);

// Patients can view their pending requests
router.get('/my-requests', authorize('patient'), getMyRequests);

// Get single request details
router.get('/:requestId', getRequestById);

// Professionals can cancel their requests
router.patch('/:requestId/cancel', authorize('psychiatrist', 'psychologist', 'nurse', 'social_worker', 'counselor', 'hospital', 'admin', 'super_admin'), cancelRequest);

module.exports = router;
