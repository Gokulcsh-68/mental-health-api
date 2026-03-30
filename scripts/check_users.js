// Reset karthik's password and create super_admin if missing
// Uses updateOne to bypass the pre-save bcrypt hook (avoids double-hashing)
const mongoose = require('mongoose');
const bcrypt = require('bcryptjs');
require('dotenv').config();

async function main() {
  await mongoose.connect(process.env.MONGO_URI);
  console.log('Connected to MongoDB\n');

  const User = require('../src/models/User');

  // 1. Reset karthik's password (bypass pre-save hook with updateOne)
  const salt = await bcrypt.genSalt(10);

  const karthikHash = await bcrypt.hash('Karthik@123', salt);
  const r1 = await User.updateOne(
    { username: 'karthik' },
    { $set: { password: karthikHash, loginAttempts: 0 }, $unset: { lockUntil: '' } }
  );
  console.log(r1.modifiedCount ? '✅ karthik password reset' : '⚠️  karthik not found or unchanged');

  // 2. Ensure super_admin exists
  let sa = await User.findOne({ role: 'super_admin' });
  if (!sa) {
    // Use User.create so the pre-save hook hashes it once
    sa = await User.create({
      firstName: 'Super', lastName: 'Admin', username: 'superadmin',
      email: 'superadmin@mindbalance.com', password: 'Test12345!',
      phone: '9840056700', role: 'super_admin', gender: 'male',
      dateOfBirth: new Date('1960-04-02')
    });
    console.log('✅ super_admin created');
  } else {
    const saHash = await bcrypt.hash('Test12345!', salt);
    await User.updateOne(
      { _id: sa._id },
      { $set: { password: saHash, loginAttempts: 0 }, $unset: { lockUntil: '' } }
    );
    console.log('✅ super_admin password reset');
  }

  // 3. Quick verify
  const karthik = await User.findOne({ username: 'karthik' }).select('+password');
  const match = await bcrypt.compare('Karthik@123', karthik.password);
  console.log(`\n🔍 Verify karthik password match: ${match ? '✅ YES' : '❌ NO'}`);

  const admin = await User.findOne({ role: 'super_admin' }).select('+password');
  const match2 = await bcrypt.compare('Test12345!', admin.password);
  console.log(`🔍 Verify superadmin password match: ${match2 ? '✅ YES' : '❌ NO'}`);

  await mongoose.disconnect();
  process.exit(0);
}

main().catch(e => { console.error(e); process.exit(1); });
