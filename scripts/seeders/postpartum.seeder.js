const Question = require('../../src/models/Question');
const Master = require('../../src/models/Master');
const StandardizedScore = require('../../src/models/StandardizedScore');

const postpartumQuestions = [
    {
        text: "I have been able to laugh and see the funny side of things",
        category: "postpartum",
        type: "scale",
        minAge: 18,
        maxAge: 120,
        gender: "female",
        options: [
            { text: "As much as I always could", score: 0 },
            { text: "Not quite as much now", score: 1 },
            { text: "Definitely not as much now", score: 2 },
            { text: "Not at all", score: 3 }
        ],
        uiType: "radio"
    },
    {
        text: "I have looked forward with enjoyment to things",
        category: "postpartum",
        type: "scale",
        minAge: 18,
        maxAge: 120,
        gender: "female",
        options: [
            { text: "As much as I ever did", score: 0 },
            { text: "Somewhat less than I used to", score: 1 },
            { text: "Definitely less than I used to", score: 2 },
            { text: "Hardly at all", score: 3 }
        ],
        uiType: "radio"
    },
    {
        text: "I have blamed myself when things went wrong",
        category: "postpartum",
        type: "scale",
        minAge: 18,
        maxAge: 120,
        gender: "female",
        options: [
            { text: "Yes, most of the time", score: 3 },
            { text: "Yes, some of the time", score: 2 },
            { text: "Not very often", score: 1 },
            { text: "No, never", score: 0 }
        ],
        uiType: "radio"
    },
    {
        text: "I have felt anxious or worried",
        category: "postpartum",
        type: "scale",
        minAge: 18,
        maxAge: 120,
        gender: "female",
        options: [
            { text: "No, not at all", score: 0 },
            { text: "Hardly ever", score: 1 },
            { text: "Yes, sometimes", score: 2 },
            { text: "Yes, very often", score: 3 }
        ],
        uiType: "radio"
    },
    {
        text: "I have felt scared or panicky",
        category: "postpartum",
        type: "scale",
        minAge: 18,
        maxAge: 120,
        gender: "female",
        options: [
            { text: "Yes, quite a lot", score: 3 },
            { text: "Yes, sometimes", score: 2 },
            { text: "No, not much", score: 1 },
            { text: "No, not at all", score: 0 }
        ],
        uiType: "radio"
    },
    {
        text: "I have felt overwhelmed",
        category: "postpartum",
        type: "scale",
        minAge: 18,
        maxAge: 120,
        gender: "female",
        options: [
            { text: "Yes, most of the time I haven't been able to cope at all", score: 3 },
            { text: "Yes, sometimes I haven't been coping as well as usual", score: 2 },
            { text: "No, most of the time I have coped quite well", score: 1 },
            { text: "No, I have been coping as well as ever", score: 0 }
        ],
        uiType: "radio"
    },
    {
        text: "I have had difficulty sleeping even when I have the opportunity to sleep",
        category: "postpartum",
        type: "scale",
        minAge: 18,
        maxAge: 120,
        gender: "female",
        options: [
            { text: "Yes, most of the time", score: 3 },
            { text: "Yes, quite often", score: 2 },
            { text: "Not very often", score: 1 },
            { text: "No, not at all", score: 0 }
        ],
        uiType: "radio"
    },
    {
        text: "I have felt sad or miserable",
        category: "postpartum",
        type: "scale",
        minAge: 18,
        maxAge: 120,
        gender: "female",
        options: [
            { text: "Yes, most of the time", score: 3 },
            { text: "Yes, quite often", score: 2 },
            { text: "Not very often", score: 1 },
            { text: "No, not at all", score: 0 }
        ],
        uiType: "radio"
    },
    {
        text: "I have felt so unhappy that I have been crying",
        category: "postpartum",
        type: "scale",
        minAge: 18,
        maxAge: 120,
        gender: "female",
        options: [
            { text: "Yes, most of the time", score: 3 },
            { text: "Yes, quite often", score: 2 },
            { text: "Only occasionally", score: 1 },
            { text: "No, never", score: 0 }
        ],
        uiType: "radio"
    },
    {
        text: "The thought of harming myself has occurred to me",
        category: "postpartum",
        type: "scale",
        minAge: 18,
        maxAge: 120,
        gender: "female",
        options: [
            { text: "Yes, quite often", score: 3 },
            { text: "Sometimes", score: 2 },
            { text: "Hardly ever", score: 1 },
            { text: "Never", score: 0 }
        ],
        uiType: "radio"
    }
];

const scoringTable = [
    { min: 0, max: 7, interpretation: "Depression not likely" },
    { min: 8, max: 11, interpretation: "Depression possible" },
    { min: 12, max: 13, interpretation: "Fairly high possibility of depression" },
    { min: 14, max: 30, interpretation: "Probable depression" }
];

const seedPostpartum = async () => {
    try {
        const master = await Master.findOne({ slug: 'postpartum' });
        const masterId = master ? master._id : null;
        const masterSlug = master ? master.slug : 'postpartum';

        await Question.deleteMany({ category: masterSlug });
        await StandardizedScore.deleteMany({ category: masterSlug });

        const questionsToSeed = postpartumQuestions.map(q => ({
            ...q,
            master: masterId
        }));

        // Sequential create to trigger save hooks for questionId
        for (const q of questionsToSeed) {
            await Question.create(q);
        }
        console.log(`  ✅ ${questionsToSeed.length} Postpartum Depression questions seeded`);

        const scoresToSeed = [];
        for (let raw = 0; raw <= 30; raw++) {
            const tableEntry = scoringTable.find(t => raw >= t.min && raw <= t.max);
            scoresToSeed.push({
                category: masterSlug,
                master: masterId,
                rawScore: raw,
                tScore: (raw / 30) * 100, // Derived percentage for visual representation
                standardError: 0,
                interpretation: tableEntry ? tableEntry.interpretation : "Unknown"
            });
        }

        await StandardizedScore.insertMany(scoresToSeed);
        console.log(`  📊 ${scoresToSeed.length} Postpartum Depression interpretation records seeded`);

    } catch (err) {
        console.error('  ❌ Postpartum Seeder Error:', err.message);
        throw err;
    }
};

module.exports = seedPostpartum;
