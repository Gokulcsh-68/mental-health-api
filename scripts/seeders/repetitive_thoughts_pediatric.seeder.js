const Question = require('../../src/models/Question');
const Master = require('../../src/models/Master');
const StandardizedScore = require('../../src/models/StandardizedScore');

const repetitivePediatricQuestions = [
    {
        text: "On average, how much time is occupied by these thoughts or behaviors each day?",
        category: "repetitive_thoughts_pediatric",
        type: "scale",
        minAge: 11,
        maxAge: 17,
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
        text: "How much do they bother you?",
        category: "repetitive_thoughts_pediatric",
        type: "scale",
        minAge: 11,
        maxAge: 17,
        gender: "all",
        options: [
            { text: "None", score: 0 },
            { text: "Mild (slightly upsetting)", score: 1 },
            { text: "Moderate (upsetting but still manageable)", score: 2 },
            { text: "Severe (very upsetting)", score: 3 },
            { text: "Extreme (overwhelming distress)", score: 4 }
        ],
        uiType: "radio"
    },
    {
        text: "How hard is it for you to control them?",
        category: "repetitive_thoughts_pediatric",
        type: "scale",
        minAge: 11,
        maxAge: 17,
        gender: "all",
        options: [
            { text: "Complete control", score: 0 },
            { text: "Much control (usually able to control thoughts or behaviors)", score: 1 },
            { text: "Moderate control (sometimes able to control thoughts or behaviors)", score: 2 },
            { text: "Little control (not usually able to control thoughts or behaviors)", score: 3 },
            { text: "No control (unable to control thoughts or behaviors)", score: 4 }
        ],
        uiType: "radio"
    },
    {
        text: "How much do they cause you to avoid doing things, going places or being with people?",
        category: "repetitive_thoughts_pediatric",
        type: "scale",
        minAge: 11,
        maxAge: 17,
        gender: "all",
        options: [
            { text: "No avoidance", score: 0 },
            { text: "Mild (occasionally avoids things)", score: 1 },
            { text: "Moderate (regularly avoids doing these things)", score: 2 },
            { text: "Severe (frequently avoids these things)", score: 3 },
            { text: "Extreme (nearly complete avoidance; can’t leave the house)", score: 4 }
        ],
        uiType: "radio"
    },
    {
        text: "How much do they interfere with school, your social or family life, or your job?",
        category: "repetitive_thoughts_pediatric",
        type: "scale",
        minAge: 11,
        maxAge: 17,
        gender: "all",
        options: [
            { text: "None", score: 0 },
            { text: "Mild (slight interference)", score: 1 },
            { text: "Moderate (definite interference with functioning, but can still manage)", score: 2 },
            { text: "Severe (substantial interference)", score: 3 },
            { text: "Extreme (near-total interference)", score: 4 }
        ],
        uiType: "radio"
    }
];

const getInterpretation = (rawScore) => {
    const avg = rawScore / 5;
    let label = '';
    if (avg < 0.5) label = 'None';
    else if (avg < 1.5) label = 'Mild';
    else if (avg < 2.5) label = 'Moderate';
    else if (avg < 3.5) label = 'Severe';
    else label = 'Extreme';

    // Clinical Alert
    if (rawScore >= 8) {
        label += " - Consider detailed assessment for Obsessive Compulsive Disorder (OCD)";
    } else {
        label += " - Less likely clinically significant OCD symptoms";
    }

    return label;
};

const seedRepetitiveThoughtsPediatric = async () => {
    try {
        const master = await Master.findOne({ slug: 'repetitive_thoughts_pediatric' });
        const masterId = master ? master._id : null;
        const masterSlug = master ? master.slug : 'repetitive_thoughts_pediatric';

        await Question.deleteMany({ category: masterSlug });
        await StandardizedScore.deleteMany({ category: masterSlug });

        const questionsToSeed = repetitivePediatricQuestions.map(q => ({
            ...q,
            master: masterId
        }));

        for (const q of questionsToSeed) {
            await Question.create(q);
        }
        console.log(`  ✅ ${questionsToSeed.length} Repetitive Thoughts (Pediatric) questions seeded`);

        const scoresToSeed = [];
        for (let raw = 0; raw <= 20; raw++) {
            scoresToSeed.push({
                category: masterSlug,
                master: masterId,
                rawScore: raw,
                tScore: (raw / 20) * 100, // Visual percentage
                standardError: 0,
                interpretation: getInterpretation(raw)
            });
        }

        await StandardizedScore.insertMany(scoresToSeed);
        console.log(`  📊 ${scoresToSeed.length} Repetitive Thoughts (Pediatric) interpretation records seeded`);

    } catch (err) {
        console.error('  ❌ Repetitive Thoughts (Pediatric) Seeder Error:', err.message);
        throw err;
    }
};

module.exports = seedRepetitiveThoughtsPediatric;
