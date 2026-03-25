const Question = require('../../src/models/Question');
const Master = require('../../src/models/Master');
const StandardizedScore = require('../../src/models/StandardizedScore');

const repetitiveQuestions = [
    {
        text: "On average, how much time is occupied by these thoughts or behaviors each day?",
        category: "repetitive_thoughts",
        type: "scale",
        minAge: 18,
        maxAge: 120,
        gender: "all",
        options: [
            { text: "None", score: 0 },
            { text: "Mild (Less than an hour a day)", score: 1 },
            { text: "Moderate (1 to 3 hours a day)", score: 2 },
            { text: "Severe (3 to 8 hours a day)", score: 3 },
            { text: "Extreme (more than 8 hours a day)", score: 4 }
        ],
        uiType: "radio"
    },
    {
        text: "How much distress do these thoughts or behaviors cause you?",
        category: "repetitive_thoughts",
        type: "scale",
        minAge: 18,
        maxAge: 120,
        gender: "all",
        options: [
            { text: "None", score: 0 },
            { text: "Mild (slightly disturbing)", score: 1 },
            { text: "Moderate (disturbing but still manageable)", score: 2 },
            { text: "Severe (very disturbing)", score: 3 },
            { text: "Extreme (overwhelming distress)", score: 4 }
        ],
        uiType: "radio"
    },
    {
        text: "How hard is it for you to control these thoughts or behaviors?",
        category: "repetitive_thoughts",
        type: "scale",
        minAge: 18,
        maxAge: 120,
        gender: "all",
        options: [
            { text: "Complete control", score: 0 },
            { text: "Much control (usually able to control thoughts or behaviors)", score: 1 },
            { text: "Moderate control (sometimes able to control thoughts or behaviors)", score: 2 },
            { text: "Little control (infrequently able to control thoughts or behaviors)", score: 3 },
            { text: "No control (unable to control thoughts or behaviors)", score: 4 }
        ],
        uiType: "radio"
    },
    {
        text: "How much do these thoughts or behaviors cause you to avoid doing anything, going anyplace, or being with anyone?",
        category: "repetitive_thoughts",
        type: "scale",
        minAge: 18,
        maxAge: 120,
        gender: "all",
        options: [
            { text: "No avoidance", score: 0 },
            { text: "Mild (occasional avoidance)", score: 1 },
            { text: "Moderate (regularly avoid doing these things)", score: 2 },
            { text: "Severe (frequent and extensive avoidance)", score: 3 },
            { text: "Extreme (nearly complete avoidance; house-bound)", score: 4 }
        ],
        uiType: "radio"
    },
    {
        text: "How much do these thoughts or behaviors interfere with school, work, or your social or family life?",
        category: "repetitive_thoughts",
        type: "scale",
        minAge: 18,
        maxAge: 120,
        gender: "all",
        options: [
            { text: "None", score: 0 },
            { text: "Mild (slight interference)", score: 1 },
            { text: "Moderate (definite interference with functioning, but still manageable)", score: 2 },
            { text: "Severe (substantial interference)", score: 3 },
            { text: "Extreme", score: 4 }
        ],
        uiType: "radio"
    }
];

const getInterpretation = (rawScore) => {
    const avg = rawScore / 5;
    let label = '';
    if (avg < 1) label = 'None';
    else if (avg < 2) label = 'Mild';
    else if (avg < 3) label = 'Moderate';
    else if (avg < 4) label = 'Severe';
    else label = 'Extreme';

    if (rawScore >= 8) {
        return `${label} - Consider detailed assessment for Obsessive Compulsive Disorder (OCD)`;
    }
    return label;
};

const seedRepetitiveThoughts = async () => {
    try {
        const master = await Master.findOne({ slug: 'repetitive_thoughts' });
        const masterId = master ? master._id : null;
        const masterSlug = master ? master.slug : 'repetitive_thoughts';

        await Question.deleteMany({ category: masterSlug });
        await StandardizedScore.deleteMany({ category: masterSlug });

        const questionsToSeed = repetitiveQuestions.map(q => ({
            ...q,
            master: masterId
        }));

        await Question.create(questionsToSeed);
        console.log(`  ✅ ${questionsToSeed.length} Repetitive Thoughts questions seeded`);

        const scoresToSeed = [];
        for (let raw = 0; raw <= 20; raw++) {
            scoresToSeed.push({
                category: masterSlug,
                master: masterId,
                rawScore: raw,
                tScore: 50, // Default tScore if not provided
                standardError: 0,
                interpretation: getInterpretation(raw)
            });
        }

        await StandardizedScore.insertMany(scoresToSeed);
        console.log(`  📊 ${scoresToSeed.length} Repetitive Thoughts interpretation records seeded`);

    } catch (err) {
        console.error('  ❌ Repetitive Thoughts Seeder Error:', err.message);
        throw err;
    }
};

module.exports = seedRepetitiveThoughts;
