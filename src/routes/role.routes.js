const express = require('express');
const {
    getRoles,
    createRole,
    getRole,
    updateRole,
    deleteRole
} = require('../controllers/role.controller');
const { protect, authorize } = require('../middleware/auth');

const router = express.Router();

// Only Super Admin should manage roles
router.use(protect);
router.use(authorize('super_admin'));

router.route('/')
    .get(getRoles)
    .post(createRole);

router.route('/:id')
    .get(getRole)
    .put(updateRole)
    .delete(deleteRole);

module.exports = router;
