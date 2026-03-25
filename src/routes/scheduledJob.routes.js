const express = require('express');
const router = express.Router();
const jobController = require('../controllers/scheduledJob.controller');
const { protect, authorize } = require('../middleware/auth');

router.use(protect);
router.use(authorize('super_admin'));

router.get('/', jobController.getJobs);
router.put('/:name/toggle', jobController.toggleJob);

module.exports = router;
