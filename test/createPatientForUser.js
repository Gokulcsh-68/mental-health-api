// Script to create a patient linked to user with userId 3
require('dotenv').config();
const mongoose = require('mongoose');
const User = require('../src/models/User');
const Patient = require('../src/models/Patient');

async function main() {
  await mongoose.connect(process.env.MONGO_URI);
  const user = await User.findOne({ userId: 3 });
  if (!user) {
    console.error('User with userId 3 not found');
    process.exit(1);
  }
  // Check if patient already exists
  let patient = await Patient.findOne({ user_id: user._id });
  if (patient) {
    console.log('Patient already exists:', patient);
    await mongoose.disconnect();
    return;
  }
  // Create a new patient
  patient = await Patient.create({
    patient_id: 1,
    user_id: user._id,
    age: 30,
    gender: 'Male',
    symptoms: [],
    vitals: {},
    allergies: [],
    medications: [],
    medical_history: [],
    assessments: []
  });
  console.log('Created patient:', patient);
  await mongoose.disconnect();
}

main().catch(err => {
  console.error('Error:', err);
  process.exit(1);
});
