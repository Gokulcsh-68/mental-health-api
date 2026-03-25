const Question = require('../../src/models/Question');
const Master = require('../../src/models/Master');
const StandardizedScore = require('../../src/models/StandardizedScore');

const separationAnxietyQuestions = [
    { text: "During the PAST 7 DAYS, I have felt moments of sudden terror, fear, or fright when separated", category: "separation_anxiety", type: "scale", minAge: 11, maxAge: 14, gender: "male", uiType: "radio" },
    { text: "During the PAST 7 DAYS, I have felt anxious, worried, or nervous about being separated", category: "separation_anxiety", type: "scale", minAge: 11, maxAge: 14, gender: "male", uiType: "radio" },
    { text: "During the PAST 7 DAYS, I have had thoughts of bad things happening to people important to me or bad things happening to me when separated from them (e.g., getting lost, accidents)", category: "separation_anxiety", type: "scale", minAge: 11, maxAge: 14, gender: "male", uiType: "radio" },
    { text: "During the PAST 7 DAYS, I have felt a racing heart, sweaty, trouble breathing, faint, or shaky when separated", category: "separation_anxiety", type: "scale", minAge: 11, maxAge: 14, gender: "male", uiType: "radio" },
    { text: "During the PAST 7 DAYS, I have felt tense muscles, felt on edge or restless, or had trouble relaxing or trouble sleeping when separated", category: "separation_anxiety", type: "scale", minAge: 11, maxAge: 14, gender: "male", uiType: "radio" },
    { text: "During the PAST 7 DAYS, I have avoided going places where I would be separated", category: "separation_anxiety", type: "scale", minAge: 11, maxAge: 14, gender: "male", uiType: "radio" },
    { text: "During the PAST 7 DAYS, I have when separated, left places early to go home", category: "separation_anxiety", type: "scale", minAge: 11, maxAge: 14, gender: "male", uiType: "radio" },
    { text: "During the PAST 7 DAYS, I have spent a lot of time preparing for how to deal with separation", category: "separation_anxiety", type: "scale", minAge: 11, maxAge: 14, gender: "male", uiType: "radio" },
    { text: "During the PAST 7 DAYS, I have distracted myself to avoid thinking about being separated", category: "separation_anxiety", type: "scale", minAge: 11, maxAge: 14, gender: "male", uiType: "radio" },
    { text: "During the PAST 7 DAYS, I have needed help to cope with separation (e.g., alcohol or medications, superstitious objects)", category: "separation_anxiety", type: "scale", minAge: 11, maxAge: 14, gender: "male", uiType: "radio" }
];

const options = [
    { text: "Never", score: 0 },
    { text: "Occasionally", score: 1 },
    { text: "Half of the time", score: 2 },
    { text: "Most of the time", score: 3 },
    { text: "All of the time", score: 4 }
];

const getInterpretation = (rawScore) => {
    const avg = rawScore / 10;
    if (avg < 0.5) return 'None';             // Rounding logic: 0
    if (avg < 1.5) return 'Mild';             // Rounding logic: 1
    if (avg < 2.5) return 'Moderate';         // Rounding logic: 2
    if (avg < 3.5) return 'Severe';           // Rounding logic: 3
    return 'Extreme';                         // Rounding logic: 4
};

const seedSeparationAnxiety = async () => {
    try {
        const master = await Master.findOne({ slug: 'separation_anxiety' });
        const masterId = master ? master._id : null;
        const masterSlug = master ? master.slug : 'separation_anxiety';

        await Question.deleteMany({ category: masterSlug });
        await StandardizedScore.deleteMany({ category: masterSlug });

        const questionsToSeed = separationAnxietyQuestions.map(q => ({
            ...q,
            options,
            master: masterId
        }));

        for (const q of questionsToSeed) {
            await Question.create(q);
        }
        console.log(`  ✅ ${questionsToSeed.length} Separation Anxiety questions seeded`);

        const scoresToSeed = [];
        for (let raw = 0; raw <= 40; raw++) {
            scoresToSeed.push({
                category: masterSlug,
                master: masterId,
                rawScore: raw,
                tScore: (raw / 40) * 100, // Visual representation
                standardError: 0,
                interpretation: getInterpretation(raw)
            });
        }

        await StandardizedScore.insertMany(scoresToSeed);
        console.log(`  📊 ${scoresToSeed.length} Separation Anxiety interpretation records seeded`);

    } catch (err) {
        console.error('  ❌ Separation Anxiety Seeder Error:', err.message);
        throw err;
    }
};

module.exports = seedSeparationAnxiety;
