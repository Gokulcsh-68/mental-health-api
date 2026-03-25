const express = require('express');
const {
    getUserInfo,
    updateProfile,
    getUserById,
    updateUser,
    deleteUser,
    toggleUserStatus,
    createUserByRole,
    listUsers,
    getUserStats,
    getHospitalUserStats,
    getMySubordinates,
    assignUser,
    getSubordinates,
    getHierarchy,
    toggleVerification,
    getPatientComprehensiveView,
    getProfessionalHistory
} = require('../controllers/user.controller');
const { protect, authorize } = require('../middleware/auth');
const { upload } = require('../services/S3Service');

const router = express.Router();

// All user routes require authentication
router.use(protect);

// Current user profile
router.get('/info', getUserInfo);
router.get('/patient-view', authorize('patient', 'super_admin'), getPatientComprehensiveView);
router.get('/professional-history/:id', authorize('psychiatrist', 'psychologist', 'nurse', 'social_worker', 'counselor', 'hospital', 'admin', 'super_admin'), getProfessionalHistory);
router.put('/update-me', upload.single('profileImage'), updateProfile);

// Statistics and Subordinates
router.get('/hospital-stats', authorize('super_admin', 'admin', 'hospital'), getHospitalUserStats);
router.get('/stats', authorize('super_admin', 'admin', 'hospital'), getUserStats);
router.get('/my-subordinates', authorize('psychiatrist', 'psychologist', 'nurse', 'social_worker', 'counselor', 'hospital', 'admin', 'super_admin'), getMySubordinates);
router.get('/hierarchy', authorize('super_admin', 'admin', 'hospital', 'psychiatrist', 'psychologist'), getHierarchy);

// General listing/search
router.get('/list', authorize('super_admin', 'admin', 'hospital', 'psychiatrist', 'psychologist', 'nurse', 'social_worker', 'counselor', 'patient'), listUsers);

// CRUD and Specific Actions by userId
router.get('/:id', authorize('super_admin', 'admin', 'hospital', 'psychiatrist', 'psychologist', 'nurse', 'social_worker', 'counselor'), getUserById);
router.put('/:id', authorize('super_admin', 'admin', 'hospital', 'psychiatrist', 'psychologist'), upload.single('profileImage'), updateUser);
router.delete('/:id', authorize('super_admin', 'admin', 'hospital', 'psychiatrist', 'psychologist'), deleteUser);
router.put('/:id/toggle-status', authorize('super_admin', 'admin', 'hospital'), toggleUserStatus);
router.put('/:id/toggle-verification', authorize('super_admin'), toggleVerification);
router.put('/:id/assign', authorize('super_admin', 'admin', 'hospital', 'psychiatrist', 'psychologist', 'nurse', 'social_worker', 'counselor'), assignUser);
router.get('/:id/subordinates', authorize('super_admin', 'admin', 'hospital', 'psychiatrist', 'psychologist'), getSubordinates);

// Create by role
router.post('/:role', authorize('super_admin', 'admin', 'hospital', 'psychiatrist', 'psychologist', 'nurse', 'social_worker', 'counselor'), createUserByRole);

module.exports = router;
