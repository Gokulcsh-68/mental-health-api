const Question = require('../../src/models/Question');
const Master = require('../../src/models/Master');
const StandardizedScore = require('../../src/models/StandardizedScore');

const irritabilityQuestions = [
    {
        text: "Am easily annoyed by others.",
        category: "irritability",
        type: "scale",
        minAge: 11,
        maxAge: 17,
        gender: "all",
        options: [
            { text: "Not True", score: 0 },
            { text: "Somewhat True", score: 1 },
            { text: "Certainly True", score: 2 }
        ],
        uiType: "radio"
    },
    {
        text: "Often lose my temper.",
        category: "irritability",
        type: "scale",
        minAge: 11,
        maxAge: 17,
        gender: "all",
        options: [
            { text: "Not True", score: 0 },
            { text: "Somewhat True", score: 1 },
            { text: "Certainly True", score: 2 }
        ],
        uiType: "radio"
    },
    {
        text: "Stay angry for a long time.",
        category: "irritability",
        type: "scale",
        minAge: 11,
        maxAge: 17,
        gender: "all",
        options: [
            { text: "Not True", score: 0 },
            { text: "Somewhat True", score: 1 },
            { text: "Certainly True", score: 2 }
        ],
        uiType: "radio"
    },
    {
        text: "Am angry most of the time.",
        category: "irritability",
        type: "scale",
        minAge: 11,
        maxAge: 17,
        gender: "all",
        options: [
            { text: "Not True", score: 0 },
            { text: "Somewhat True", score: 1 },
            { text: "Certainly True", score: 2 }
        ],
        uiType: "radio"
    },
    {
        text: "Get angry frequently.",
        category: "irritability",
        type: "scale",
        minAge: 11,
        maxAge: 17,
        gender: "all",
        options: [
            { text: "Not True", score: 0 },
            { text: "Somewhat True", score: 1 },
            { text: "Certainly True", score: 2 }
        ],
        uiType: "radio"
    },
    {
        text: "Lose temper easily.",
        category: "irritability",
        type: "scale",
        minAge: 11,
        maxAge: 17,
        gender: "all",
        options: [
            { text: "Not True", score: 0 },
            { text: "Somewhat True", score: 1 },
            { text: "Certainly True", score: 2 }
        ],
        uiType: "radio"
    },
    {
        text: "Overall irritability causes me problems.",
        category: "irritability",
        type: "scale",
        minAge: 11,
        maxAge: 17,
        gender: "all",
        options: [
            { text: "Not True", score: 0 },
            { text: "Somewhat True", score: 1 },
            { text: "Certainly True", score: 2 }
        ],
        uiType: "radio"
    }
];

const scoringTable = [
    { minRaw: 0, maxRaw: 2, interpretation: "None" },
    { minRaw: 3, maxRaw: 8, interpretation: "Mild–Moderate irritability" },
    { minRaw: 9, maxRaw: 12, interpretation: "Moderate–Severe irritability" }
];

const seedIrritability = async () => {
    try {
        const master = await Master.findOne({ slug: 'irritability' });
        const masterId = master ? master._id : null;
        const masterSlug = master ? master.slug : 'irritability';

        await Question.deleteMany({ category: masterSlug });
        await StandardizedScore.deleteMany({ category: masterSlug });

        const questionsToSeed = irritabilityQuestions.map(q => ({
            ...q,
            master: masterId
        }));

        for (const q of questionsToSeed) {
            await Question.create(q);
        }
        console.log(`  ✅ ${questionsToSeed.length} Irritability questions seeded`);

        const scoresToSeed = [];
        for (let raw = 0; raw <= 14; raw++) {
            let entry = scoringTable.find(t => raw >= t.minRaw && raw <= t.maxRaw);
            let interpretation = entry ? entry.interpretation : "Moderate–Severe irritability"; // Handle raw 13, 14
            scoresToSeed.push({
                category: masterSlug,
                master: masterId,
                rawScore: raw,
                tScore: (raw / 14) * 100, // Derived percentage for visual representation
                standardError: 0,
                interpretation
            });
        }

        await StandardizedScore.insertMany(scoresToSeed);
        console.log(`  📊 ${scoresToSeed.length} Irritability interpretation records seeded`);

    } catch (err) {
        console.error('  ❌ Irritability Seeder Error:', err.message);
        throw err;
    }
};

module.exports = seedIrritability;
