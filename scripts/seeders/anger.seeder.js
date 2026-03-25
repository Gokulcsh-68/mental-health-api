const Question = require('../../src/models/Question');
const Master = require('../../src/models/Master');
const StandardizedScore = require('../../src/models/StandardizedScore');

const angerQuestions = [
    {
        text: "I was irritated more than people knew.",
        category: "anger",
        type: "scale",
        minAge: 18,
        gender: "all",
        options: [
            { text: "Never", score: 1 },
            { text: "Rarely", score: 2 },
            { text: "Sometimes", score: 3 },
            { text: "Often", score: 4 },
            { text: "Always", score: 5 }
        ],
        uiType: "radio"
    },
    {
        text: "I felt angry.",
        category: "anger",
        type: "scale",
        minAge: 18,
        gender: "all",
        options: [
            { text: "Never", score: 1 },
            { text: "Rarely", score: 2 },
            { text: "Sometimes", score: 3 },
            { text: "Often", score: 4 },
            { text: "Always", score: 5 }
        ],
        uiType: "radio"
    },
    {
        text: "I felt like I was ready to explode.",
        category: "anger",
        type: "scale",
        minAge: 18,
        gender: "all",
        options: [
            { text: "Never", score: 1 },
            { text: "Rarely", score: 2 },
            { text: "Sometimes", score: 3 },
            { text: "Often", score: 4 },
            { text: "Always", score: 5 }
        ],
        uiType: "radio"
    },
    {
        text: "I was grouchy.",
        category: "anger",
        type: "scale",
        minAge: 18,
        gender: "all",
        options: [
            { text: "Never", score: 1 },
            { text: "Rarely", score: 2 },
            { text: "Sometimes", score: 3 },
            { text: "Often", score: 4 },
            { text: "Always", score: 5 }
        ],
        uiType: "radio"
    },
    {
        text: "I felt annoyed.",
        category: "anger",
        type: "scale",
        minAge: 18,
        gender: "all",
        options: [
            { text: "Never", score: 1 },
            { text: "Rarely", score: 2 },
            { text: "Sometimes", score: 3 },
            { text: "Often", score: 4 },
            { text: "Always", score: 5 }
        ],
        uiType: "radio"
    }
];

const tScoreTable = [
    { raw: 5, t: 32.9 },
    { raw: 6, t: 38.1 },
    { raw: 7, t: 41.3 },
    { raw: 8, t: 44.0 },
    { raw: 9, t: 46.3 },
    { raw: 10, t: 48.4 },
    { raw: 11, t: 50.5 },
    { raw: 12, t: 52.6 },
    { raw: 13, t: 54.7 },
    { raw: 14, t: 56.7 },
    { raw: 15, t: 58.8 },
    { raw: 16, t: 60.8 },
    { raw: 17, t: 62.9 },
    { raw: 18, t: 65.0 },
    { raw: 19, t: 67.2 },
    { raw: 20, t: 69.4 },
    { raw: 21, t: 71.7 },
    { raw: 22, t: 74.1 },
    { raw: 23, t: 76.8 },
    { raw: 24, t: 79.7 },
    { raw: 25, t: 83.3 }
];

const getInterpretation = (tScore) => {
    if (tScore < 55) return 'None to slight';
    if (tScore < 60) return 'Mild';
    if (tScore < 70) return 'Moderate';
    return 'Severe';
};

const seedAnger = async () => {
    try {
        const master = await Master.findOne({ slug: 'anger' });
        const masterId = master ? master._id : null;
        const masterSlug = master ? master.slug : 'anger';

        await Question.deleteMany({ category: masterSlug });
        await StandardizedScore.deleteMany({ category: masterSlug });

        // Seed Questions
        const questionsToSeed = angerQuestions.map(q => ({
            ...q,
            category: masterSlug,
            master: masterId
        }));

        await Question.create(questionsToSeed);
        console.log(`  ✅ ${questionsToSeed.length} Anger questions seeded`);

        // Seed T-scores
        const scoresToSeed = tScoreTable.map(item => ({
            category: masterSlug,
            master: masterId,
            rawScore: item.raw,
            tScore: item.t,
            interpretation: getInterpretation(item.t)
        }));

        await StandardizedScore.insertMany(scoresToSeed);
        console.log(`  📊 ${scoresToSeed.length} Anger T-score records seeded`);

    } catch (err) {
        console.error('  ❌ Anger Seeder Error:', err.message);
        throw err;
    }
};

module.exports = seedAnger;
