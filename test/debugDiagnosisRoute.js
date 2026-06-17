require('dotenv').config();
const express = require('express');
const mongoose = require('mongoose');
const User = require('../src/models/User');
const Patient = require('../src/models/Patient');
const Diagnosis = require('../src/models/Diagnosis');
const { getDiagnosisHistory } = require('../src/controllers/diagnosis.controller');

async function runTest() {
  await mongoose.connect(process.env.MONGO_URI);
  console.log('Connected to DB');

  // Mock Request and Response
  const req = {
    query: {
      user_id: '3' // Replace with a valid user_id
    }
  };

  const res = {
    status: function(code) {
      this.statusCode = code;
      return this;
    },
    json: function(data) {
      console.log('Response Status:', this.statusCode);
      console.log('Response Data:', JSON.stringify(data, null, 2));
    }
  };

  const next = (err) => {
    console.error('Next called with error:', err);
  };

  console.log('Testing getDiagnosisHistory controller directly...');
  await getDiagnosisHistory(req, res, next);

  await mongoose.disconnect();
  console.log('Disconnected from DB');
}

runTest().catch(console.error);
