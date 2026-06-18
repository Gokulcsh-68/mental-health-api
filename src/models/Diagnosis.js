const mongoose = require('mongoose');

const DiagnosisSchema = new mongoose.Schema(
  {
    patientId: {
      type: mongoose.Schema.Types.ObjectId,
      ref: 'Patient',
      required: true,
      index: true
    },

    patient_id: {
      type: Number,
      required: true,
      index: true
    },

    diagnosis: {
      primary: {
        type: String,
        required: true
      },

      secondary: {
        type: String
      },

      severity: {
        type: String,
        enum: ['Mild', 'Moderate', 'Severe']
      },

      details: {
        type: String
      },

      recommendations: [{
        type: String
      }]
    },

    prescription: [
      {
        name: {
          type: String,
          required: true
        },

        dose: {
          type: String,
          required: true
        },

        duration: {
          type: String,
          required: true
        },

        instructions: {
          type: String
        }
      }
    ],

    aiGenerated: {
      type: Boolean,
      default: true
    },

    diagnosisDate: {
      type: Date,
      default: Date.now
    },

    createdBy: {
      type: mongoose.Schema.Types.ObjectId,
      ref: 'User'
    }
  },
  {
    timestamps: true
  }
);

module.exports = mongoose.model(
  'Diagnosis',
  DiagnosisSchema
);