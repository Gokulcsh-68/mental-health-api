const Question = require('../../src/models/Question');
const Master = require('../../src/models/Master');
const StandardizedScore = require('../../src/models/StandardizedScore');

const autismQuestions = [
    // SECTION A: Social Communication
    { text: "How often do you find it difficult to understand what others are thinking or feeling?", category: "autism_spectrum" },
    { text: "How often do you have difficulty making or keeping friends?", category: "autism_spectrum" },
    { text: "How often do you have trouble understanding social rules (like taking turns in conversation)?", category: "autism_spectrum" },
    { text: "How often do you prefer to be alone rather than with others?", category: "autism_spectrum" },
    { text: "How often do you have difficulty with eye contact?", category: "autism_spectrum" },
    { text: "How often do you take things literally or have trouble understanding jokes/sarcasm?", category: "autism_spectrum" },

    // SECTION B: Restricted/Repetitive Behaviors
    { text: "How often do you have very strong interests in specific topics?", category: "autism_spectrum" },
    { text: "How often do you prefer to do things the same way or follow routines?", category: "autism_spectrum" },
    { text: "How often do you get very upset when routines change?", category: "autism_spectrum" },
    { text: "How often do you have repetitive movements (hand flapping, rocking, spinning)?", category: "autism_spectrum" },
    { text: "How often are you very sensitive to sounds, textures, lights, or smells?", category: "autism_spectrum" },
    { text: "How often do you focus intensely on parts of objects rather than the whole?", category: "autism_spectrum" }
];

const options = [
    { text: "Never/Rarely", score: 0 },
    { text: "Sometimes", score: 1 },
    { text: "Often", score: 2 },
    { text: "Almost always", score: 3 }
];

const getInterpretation = (rawScore) => {
    if (rawScore <= 7) return 'Low likelihood of ASD';
    if (rawScore <= 14) return 'Some autistic traits - Monitor development';
    if (rawScore <= 21) return 'Moderate likelihood - Professional evaluation recommended';
    return 'High likelihood - Comprehensive diagnostic evaluation strongly recommended';
};

const seedAutismSpectrum = async () => {
    try {
        const master = await Master.findOne({ slug: 'autism_spectrum' });
        const masterId = master ? master._id : null;
        const masterSlug = master ? master.slug : 'autism_spectrum';

        await Question.deleteMany({ category: masterSlug });
        await StandardizedScore.deleteMany({ category: masterSlug });

        const questionsToSeed = autismQuestions.map(q => ({
            ...q,
            type: "scale",
            minAge: 11,
            maxAge: 17,
            gender: "all",
            uiType: "radio",
            options,
            master: masterId
        }));

        for (const q of questionsToSeed) {
            await Question.create(q);
        }
        console.log(`  ✅ ${questionsToSeed.length} Autism Spectrum questions seeded`);

        const scoresToSeed = [];
        // Max raw is 12 * 3 = 36
        for (let raw = 0; raw <= 36; raw++) {
            scoresToSeed.push({
                category: masterSlug,
                master: masterId,
                rawScore: raw,
                tScore: (raw / 36) * 100, // Visual percentage
                standardError: 0,
                interpretation: getInterpretation(raw)
            });
        }

        await StandardizedScore.insertMany(scoresToSeed);
        console.log(`  📊 ${scoresToSeed.length} Autism Spectrum interpretation records seeded`);

    } catch (err) {
        console.error('  ❌ Autism Spectrum Seeder Error:', err.message);
        throw err;
    }
};

module.exports = seedAutismSpectrum;
