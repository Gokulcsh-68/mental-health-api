const express = require('express');
const { register, login, getMe, forgotPassword, resetPassword, changePassword, refreshToken, logout } = require('../controllers/auth.controller');
const { protect } = require('../middleware/auth');

const router = express.Router();

// Public routes
router.post('/register', register);
router.post('/login', login);
router.post('/refresh-token', refreshToken);
router.post('/logout', logout);
router.post('/forgot-password', forgotPassword);
router.put('/reset-password/:token', resetPassword);

// Private routes (require login token)
router.get('/me', protect, getMe);
router.put('/change-password', protect, changePassword);

module.exports = router;
