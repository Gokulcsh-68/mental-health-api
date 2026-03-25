const express = require('express');
const router = express.Router();
const { getPatientDataForFamily } = require('../controllers/familyPortal.controller');
const { protect, authorize } = require('../middleware/auth');
const validateApiKey = require('../middleware/apiKey');

router.use(validateApiKey);
router.use(protect);
router.use(authorize('family', 'super_admin'));

router.get('/patients/:patientId', getPatientDataForFamily);

module.exports = router;
