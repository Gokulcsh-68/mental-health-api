const Question = require('../../src/models/Question');
const Master = require('../../src/models/Master');

const otherQuestions = [
    {
        text: "Overall, how would you rate your mood today?",
        category: "general",
        type: "scale",
        minAge: 5,
        gender: "all",
        options: [
            { text: "Very Happy", score: 0 },
            { text: "Neutral", score: 1 },
            { text: "Bit Sad", score: 2 },
            { text: "Very Low", score: 3 }
        ],
        uiType: "radio"
    },
    {
        text: "Did you manage to do something for yourself today (hobby, rest, etc)?",
        category: "general",
        type: "boolean",
        minAge: 5,
        gender: "all",
        options: [
            { text: "Yes", score: 0 },
            { text: "No", score: 1 }
        ],
        uiType: "radio"
    },
    {
        text: "How stressed are you feeling about work or school right now?",
        category: "stress",
        type: "scale",
        minAge: 10,
        gender: "all",
        options: [
            { text: "Not at all", score: 0 },
            { text: "Manageable", score: 1 },
            { text: "Very Stressed", score: 2 },
            { text: "Burned Out", score: 3 }
        ],
        uiType: "radio"
    }
];

const seedOther = async () => {
    try {
        const master = await Master.findOne({ slug: 'general' });
        const masterId = master ? master._id : null;
        const masterSlug = master ? master.slug : 'general';

        await Question.deleteMany({ category: { $in: [masterSlug, 'stress'] }, text: { $in: otherQuestions.map(q => q.text) } });

        const questionsToSeed = otherQuestions.map(q => ({
            ...q,
            category: q.category === 'general' ? masterSlug : q.category,
            master: q.category === 'general' ? masterId : null
        }));

        await Question.create(questionsToSeed);
        console.log(`  ✅ ${questionsToSeed.length} General/Other questions seeded`);
    } catch (err) {
        console.error('  ❌ Other Seeder Error:', err.message);
        throw err;
    }
};

module.exports = seedOther;
