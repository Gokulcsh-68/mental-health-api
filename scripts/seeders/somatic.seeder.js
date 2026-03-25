const Question = require('../../src/models/Question');
const Master = require('../../src/models/Master');
const StandardizedScore = require('../../src/models/StandardizedScore');

const somaticQuestions = [
    { text: "Stomach pain", category: "somatic", type: "scale", minAge: 11, maxAge: 120, gender: "all", uiType: "radio" },
    { text: "Back pain", category: "somatic", type: "scale", minAge: 11, maxAge: 120, gender: "all", uiType: "radio" },
    { text: "Pain in your arms, legs, or joints (knees, hips, etc.)", category: "somatic", type: "scale", minAge: 11, maxAge: 120, gender: "all", uiType: "radio" },
    { text: "Menstrual cramps or other problems with your periods (WOMEN ONLY)", category: "somatic", type: "scale", minAge: 11, maxAge: 120, gender: "female", uiType: "radio" },
    { text: "Headaches", category: "somatic", type: "scale", minAge: 11, maxAge: 120, gender: "all", uiType: "radio" },
    { text: "Chest pain", category: "somatic", type: "scale", minAge: 11, maxAge: 120, gender: "all", uiType: "radio" },
    { text: "Dizziness", category: "somatic", type: "scale", minAge: 11, maxAge: 120, gender: "all", uiType: "radio" },
    { text: "Fainting spells", category: "somatic", type: "scale", minAge: 11, maxAge: 120, gender: "all", uiType: "radio" },
    { text: "Feeling your heart pound or race", category: "somatic", type: "scale", minAge: 11, maxAge: 120, gender: "all", uiType: "radio" },
    { text: "Shortness of breath", category: "somatic", type: "scale", minAge: 11, maxAge: 120, gender: "all", uiType: "radio" },
    { text: "Pain or problems during sexual intercourse", category: "somatic", type: "scale", minAge: 11, maxAge: 120, gender: "all", uiType: "radio" },
    { text: "Constipation, loose bowels, or diarrhea", category: "somatic", type: "scale", minAge: 11, maxAge: 120, gender: "all", uiType: "radio" },
    { text: "Nausea, gas, or indigestion", category: "somatic", type: "scale", minAge: 11, maxAge: 120, gender: "all", uiType: "radio" },
    { text: "Feeling tired or having low energy", category: "somatic", type: "scale", minAge: 11, maxAge: 120, gender: "all", uiType: "radio" },
    { text: "Trouble sleeping", category: "somatic", type: "scale", minAge: 11, maxAge: 120, gender: "all", uiType: "radio" }
];

const options = [
    { text: "Not bothered at all", score: 0 },
    { text: "Bothered a little", score: 1 },
    { text: "Bothered a lot", score: 2 }
];

const getInterpretation = (score) => {
    if (score <= 4) return 'Minimal somatic symptoms';
    if (score <= 9) return 'Low somatic symptoms';
    if (score <= 14) return 'Medium somatic symptoms';
    return 'High somatic symptoms';
};

const seedSomatic = async () => {
    try {
        const master = await Master.findOne({ slug: 'somatic' });
        const masterId = master ? master._id : null;
        const masterSlug = master ? master.slug : 'somatic';

        await Question.deleteMany({ category: masterSlug });
        await StandardizedScore.deleteMany({ category: masterSlug });

        const questionsToSeed = somaticQuestions.map(q => ({
            ...q,
            options,
            category: masterSlug,
            master: masterId
        }));

        await Question.create(questionsToSeed);
        console.log(`  ✅ ${questionsToSeed.length} Somatic Symptom questions seeded`);

        const scoresToSeed = [];
        // Max raw is 15 items * 2 = 30
        for (let raw = 0; raw <= 30; raw++) {
            scoresToSeed.push({
                category: masterSlug,
                master: masterId,
                rawScore: raw,
                tScore: raw, // PHQ-15 doesn't use t-scores, use raw
                standardError: 0,
                interpretation: getInterpretation(raw)
            });
        }

        await StandardizedScore.insertMany(scoresToSeed);
        console.log(`  📊 ${scoresToSeed.length} Somatic Symptom interpretation records seeded`);

    } catch (err) {
        console.error('  ❌ Somatic Seeder Error:', err.message);
        throw err;
    }
};

module.exports = seedSomatic;
