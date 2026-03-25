const express = require('express');
const {
    getApiAccessRules,
    createApiAccessRule,
    getApiAccessRule,
    updateApiAccessRule,
    deleteApiAccessRule
} = require('../controllers/apiAccess.controller');
const { protect, authorize } = require('../middleware/auth');

const router = express.Router();

// Only Super Admin should manage API access
router.use(protect);
router.use(authorize('super_admin'));

router.route('/')
    .get(getApiAccessRules)
    .post(createApiAccessRule);

router.route('/:id')
    .get(getApiAccessRule)
    .put(updateApiAccessRule)
    .delete(deleteApiAccessRule);

module.exports = router;
