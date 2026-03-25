const express = require('express');
const router = express.Router();
const auditLogController = require('../controllers/auditLog.controller');
const { protect, authorize } = require('../middleware/auth');

router.use(protect);
router.use(authorize('super_admin'));

router.get('/', auditLogController.getAuditLogs);
router.get('/:id', auditLogController.getAuditLogById);

module.exports = router;
