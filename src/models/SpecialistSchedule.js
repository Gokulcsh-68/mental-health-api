const mongoose = require('mongoose');
const Counter = require('./Counter');

const SpecialistScheduleSchema = new mongoose.Schema({
    scheduleId: {
        type: Number,
        unique: true
    },
    specialist_id: {
        type: Number,
        required: [true, 'Please add a specialist ID'],
        index: true
    },
    type: {
        type: String,
        enum: ['availability', 'unavailability'],
        required: [true, 'Please specify the schedule type']
    },
    dayOfWeek: {
        type: Number,
        min: 0,
        max: 6,
        default: null,
        description: '0 (Sun) to 6 (Sat). Null for date-specific entries.'
    },
    specificDate: {
        type: Date,
        default: null,
        description: 'Start date. Null for recurring entries.'
    },
    specificEndDate: {
        type: Date,
        default: null,
        description: 'End date for ranges (e.g., vacation). Null for single day entries.'
    },
    startTime: {
        type: String,
        required: [true, 'Please add a start time'],
        match: [/^([01]\d|2[0-3]):?([0-5]\d)$/, 'Please add a valid time in HH:mm format']
    },
    endTime: {
        type: String,
        required: [true, 'Please add an end time'],
        match: [/^([01]\d|2[0-3]):?([0-5]\d)$/, 'Please add a valid time in HH:mm format']
    },
    isRecurring: {
        type: Boolean,
        default: false
    },
    slotDuration: {
        type: Number,
        default: 15,
        description: 'Duration of each appointment slot in minutes.'
    },
    bufferTime: {
        type: Number,
        default: 15,
        description: 'Gap between slots in minutes.'
    },
    maxAppointments: {
        type: Number,
        default: null,
        description: 'Daily cap for this schedule entry.'
    },
    locationTypes: {
        type: [String],
        enum: ['virtual', 'home', 'clinic'],
        default: ['virtual']
    },
    breaks: [
        {
            startTime: {
                type: String,
                match: [/^([01]\d|2[0-3]):?([0-5]\d)$/, 'Please add a valid time in HH:mm format']
            },
            endTime: {
                type: String,
                match: [/^([01]\d|2[0-3]):?([0-5]\d)$/, 'Please add a valid time in HH:mm format']
            },
            description: String
        }
    ],
    recurrenceRule: {
        type: mongoose.Schema.Types.Mixed,
        default: null,
        description: 'Structured object for advanced recurrence patterns.'
    },
    description: {
        type: String,
        trim: true,
        default: null
    },
    isActive: {
        type: Boolean,
        default: true
    },
    createdAt: {
        type: Date,
        default: Date.now
    }
});

// Auto-increment ID
SpecialistScheduleSchema.pre('save', async function () {
    if (!this.isNew) {
        return;
    }

    const counter = await Counter.findByIdAndUpdate(
        { _id: 'scheduleId' },
        { $inc: { seq: 1 } },
        { returnDocument: 'after', upsert: true }
    );
    this.scheduleId = counter.seq;
});

module.exports = mongoose.model('SpecialistSchedule', SpecialistScheduleSchema);
