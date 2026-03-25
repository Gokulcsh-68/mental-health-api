const express = require('express');
const router = express.Router();
const mseController = require('../controllers/mse.controller');
const { protect } = require('../middleware/auth');

router.get('/questions', protect, mseController.getMSEQuestions);

router.route('/')
    .post(protect, mseController.createMSE)
    .get(protect, mseController.getMSE);

router.get('/:id', protect, mseController.getMSEById);

module.exports = router;
