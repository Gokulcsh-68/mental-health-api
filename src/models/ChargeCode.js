const mongoose = require('mongoose');
const Counter = require('./Counter');

const ChargeCodeSchema = new mongoose.Schema({
    chargeCodeId: {
        type: Number,
        unique: true
    },
    code: {
        type: String,
        required: [true, 'Please add a charge code'],
        unique: true,
        uppercase: true,
        trim: true
    },
    name: {
        type: String,
        required: [true, 'Please add a name'],
        trim: true
    },
    amount: {
        type: Number,
        required: [true, 'Please add the charge amount'],
        min: [0, 'Amount cannot be negative']
    },
    specialist_id: {
        type: Number,
        required: [true, 'Please add a specialist user ID'],
        index: true
    },
    specialist_role: {
        type: String,
        trim: true,
        default: null
    },
    tax_codes: {
        type: [Number],   // Array of taxCodeId values
        default: []
    },
    description: {
        type: String,
        default: null,
        trim: true
    },
    is_active: {
        type: Number,
        enum: [0, 1],
        default: 1
    },
    createdAt: {
        type: Date,
        default: Date.now
    }
});

// Auto-increment ID
ChargeCodeSchema.pre('save', async function () {
    if (!this.isNew) return;

    const counter = await Counter.findByIdAndUpdate(
        { _id: 'chargeCodeId' },
        { $inc: { seq: 1 } },
        { new: true, upsert: true }
    );
    this.chargeCodeId = counter.seq;
});

module.exports = mongoose.model('ChargeCode', ChargeCodeSchema);
