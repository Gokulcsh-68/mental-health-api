const Question = require('../../src/models/Question');
const Master = require('../../src/models/Master');
const StandardizedScore = require('../../src/models/StandardizedScore');

const angerPediatricQuestions = [
    {
        text: "I felt mad.",
        category: "anger_pediatric",
        type: "scale",
        minAge: 11,
        maxAge: 17,
        gender: "all",
        options: [
            { text: "Never", score: 1 },
            { text: "Almost Never", score: 2 },
            { text: "Sometimes", score: 3 },
            { text: "Often", score: 4 },
            { text: "Almost Always", score: 4 } // User requested score 4 for both
        ],
        uiType: "radio"
    },
    {
        text: "I was so angry I felt like throwing something.",
        category: "anger_pediatric",
        type: "scale",
        minAge: 11,
        maxAge: 17,
        gender: "all",
        options: [
            { text: "Never", score: 1 },
            { text: "Almost Never", score: 2 },
            { text: "Sometimes", score: 3 },
            { text: "Often", score: 4 },
            { text: "Almost Always", score: 4 }
        ],
        uiType: "radio"
    },
    {
        text: "I was so angry I felt like yelling at somebody.",
        category: "anger_pediatric",
        type: "scale",
        minAge: 11,
        maxAge: 17,
        gender: "all",
        options: [
            { text: "Never", score: 1 },
            { text: "Almost Never", score: 2 },
            { text: "Sometimes", score: 3 },
            { text: "Often", score: 4 },
            { text: "Almost Always", score: 4 }
        ],
        uiType: "radio"
    },
    {
        text: "When I got mad, I stayed mad.",
        category: "anger_pediatric",
        type: "scale",
        minAge: 11,
        maxAge: 17,
        gender: "all",
        options: [
            { text: "Never", score: 1 },
            { text: "Almost Never", score: 2 },
            { text: "Sometimes", score: 3 },
            { text: "Often", score: 4 },
            { text: "Almost Always", score: 4 }
        ],
        uiType: "radio"
    },
    {
        text: "I felt fed up.",
        category: "anger_pediatric",
        type: "scale",
        minAge: 11,
        maxAge: 17,
        gender: "all",
        options: [
            { text: "Never", score: 1 },
            { text: "Almost Never", score: 2 },
            { text: "Sometimes", score: 3 },
            { text: "Often", score: 4 },
            { text: "Almost Always", score: 4 }
        ],
        uiType: "radio"
    },
    {
        text: "I felt upset.",
        category: "anger_pediatric",
        type: "scale",
        minAge: 11,
        maxAge: 17,
        gender: "all",
        options: [
            { text: "Never", score: 1 },
            { text: "Almost Never", score: 2 },
            { text: "Sometimes", score: 3 },
            { text: "Often", score: 4 },
            { text: "Almost Always", score: 4 }
        ],
        uiType: "radio"
    }
];

const tScoreTable = [
    { raw: 6, t: 31.1, se: 5.8 },
    { raw: 7, t: 35.9, se: 5.1 },
    { raw: 8, t: 39.0, se: 4.9 },
    { raw: 9, t: 41.7, se: 4.7 },
    { raw: 10, t: 44.2, se: 4.6 },
    { raw: 11, t: 46.4, se: 4.5 },
    { raw: 12, t: 48.5, se: 4.4 },
    { raw: 13, t: 50.5, se: 4.4 },
    { raw: 14, t: 52.4, se: 4.3 },
    { raw: 15, t: 54.2, se: 4.3 },
    { raw: 16, t: 56.0, se: 4.3 },
    { raw: 17, t: 57.7, se: 4.3 },
    { raw: 18, t: 59.5, se: 4.3 },
    { raw: 19, t: 61.2, se: 4.3 },
    { raw: 20, t: 62.9, se: 4.3 },
    { raw: 21, t: 64.6, se: 4.2 },
    { raw: 22, t: 66.3, se: 4.2 },
    { raw: 23, t: 68.0, se: 4.2 },
    { raw: 24, t: 69.8, se: 4.2 },
    { raw: 25, t: 71.6, se: 4.2 },
    { raw: 26, t: 73.4, se: 4.3 },
    { raw: 27, t: 75.4, se: 4.3 },
    { raw: 28, t: 77.5, se: 4.5 },
    { raw: 29, t: 79.8, se: 4.6 },
    { raw: 30, t: 82.7, se: 4.9 }
];

const getInterpretation = (tScore) => {
    if (tScore < 55) return 'None to slight';
    if (tScore < 60) return 'Mild';
    if (tScore < 70) return 'Moderate';
    return 'Severe';
};

const seedAngerPediatric = async () => {
    try {
        const master = await Master.findOne({ slug: 'anger_pediatric' });
        const masterId = master ? master._id : null;
        const masterSlug = master ? master.slug : 'anger_pediatric';

        await Question.deleteMany({ category: masterSlug });
        await StandardizedScore.deleteMany({ category: masterSlug });

        const questionsToSeed = angerPediatricQuestions.map(q => ({
            ...q,
            master: masterId
        }));

        for (const q of questionsToSeed) {
            await Question.create(q);
        }
        console.log(`  ✅ ${questionsToSeed.length} Anger (Pediatric) questions seeded`);

        const scoresToSeed = tScoreTable.map(item => ({
            category: masterSlug,
            master: masterId,
            rawScore: item.raw,
            tScore: item.t,
            standardError: item.se,
            interpretation: getInterpretation(item.t)
        }));

        await StandardizedScore.insertMany(scoresToSeed);
        console.log(`  📊 ${scoresToSeed.length} Anger (Pediatric) T-score records seeded`);

    } catch (err) {
        console.error('  ❌ Anger (Pediatric) Seeder Error:', err.message);
        throw err;
    }
};

module.exports = seedAngerPediatric;
