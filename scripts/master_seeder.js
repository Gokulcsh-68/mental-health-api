const mongoose = require('mongoose');
const dotenv = require('dotenv');
const Role = require('../src/models/Role');
const ApiAccess = require('../src/models/ApiAccess');
const User = require('../src/models/User');
const Question = require('../src/models/Question');
const Assessment = require('../src/models/Assessment');
const Notification = require('../src/models/Notification');
const Counter = require('../src/models/Counter');
const StandardizedScore = require('../src/models/StandardizedScore');
const Master = require('../src/models/Master');
const TaxCode = require('../src/models/TaxCode');
const ChargeCode = require('../src/models/ChargeCode');
const AuditLog = require('../src/models/AuditLog');
const ChiefComplaint = require('../src/models/ChiefComplaint');
const Consult = require('../src/models/Consult');
const Feedback = require('../src/models/Feedback');
const HPI = require('../src/models/HPI');
const HistoryOfIllness = require('../src/models/HistoryOfIllness');
const Invoice = require('../src/models/Invoice');
const MSE = require('../src/models/MSE');
const Message = require('../src/models/Message');
const PastHistory = require('../src/models/PastHistory');
const ROS = require('../src/models/ROS');
const Recommendation = require('../src/models/Recommendation');
const RefreshToken = require('../src/models/RefreshToken');
const SpecialistSchedule = require('../src/models/SpecialistSchedule');
const StepLog = require('../src/models/StepLog');
const Symptom = require('../src/models/Symptom');
const SystemSetting = require('../src/models/SystemSetting');
const TreatmentPlan = require('../src/models/TreatmentPlan');
const TreatmentStage = require('../src/models/TreatmentStage');

const seedRoles = require('./seeders/role.seeder');
const seedApiAccess = require('./seeders/access.seeder');
const seedSuperAdmin = require('./seeders/super_admin.seeder');
const seedUsers = require('./seeders/user.seeder');
const seedQuestions = require('./seeders/questionSeeder');
const seedMasters = require('./seeders/master.seeder');
const seedTaxCodes = require('./seeders/taxCode.seeder');
const seedRecommendations = require('./seeders/recommendations.seeder');
const seedPortalContent = require('./seeders/portal_content.seeder');
const seedTestData = require('./seeders/test_data.seeder');

dotenv.config();

const runAllSeeders = async () => {
    const isFullReset = process.argv.includes('--reset');

    try {
        await mongoose.connect(process.env.MONGO_URI);
        console.log('MongoDB Connected\n');

        if (isFullReset) {
            console.log('🗑️  Performing clean platform reset (A to Z)...');
            await Role.deleteMany();
            await ApiAccess.deleteMany();
            await User.deleteMany();
            await Assessment.deleteMany();
            await Notification.deleteMany();
            await Counter.deleteMany();
            await StandardizedScore.deleteMany();
            await Question.deleteMany();
            await Master.deleteMany();
            await TaxCode.deleteMany();
            await ChargeCode.deleteMany();
            await AuditLog.deleteMany();
            await ChiefComplaint.deleteMany();
            await Consult.deleteMany();
            await Feedback.deleteMany();
            await HPI.deleteMany();
            await HistoryOfIllness.deleteMany();
            await Invoice.deleteMany();
            await MSE.deleteMany();
            await Message.deleteMany();
            await PastHistory.deleteMany();
            await ROS.deleteMany();
            await Recommendation.deleteMany();
            await RefreshToken.deleteMany();
            await SpecialistSchedule.deleteMany();
            await StepLog.deleteMany();
            await Symptom.deleteMany();
            await SystemSetting.deleteMany();
            await TreatmentPlan.deleteMany();
            await TreatmentStage.deleteMany();
            console.log('  ✅ All collections (A to Z) cleared and sequences reset');
        } else {
            console.log('📦 Updating resource masters (preserving application data)...');
            // Only clear master data, not app data
            await Role.deleteMany();
            await ApiAccess.deleteMany();
            await Question.deleteMany();
            await Master.deleteMany();
            await TaxCode.deleteMany();
            await ChargeCode.deleteMany();
            console.log('  ✅ Master data collections cleared');
        }

        console.log('\n📦 Seeding Roles & API Access...');
        await seedRoles();
        await seedApiAccess();

        console.log('\n📦 Seeding Resource Masters...');
        await seedMasters();

        console.log('\n📦 Seeding Questions & Topics...');
        await seedQuestions();

        console.log('\n📦 Seeding Tax Codes...');
        await seedTaxCodes();

        console.log('\n📦 Seeding Recommendations...');
        await seedRecommendations();

        console.log('\n📦 Seeding Portal Content & Test Data...');
        await seedPortalContent();
        await seedTestData();

        if (isFullReset) {
            console.log('\n📦 Seeding Default Users...');
            await seedSuperAdmin();
        }

        console.log('\n🎉 All seeders completed successfully!');
        process.exit();
    } catch (err) {
        console.error('\n❌ Seeder failed:', err.message);
        process.exit(1);
    }
};

runAllSeeders();
