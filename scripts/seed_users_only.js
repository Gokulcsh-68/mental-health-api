const mongoose = require('mongoose');
const dotenv = require('dotenv');
const seedUsers = require('./seeders/user.seeder');

dotenv.config();

const runUserSeeder = async () => {
    try {
        await mongoose.connect(process.env.MONGO_URI);
        console.log('MongoDB Connected\n');
        
        console.log('🗑️  Clearing old non-super-admin users...');
        const User = require('../src/models/User');
        await User.deleteMany({ role: { $ne: 'super_admin' } });

        console.log('📦 Seeding Additional Users (Tamil Names)...');
        await seedUsers();
        
        console.log('\n🎉 Users seeded successfully!');
        process.exit();
    } catch (err) {
        console.error('\n❌ Seeder failed:', err.message);
        process.exit(1);
    }
};

runUserSeeder();
