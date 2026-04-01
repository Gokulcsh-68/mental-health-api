const mongoose = require('mongoose');
const Counter = require('./Counter');

const TaxCodeSchema = new mongoose.Schema({
    taxCodeId: {
        type: Number,
        unique: true
    },
    code: {
        type: String,
        required: [true, 'Please add a tax code'],
        unique: true,
        uppercase: true,
        trim: true
    },
    name: {
        type: String,
        required: [true, 'Please add a name'],
        trim: true
    },
    rate: {
        type: Number,
        required: [true, 'Please add a tax rate'],
        min: [0, 'Rate cannot be negative'],
        max: [100, 'Rate cannot exceed 100']
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
TaxCodeSchema.pre('save', async function () {
    if (!this.isNew) return;

    const counter = await Counter.findByIdAndUpdate(
        { _id: 'taxCodeId' },
        { $inc: { seq: 1 } },
        { returnDocument: 'after', upsert: true }
    );
    this.taxCodeId = counter.seq;
});

module.exports = mongoose.model('TaxCode', TaxCodeSchema);
