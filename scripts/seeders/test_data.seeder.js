const MSE = require('../../src/models/MSE');
const TreatmentStage = require('../../src/models/TreatmentStage');
const User = require('../../src/models/User');

const seedTestData = async () => {
    try {
        const patient = await User.findOne({ username: 'karthik' });
        if (!patient) {
            console.log('  ⚠️ Patient "karthik" not found. Skipping test data seeding.');
            return;
        }

        // 1. Seed MSE Logs
        await MSE.deleteMany({ patient: patient._id });
        const mseData = [
            { patient: patient._id, mood: { subjective: 'Happy' }, color_code: '#4CAF50', createdAt: new Date(Date.now() - 100000) },
            { patient: patient._id, mood: { subjective: 'Neutral' }, color_code: '#8BC34A', createdAt: new Date(Date.now() - 200000) },
            { patient: patient._id, mood: { subjective: 'Anxious' }, color_code: '#FFEB3B', createdAt: new Date(Date.now() - 300000) }
        ];
        await MSE.create(mseData);
        console.log(`  ✅ ${mseData.length} MSE records seeded for Karthik`);

        // 2. Seed Treatment Stage
        await TreatmentStage.deleteMany({ patient: patient._id });
        const stages = [
            { patient: patient._id, stage: 'Onboarding', status: 'completed', order: 1 },
            { patient: patient._id, stage: 'Assessment', status: 'in_progress', order: 2 }
        ];
        await TreatmentStage.insertMany(stages);
        console.log(`  ✅ ${stages.length} Treatment Stages seeded for Karthik`);

    } catch (err) {
        console.error('  ❌ Test Data Seeder Error:', err.message);
        throw err;
    }
};

module.exports = seedTestData;
