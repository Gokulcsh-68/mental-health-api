const express = require('express');
const { sendSuccess } = require('../utils/responseHelper');
const router = express.Router();

router.get('/', (req, res) => {
    sendSuccess(res, 200, 'Mental Health API is running', {
        timestamp: new Date().toISOString()
    });
});

module.exports = router;
