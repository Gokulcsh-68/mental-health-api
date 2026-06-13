const mongoose = require('mongoose');
const Counter = require('./Counter');

/**
 * GroupSession model stores a session for a therapy group.
 * Fields:
 *  - sessionId: auto‑increment numeric ID
 *  - group: reference to Group
 *  - date: Date of the session
 *  - notes: group‑level notes (String)
 *  - memberNotes: [{ member: User ref, note: String }]
 *  - attendance: [{ member: User ref, present: Boolean, timestamp: Date }]
 */
const GroupSessionSchema = new mongoose.Schema({
  sessionId: { type: Number, unique: true },
  group: { type: mongoose.Schema.Types.ObjectId, ref: 'Group', required: true },
  date: { type: Date, default: Date.now },
  notes: { type: String },
  memberNotes: [
    {
      member: { type: mongoose.Schema.Types.ObjectId, ref: 'User' },
      note: { type: String }
    }
  ],
  attendance: [
    {
      member: { type: mongoose.Schema.Types.ObjectId, ref: 'User' },
      present: { type: Boolean },
      timestamp: { type: Date, default: Date.now }
    }
  ]
}, { timestamps: true });

// Auto‑increment sessionId
GroupSessionSchema.pre('save', async function () {
  if (!this.isNew) return;
  const counter = await Counter.findByIdAndUpdate(
    { _id: 'groupSessionId' },
    { $inc: { seq: 1 } },
    { returnDocument: 'after', upsert: true }
  );
  this.sessionId = counter.seq;
});

module.exports = mongoose.model('GroupSession', GroupSessionSchema);
