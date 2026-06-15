// Helper script to fetch a patient ID from MongoDB
// Run with: node test/fetchFirstPatientId.js
// Ensure your .env has MONGO_URI defined (same as app)

require('dotenv').config();
const mongoose = require('mongoose');
const Patient = require('../src/models/Patient');

(async () => {
  try {
    await mongoose.connect(process.env.MONGO_URI);
    const patient = await Patient.findOne();
    if (patient) {
      console.log('First patient ID:', patient._id.toString());
    } else {
      console.log('No patient records found');
    }
    await mongoose.disconnect();
  } catch (err) {
    console.error('Error:', err);
    process.exit(1);
  }
})();
