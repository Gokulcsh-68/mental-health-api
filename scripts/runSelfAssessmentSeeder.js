const mongoose = require('mongoose');
const dotenv = require('dotenv');
const path = require('path');

// Import seeders
const seedSelfQuestions = require('./seeders/seed_self_questions.js');
const seedAssessmentQuestions = require('./seeders/seed_assessment_questions.js');

// Config
dotenv.config({ path: path.join(__dirname, '../.env') });

const runSeeder = async () => {
    try {
        const mongoUri = process.env.MONGO_URI || 'mongodb://127.0.0.1:27017/mental_health_db';
        console.log(`Connecting to MongoDB at ${mongoUri}...`);
        
        await mongoose.connect(mongoUri);
        console.log('✅ MongoDB Connected\n');

        console.log('📦 Seeding Self Questions (20 questions)...');
        await seedSelfQuestions();

        console.log('\n📦 Seeding Assessment Questions (5 questions)...');
        await seedAssessmentQuestions();

        console.log('\n🎉 All self-assessment seeders completed successfully!');
        process.exit(0);
    } catch (err) {
        console.error('\n❌ Seeder failed:', err);
        process.exit(1);
    }
};

runSeeder();
