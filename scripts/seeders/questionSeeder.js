require('dotenv').config();
const mongoose = require('mongoose');

// ================== IMPORT ALL SEEDERS ==================
const seedDepression = require('./depression.seeder');
const seedAnxiety = require('./anxiety.seeder');
const seedAnger = require('./anger.seeder');
const seedSleep = require('./sleep.seeder');
const seedMania = require('./mania.seeder');
const seedSomatic = require('./somatic.seeder');
const seedBipolar = require('./bipolar.seeder');
const seedRepetitiveThoughts = require('./repetitive_thoughts.seeder');
const seedSubstanceUse = require('./substance.seeder');
const seedPostpartum = require('./postpartum.seeder');
const seedAngerPediatric = require('./anger_pediatric.seeder');
const seedAnxietyPediatric = require('./anxiety_pediatric.seeder');
const seedIrritability = require('./irritability.seeder');
const seedRepetitiveThoughtsPediatric = require('./repetitive_thoughts_pediatric.seeder');
const seedSeparationAnxiety = require('./separation_anxiety.seeder');
const seedODD = require('./odd.seeder');
const seedSocialAnxiety = require('./social_anxiety.seeder');
const seedAgoraphobia = require('./agoraphobia.seeder');
const seedPanicDisorder = require('./panic_disorder.seeder');
const seedADHD = require('./adhd.seeder');
const seedOCD = require('./ocd.seeder');
const seedPsychosis = require('./psychosis.seeder');
const seedGambling = require('./gambling.seeder');
const seedEatingDisorder = require('./eating_disorder.seeder');
const seedPMDD = require('./pmdd.seeder');
const seedAutismSpectrum = require('./autism_spectrum.seeder');
const seedPTSDPediatric = require('./ptsd_pediatric.seeder');
const seedAcuteStress = require('./acute_stress.seeder');
const seedDissociativeSymptoms = require('./dissociative_symptoms.seeder');
const seedOther = require('./other.seeder');
const seedDemographicQuestions = require('./patient_demographic_questions.seeder');
const seedPatientQuestions = require('./patient_questions.seeder');
const seedTopicQuestions = require('./patient_topic_questions.seeder');
const seedPersonalityInventory = require('./personality_inventory.seeder');
const seedRefinedQuestions = require('./refined_patient_questions.seeder');
const seedRoleBasedQuestions = require('./role_based_questions.seeder');
const seedAssessmentQuestions = require('./seed_assessment_questions.js');
const seedProfessionalQuestions = require('./seed_professional_questions.js');
const seedRefinedQuestionsRoot = require('./seed_refined_questions.js');
const seedSelfQuestions = require('./seed_self_questions.js');

// ================== MAIN SEED FUNCTION ==================
const seedQuestions = async () => {
    try {
        console.log('📦 Seeding Depression...');
        await seedDepression();

        console.log('📦 Seeding Anxiety...');
        await seedAnxiety();

        console.log('📦 Seeding Anxiety Pediatric...');
        await seedAnxietyPediatric();

        console.log('📦 Seeding Social Anxiety...');
        await seedSocialAnxiety();

        console.log('📦 Seeding Agoraphobia...');
        await seedAgoraphobia();

        console.log('📦 Seeding Panic Disorder...');
        await seedPanicDisorder();

        console.log('📦 Seeding ADHD...');
        await seedADHD();

        console.log('📦 Seeding OCD...');
        await seedOCD();

        console.log('📦 Seeding Psychosis...');
        await seedPsychosis();

        console.log('📦 Seeding Gambling...');
        await seedGambling();

        console.log('📦 Seeding Eating Disorder...');
        await seedEatingDisorder();

        console.log('📦 Seeding PMDD...');
        await seedPMDD();

        console.log('📦 Seeding Autism Spectrum...');
        await seedAutismSpectrum();

        console.log('📦 Seeding PTSD...');
        await seedPTSDPediatric();

        console.log('📦 Seeding Acute Stress...');
        await seedAcuteStress();

        console.log('📦 Seeding Dissociative Symptoms...');
        await seedDissociativeSymptoms();

        console.log('📦 Seeding Anger...');
        await seedAnger();

        console.log('📦 Seeding Anger Pediatric...');
        await seedAngerPediatric();

        console.log('📦 Seeding Irritability...');
        await seedIrritability();

        console.log('📦 Seeding Sleep...');
        await seedSleep();

        console.log('📦 Seeding Mania...');
        await seedMania();

        console.log('📦 Seeding Somatic...');
        await seedSomatic();

        console.log('📦 Seeding Bipolar...');
        await seedBipolar();

        console.log('📦 Seeding Repetitive Thoughts...');
        await seedRepetitiveThoughts();

        console.log('📦 Seeding Repetitive Thoughts Pediatric...');
        await seedRepetitiveThoughtsPediatric();

        console.log('📦 Seeding Separation Anxiety...');
        await seedSeparationAnxiety();

        console.log('📦 Seeding ODD...');
        await seedODD();

        console.log('📦 Seeding Substance Use...');
        await seedSubstanceUse();

        console.log('📦 Seeding Postpartum...');
        await seedPostpartum();

        console.log('📦 Seeding Other...');
        await seedOther();

        console.log('📦 Seeding Demographics...');
        await seedDemographicQuestions();

        console.log('📦 Seeding Patient Questions...');
        await seedPatientQuestions();

        console.log('📦 Seeding Topic Questions...');
        await seedTopicQuestions();

        console.log('📦 Seeding Personality Inventory...');
        await seedPersonalityInventory();

        console.log('📦 Seeding Refined Questions...');
        await seedRefinedQuestions();

        console.log('📦 Seeding Role Based Questions...');
        await seedRoleBasedQuestions();

        console.log('📦 Seeding Assessment Questions...');
        await seedAssessmentQuestions();

        console.log('📦 Seeding Self Questions...');
        await seedSelfQuestions();

        console.log('📦 Seeding Root Refined Questions...');
        await seedRefinedQuestionsRoot();

        console.log('📦 Tagging Professional Questions...');
        await seedProfessionalQuestions();

        console.log('\n✅ ALL SEEDING COMPLETED SUCCESSFULLY');
    } catch (err) {
        console.error('❌ Seeder Error:', err);
        throw err;
    }
};

// ================== RUNNER ==================
if (require.main === module) {
    const MONGO_URI =
        process.env.MONGO_URI || 'mongodb://127.0.0.1:27017/mental_health_db';

    mongoose
        .connect(MONGO_URI)
        .then(async () => {
            console.log('✅ MongoDB Connected');

            await seedQuestions();

            console.log('🎉 Seeding Done');
            process.exit(0);
        })
        .catch((err) => {
            console.error('❌ DB Connection Error:', err);
            process.exit(1);
        });
}

// export (optional reuse)
module.exports = seedQuestions;