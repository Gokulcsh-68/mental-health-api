const mongoose = require('mongoose');

const MasterSchema = new mongoose.Schema({
    masterId: {
        type: Number,
        unique: true
    },
    name: {
        type: String,
        required: [true, 'Please add a name'],
        trim: true
    },
    slug: {
        type: String,
        required: [true, 'Please add a slug'],
        unique: true,
        lowercase: true,
        trim: true
    },
    master_type_slug: {
        type: String,
        required: true,
        default: 'general'
    },
    attributes: {
        type: mongoose.Schema.Types.Mixed,
        default: {}
    },
    minAge: {
        type: Number,
        default: 18
    },
    maxAge: {
        type: Number,
        default: 120
    },
    gender: {
        type: String,
        enum: ['male', 'female', 'all'],
        default: 'all'
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
}, { minimize: false });

// Auto-increment ID
const Counter = require('./Counter');
MasterSchema.pre('save', async function () {
    if (!this.isNew) return;

    const counter = await Counter.findByIdAndUpdate(
        { _id: 'masterId' },
        { $inc: { seq: 1 } },
        { new: true, upsert: true }
    );
    this.masterId = counter.seq;
});

module.exports = mongoose.model('Master', MasterSchema);
