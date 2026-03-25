const express = require('express');
const router = express.Router();
const { getSettings, getSettingByKey, updateSetting } = require('../controllers/systemSetting.controller');
const { protect, authorize } = require('../middleware/auth');

// Public access for specific settings (like app_name or support_email)
router.get('/:key', getSettingByKey);

// Restricted to Super Admin
router.use(protect);
router.use(authorize('super_admin'));

router.get('/', getSettings);
router.post('/', updateSetting);

module.exports = router;
