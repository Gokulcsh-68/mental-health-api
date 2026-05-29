const mongoose = require('mongoose');

const ChildAssessmentSchema = new mongoose.Schema({
  patient: { type: mongoose.Schema.Types.ObjectId, ref: 'User', required: true },
  responses: [
    {
      questionId: { type: Number, required: true },
      answer: { type: mongoose.Schema.Types.Mixed, required: true }
    }
  ],
  createdAt: { type: Date, default: Date.now }
});

module.exports = mongoose.model('ChildAssessment', ChildAssessmentSchema);
