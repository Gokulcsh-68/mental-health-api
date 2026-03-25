const Question = require('../../src/models/Question');
const Master = require('../../src/models/Master');
const StandardizedScore = require('../../src/models/StandardizedScore');

const oddQuestions = [
    { text: "Often loses temper", category: "odd", type: "scale", minAge: 11, maxAge: 17, gender: "male", uiType: "radio" },
    { text: "Is often touchy or easily annoyed", category: "odd", type: "scale", minAge: 11, maxAge: 17, gender: "male", uiType: "radio" },
    { text: "Is often angry and resentful", category: "odd", type: "scale", minAge: 11, maxAge: 17, gender: "male", uiType: "radio" },
    { text: "Often argues with authority figures (parents, teachers, supervisors)", category: "odd", type: "scale", minAge: 11, maxAge: 17, gender: "male", uiType: "radio" },
    { text: "Often actively defies or refuses to comply with requests from authority figures or with rules", category: "odd", type: "scale", minAge: 11, maxAge: 17, gender: "male", uiType: "radio" },
    { text: "Often deliberately annoys people", category: "odd", type: "scale", minAge: 11, maxAge: 17, gender: "male", uiType: "radio" },
    { text: "Often blames others for his or her mistakes or misbehavior", category: "odd", type: "scale", minAge: 11, maxAge: 17, gender: "male", uiType: "radio" },
    { text: "Has been spiteful or vindictive at least twice within the past 6 months", category: "odd", type: "scale", minAge: 11, maxAge: 17, gender: "male", uiType: "radio" },
    { text: "Shows persistent pattern of negativistic, hostile, and defiant behavior", category: "odd", type: "scale", minAge: 11, maxAge: 17, gender: "male", uiType: "radio" }
];

const options = [
    { text: "Never", score: 0 },
    { text: "Occasionally", score: 1 },
    { text: "Often", score: 2 },
    { text: "Very Often", score: 2 }
];

const getInterpretation = (rawScore) => {
    if (rawScore <= 3) return 'Minimal/None';
    if (rawScore <= 7) return 'Mild';
    if (rawScore <= 12) return 'Moderate';
    return 'Severe';
};

const seedODD = async () => {
    try {
        const master = await Master.findOne({ slug: 'odd' });
        const masterId = master ? master._id : null;
        const masterSlug = master ? master.slug : 'odd';

        await Question.deleteMany({ category: masterSlug });
        await StandardizedScore.deleteMany({ category: masterSlug });

        const questionsToSeed = oddQuestions.map(q => ({
            ...q,
            options,
            master: masterId
        }));

        for (const q of questionsToSeed) {
            await Question.create(q);
        }
        console.log(`  ✅ ${questionsToSeed.length} ODD questions seeded`);

        const scoresToSeed = [];
        // Max possible raw score is 9 * 2 = 18.
        // Interpretation table goes up to 24+, so we seed up to that for robustness.
        for (let raw = 0; raw <= 25; raw++) {
            scoresToSeed.push({
                category: masterSlug,
                master: masterId,
                rawScore: raw,
                tScore: (raw / 18) * 100, // Visual percentage based on reachable max
                standardError: 0,
                interpretation: getInterpretation(raw)
            });
        }

        await StandardizedScore.insertMany(scoresToSeed);
        console.log(`  📊 ${scoresToSeed.length} ODD interpretation records seeded`);

    } catch (err) {
        console.error('  ❌ ODD Seeder Error:', err.message);
        throw err;
    }
};

module.exports = seedODD;
