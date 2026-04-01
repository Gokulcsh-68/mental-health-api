const mongoose = require('mongoose');
const Counter = require('./Counter');

const InvoiceSchema = new mongoose.Schema({
    invoiceId: {
        type: Number,
        unique: true
    },
    consult_id: {
        type: Number,
        required: true,
        ref: 'Consult'
    },
    specialist_id: {
        type: Number,
        required: true,
        ref: 'User'
    },
    patient_id: {
        type: Number,
        required: true,
        ref: 'User'
    },
    base_amount: {
        type: Number,
        required: true
    },
    tax_amount: {
        type: Number,
        default: 0
    },
    total_amount: {
        type: Number,
        required: true
    },
    currency: {
        type: String,
        default: 'INR'
    },
    status: {
        type: String,
        enum: ['unpaid', 'paid', 'cancelled', 'refunded'],
        default: 'unpaid'
    },
    payment_method: {
        type: String,
        default: null
    },
    payment_details: {
        type: mongoose.Schema.Types.Mixed,
        default: {}
    },
    invoice_date: {
        type: Date,
        default: Date.now
    },
    paid_at: {
        type: Date,
        default: null
    },
    description: String,
    createdAt: {
        type: Date,
        default: Date.now
    }
});

// Auto-increment ID
InvoiceSchema.pre('save', async function () {
    if (!this.isNew) {
        return;
    }

    const counter = await Counter.findByIdAndUpdate(
        { _id: 'invoiceId' },
        { $inc: { seq: 1 } },
        { returnDocument: 'after', upsert: true }
    );
    this.invoiceId = counter.seq;
});

module.exports = mongoose.model('Invoice', InvoiceSchema);
