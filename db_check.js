const mongoose = require('mongoose');
const MONGO_URI = 'mongodb+srv://gokulvarthanr_db_user:MHApi2026@cluster0.76xgrys.mongodb.net/mental_health_db';

async function run() {
  try {
    await mongoose.connect(MONGO_URI);
    console.log('Connected to MongoDB');

    // Query Patient model
    const Patient = require('./src/models/Patient');
    const User = require('./src/models/User');

    console.log('--- PATIENTS COUNT ---');
    const count = await Patient.countDocuments();
    console.log('Total Patients:', count);

    console.log('--- LISTING FIRST 10 PATIENTS ---');
    const patients = await Patient.find({}).limit(10);
    patients.forEach(p => {
      console.log(`patient_id: ${p.patient_id}, user_id: ${p.user_id}, _id: ${p._id}`);
    });

    console.log('--- LOOKING FOR patient_id 16 ---');
    const p16 = await Patient.findOne({ patient_id: 16 });
    console.log('Patient with patient_id 16:', p16);

    console.log('--- LOOKING FOR user_id 16 (if user_id is a number) ---');
    const u16 = await User.findOne({ userId: 16 });
    console.log('User with userId 16:', u16);
    if (u16) {
      const pByU16 = await Patient.findOne({ user_id: u16._id });
      console.log('Patient associated with User 16 ObjectId:', pByU16);
    }

    await mongoose.disconnect();
  } catch (err) {
    console.error('Error:', err);
  }
}

run();
