const mongoose = require('mongoose');
const bcrypt = require('bcryptjs');
require('dotenv').config();
const User = require('../src/models/User');

/**
 * Ensures the johndoe user exists in the local database with correct credentials.
 */
async function setup() {
    try {
        console.log('📡 Connecting to MongoDB...');
        await mongoose.connect(process.env.MONGO_URI);
        
        const salt = await bcrypt.genSalt(10);
        const hash = await bcrypt.hash('Password@123', salt);
        
        const result = await User.updateOne(
            { username: 'johndoe' },
            { 
                $set: { 
                    password: hash, 
                    role: 'patient',
                    firstName: 'John',
                    lastName: 'Doe',
                    email: 'johndoe@example.com',
                    loginAttempts: 0 
                },
                $unset: { lockUntil: '' } 
            },
            { upsert: true }
        );

        console.log(result.upsertedCount > 0 ? '✅ Created johndoe user.' : '✅ Updated johndoe credentials.');
    } catch (err) {
        console.error('❌ Database Error:', err.message);
    } finally {
        await mongoose.disconnect();
        process.exit(0);
    }
}

setup();
