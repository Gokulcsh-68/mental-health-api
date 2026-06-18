// Script to inspect user with userId 3
require('dotenv').config();
const mongoose = require('mongoose');
const User = require('../src/models/User');
const Patient = require('../src/models/Patient');

async function main() {
  await mongoose.connect(process.env.MONGO_URI);
  const user = await User.findOne({ userId: 3 });
  console.log('User with userId 3:', user);
  if (user) {
    const patient = await Patient.findOne({ user_id: user._id });
    console.log('Patient linked to this user:', patient);
  }
  await mongoose.disconnect();
}

main().catch(err => {
  console.error('Error:', err);
});
