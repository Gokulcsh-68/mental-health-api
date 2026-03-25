const Question = require('../../src/models/Question');
const Master = require('../../src/models/Master');
const StandardizedScore = require('../../src/models/StandardizedScore');

const pmddQuestions = [
    { text: "Marked mood swings (suddenly sad, tearful, sensitive to rejection)" },
    { text: "Marked irritability, anger, or increased interpersonal conflicts" },
    { text: "Marked depressed mood, feelings of hopelessness, or self-deprecating thoughts" },
    { text: "Marked anxiety, tension, feeling 'keyed up' or 'on edge'" },
    { text: "Decreased interest in usual activities" },
    { text: "Difficulty concentrating" },
    { text: "Lethargy, fatigue, or marked lack of energy" },
    { text: "Marked change in appetite, overeating, or specific food cravings" },
    { text: "Hypersomnia or insomnia" },
    { text: "Feeling overwhelmed or out of control" },
    { text: "Physical symptoms (breast tenderness, bloating, joint/muscle pain, weight gain)" }
];

const options = [
    { text: "Not at all", score: 0 },
    { text: "Several days", score: 1 },
    { text: "More than half the days", score: 2 },
    { text: "Nearly every day", score: 3 }
];

const getInterpretation = (rawScore) => {
    if (rawScore <= 10) return 'Minimal premenstrual symptoms';
    if (rawScore <= 16) return 'Mild premenstrual symptoms';
    if (rawScore <= 22) return 'Moderate premenstrual symptoms';
    return 'Severe premenstrual symptoms';
};

const seedPMDD = async () => {
    try {
        const master = await Master.findOne({ slug: 'pmdd' });
        const masterId = master ? master._id : null;
        const masterSlug = master ? master.slug : 'pmdd';

        await Question.deleteMany({ category: masterSlug });
        await StandardizedScore.deleteMany({ category: masterSlug });

        const questionsToSeed = pmddQuestions.map(q => ({
            text: q.text,
            category: masterSlug,
            type: "scale",
            minAge: 12,
            maxAge: 45,
            gender: "female",
            uiType: "radio",
            options,
            master: masterId
        }));

        for (const q of questionsToSeed) {
            await Question.create(q);
        }
        console.log(`  ✅ ${questionsToSeed.length} PMDD questions seeded`);

        const scoresToSeed = [];
        // Max raw is 11 * 3 = 33
        for (let raw = 0; raw <= 33; raw++) {
            scoresToSeed.push({
                category: masterSlug,
                master: masterId,
                rawScore: raw,
                tScore: (raw / 33) * 100, // Visual percentage
                standardError: 0,
                interpretation: getInterpretation(raw)
            });
        }

        await StandardizedScore.insertMany(scoresToSeed);
        console.log(`  📊 ${scoresToSeed.length} PMDD interpretation records seeded`);

    } catch (err) {
        console.error('  ❌ PMDD Seeder Error:', err.message);
        throw err;
    }
};

module.exports = seedPMDD;
