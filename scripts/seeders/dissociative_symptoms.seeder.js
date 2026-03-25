const Question = require('../../src/models/Question');
const Master = require('../../src/models/Master');
const StandardizedScore = require('../../src/models/StandardizedScore');

const dissociativeQuestions = [
    { text: "I find myself staring into space and thinking of nothing." },
    { text: "People, objects, or the world around me seem strange or unreal." },
    { text: "I find that I did things that I do not remember doing." },
    { text: "When I am alone, I talk out loud to myself." },
    { text: "I feel as though I were looking at the world through a fog so that people and things seem far away or unclear." },
    { text: "I am able to ignore pain." },
    { text: "I act so differently from one situation to another that it is almost as if I were two different people." },
    { text: "I can do things very easily that would usually be hard for me." }
];

const options = [
    { text: "Not at all", score: 0 },
    { text: "Once or twice", score: 1 },
    { text: "Almost every day", score: 2 },
    { text: "About once a day", score: 3 },
    { text: "More than once a day", score: 4 }
];

const getInterpretation = (rawScore) => {
    // Avg score = raw / 8
    if (rawScore <= 3) return 'None';             // Avg 0.0 - 0.4
    if (rawScore <= 11) return 'Mild';            // Avg 0.5 - 1.4
    if (rawScore <= 19) return 'Moderate';        // Avg 1.5 - 2.4
    if (rawScore <= 27) return 'Severe';          // Avg 2.5 - 3.4
    return 'Extreme';                             // Avg 3.5 - 4.0
};

const seedDissociativeSymptoms = async () => {
    try {
        const master = await Master.findOne({ slug: 'dissociative_symptoms' });
        const masterId = master ? master._id : null;
        const masterSlug = master ? master.slug : 'dissociative_symptoms';

        await Question.deleteMany({ category: masterSlug });
        await StandardizedScore.deleteMany({ category: masterSlug });

        const questionsToSeed = dissociativeQuestions.map(q => ({
            text: q.text,
            category: masterSlug,
            type: "scale",
            minAge: 18,
            maxAge: 120,
            gender: "all",
            uiType: "radio",
            options,
            master: masterId
        }));

        for (const q of questionsToSeed) {
            await Question.create(q);
        }
        console.log(`  ✅ ${questionsToSeed.length} Dissociative Symptoms questions seeded`);

        const scoresToSeed = [];
        // Max raw is 8 * 4 = 32
        for (let raw = 0; raw <= 32; raw++) {
            scoresToSeed.push({
                category: masterSlug,
                master: masterId,
                rawScore: raw,
                tScore: (raw / 32) * 100, // Visual percentage
                standardError: 0,
                interpretation: getInterpretation(raw)
            });
        }

        await StandardizedScore.insertMany(scoresToSeed);
        console.log(`  📊 ${scoresToSeed.length} Dissociative Symptoms interpretation records seeded`);

    } catch (err) {
        console.error('  ❌ Dissociative Symptoms Seeder Error:', err.message);
        throw err;
    }
};

module.exports = seedDissociativeSymptoms;
