// src/models/Patient.js
const mongoose = require('mongoose');

const PatientSchema = new mongoose.Schema(
  {
    patient_id: {
      type: Number,
      required: true,
      unique: true,
      index: true,
    },
    // Basic demographic info
    age: { type: Number },
    gender: { type: String, enum: ['Male', 'Female', 'Other'] },
    // Clinical data used by the diagnosis controller
    symptoms: { type: [String], default: [] },
    vitals: { type: mongoose.Schema.Types.Mixed, default: {} },
    allergies: { type: [String], default: [] },
    medications: { type: [String], default: [] },
    medical_history: { type: [String], default: [] },
    assessments: { type: [String], default: [] },
    // Link to the user that owns this patient record
    user_id: { type: mongoose.Schema.Types.ObjectId, ref: 'User' },
  },
  { timestamps: true }
);

module.exports = mongoose.model('Patient', PatientSchema);
