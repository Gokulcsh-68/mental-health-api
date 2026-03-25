const express = require('express');
const { getMasters, getAllMasters } = require('../controllers/resource.controller');
const {
    createConsult,
    getConsults,
    getConsultById,
    patchConsult,
    rescheduleConsult,
    cancelConsult,
    addClinicalNotes,
    getClinicalRecord,
    updateChiefComplaints,
    getChiefComplaints,
    getConsultBilling,
    generateInvoice
} = require('../controllers/consult.controller');

const { protect } = require('../middleware/auth');

const router = express.Router();

router.get('/masters/all', protect, getAllMasters);
router.get('/masters/all/:userId', protect, getAllMasters);
router.get('/masters', protect, getMasters);
router.get('/masters/:userId', protect, getMasters);

// Consult routes (mapped to /api/v1/resource/consults)
router.route('/consults')
    .post(protect, createConsult)
    .get(protect, getConsults);

router.route('/consults/:id')
    .get(protect, getConsultById)
    .patch(protect, patchConsult);

router.patch('/consults/:id/reschedule', protect, rescheduleConsult);
router.patch('/consults/:id/cancel', protect, cancelConsult);
router.post('/consults/:id/notes', protect, addClinicalNotes);
router.get('/consults/:id/clinical-record', protect, getClinicalRecord);
router.route('/consults/:id/chief-complaints')
    .post(protect, updateChiefComplaints)
    .get(protect, getChiefComplaints);
router.get('/consults/:id/billing', protect, getConsultBilling);
router.post('/consults/:id/invoice', protect, generateInvoice);

module.exports = router;
