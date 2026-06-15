// src/routes/medication.routes.js
const express = require('express');
const { protect } = require('../middleware/auth');
const {
  getMedications,
  getMedicationCategories,
  getMedicationById,
  createMedication,
  updateMedication,
  deleteMedication,
} = require('../controllers/medication.controller');

const router = express.Router();

// Public (protected) read routes
router.get('/', protect, getMedications);
router.get('/categories', protect, getMedicationCategories);
router.get('/:id', protect, getMedicationById);

// Admin/modification routes (still protected; add role checks if needed)
router.post('/', protect, createMedication);
router.put('/:id', protect, updateMedication);
router.delete('/:id', protect, deleteMedication);

module.exports = router;
