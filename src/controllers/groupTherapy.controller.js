const Group = require('../models/Group');
const GroupSession = require('../models/GroupSession');

/** Create a new group therapy */
exports.createGroup = async (req, res) => {
  try {
    const { type, facilitator, capacity } = req.body;
    const group = new Group({ type, facilitator, capacity, members: [] });
    await group.save();
    res.status(201).json(group);
  } catch (err) {
    res.status(400).json({ error: err.message });
  }
};

/** Add a member to a group */
exports.addMember = async (req, res) => {
  try {
    const { groupId } = req.params;
    const { memberId } = req.body;
    const group = await Group.findOneAndUpdate(
      { groupId: Number(groupId) },
      { $addToSet: { members: memberId } },
      { new: true }
    );
    res.json(group);
  } catch (err) {
    res.status(400).json({ error: err.message });
  }
};

/** Record a group session */
exports.createSession = async (req, res) => {
  try {
    const { groupId, date, notes } = req.body;
    const group = await Group.findOne({ groupId: Number(groupId) });
    if (!group) throw new Error('Group not found');
    const session = new GroupSession({
      group: group._id,
      date,
      notes,
      memberNotes: [],
      attendance: [],
      outcomeMeasures: []
    });
    await session.save();
    res.status(201).json(session);
  } catch (err) {
    res.status(400).json({ error: err.message });
  }
};

/** Add or update a member's note for a session */
exports.upsertMemberNote = async (req, res) => {
  try {
    const { sessionId, memberId } = req.params;
    const { note } = req.body;
    const session = await GroupSession.findOne({ sessionId: Number(sessionId) });
    if (!session) throw new Error('Session not found');
    const existing = session.memberNotes.find(n => n.member.toString() === memberId);
    if (existing) {
      existing.note = note;
    } else {
      session.memberNotes.push({ member: memberId, note });
    }
    await session.save();
    res.json(session);
  } catch (err) {
    res.status(400).json({ error: err.message });
  }
};

/** Record attendance for members */
exports.recordAttendance = async (req, res) => {
  try {
    const { sessionId } = req.params;
    const { attendance } = req.body; // [{ memberId, present }]
    const session = await GroupSession.findOne({ sessionId: Number(sessionId) });
    if (!session) throw new Error('Session not found');
    // Replace attendance array
    session.attendance = attendance.map(a => ({ member: a.memberId, present: a.present }));
    await session.save();
    res.json(session);
  } catch (err) {
    res.status(400).json({ error: err.message });
  }
};

/** Add outcome measure (e.g., GAS, CORE‑OM) */
exports.addOutcomeMeasure = async (req, res) => {
  try {
    const { sessionId } = req.params;
    const { measureName, score } = req.body;
    const session = await GroupSession.findOne({ sessionId: Number(sessionId) });
    if (!session) throw new Error('Session not found');
    session.outcomeMeasures.push({ measureName, score, recordedAt: new Date() });
    await session.save();
    res.json(session);
  } catch (err) {
    res.status(400).json({ error: err.message });
  }
};

/** Get group details (including members) */
exports.getGroup = async (req, res) => {
  try {
    const { groupId } = req.params;
    const group = await Group.findOne({ groupId: Number(groupId) }).populate('facilitator members');
    if (!group) throw new Error('Group not found');
    res.json(group);
  } catch (err) {
    res.status(404).json({ error: err.message });
  }
};

/** List all groups */
exports.listGroups = async (req, res) => {
  try {
    const groups = await Group.find().populate('facilitator members');
    res.json(groups);
  } catch (err) {
    res.status(500).json({ error: err.message });
  }
};
