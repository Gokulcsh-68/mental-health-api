const mongoose = require('mongoose');

const DiagnosisSchema = new mongoose.Schema({
  consultId: { type: mongoose.Schema.Types.ObjectId, ref: 'Consult', required: true, index: true },
  diagnosis: {
    primary: { type: String, required: true },
    secondary: { type: String },
    severity: { type: String, enum: ['Mild', 'Moderate', 'Severe'] }
  },
  prescription: [{
    name: { type: String, required: true },
    dose: { type: String, required: true },
    duration: { type: String, required: true },
    instructions: { type: String }
  }],
  createdAt: { type: Date, default: Date.now },
  updatedAt: { type: Date, default: Date.now }
});

module.exports = mongoose.model('Diagnosis', DiagnosisSchema);
