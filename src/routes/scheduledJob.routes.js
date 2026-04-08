const express = require('express');
const router = express.Router();
const jobController = require('../controllers/scheduledJob.controller');
const { protect, authorize } = require('../middleware/auth');

router.use(protect);
router.use(authorize('super_admin'));

router.get('/', jobController.getJobs);
router.post('/', jobController.createJob);
router.put('/:name/toggle', jobController.toggleJob);
router.delete('/:id', jobController.deleteJob);

module.exports = router;
