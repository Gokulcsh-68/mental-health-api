const mongoose = require('mongoose');
const Counter = require('./Counter');

/**
 * Group model represents a therapy group.
 * Fields:
 *  - groupId: auto‑increment numeric ID
 *  - type: e.g., 'CBT', 'Support', 'Psychoeducation'
 *  - facilitator: reference to User (psychiatrist/therapist)
 *  - members: array of User references
 *  - capacity: maximum number of members
 */
const GroupSchema = new mongoose.Schema({
  groupId: { type: Number, unique: true },
  type: { type: String, required: true },
  facilitator: { type: mongoose.Schema.Types.ObjectId, ref: 'User', required: true },
  members: [{ type: mongoose.Schema.Types.ObjectId, ref: 'User' }],
  capacity: { type: Number, required: true },
}, { timestamps: true });

// Auto‑increment groupId
GroupSchema.pre('save', async function () {
  if (!this.isNew) return;
  const counter = await Counter.findByIdAndUpdate(
    { _id: 'groupId' },
    { $inc: { seq: 1 } },
    { returnDocument: 'after', upsert: true }
  );
  this.groupId = counter.seq;
});

module.exports = mongoose.model('Group', GroupSchema);
