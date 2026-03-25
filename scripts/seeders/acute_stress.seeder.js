const Question = require('../../src/models/Question');
const Master = require('../../src/models/Master');
const StandardizedScore = require('../../src/models/StandardizedScore');

const acuteStressQuestions = [
    { text: "Having 'flashbacks', that is, you suddenly acted or felt as if a stressful experience from the past was happening all over again?" },
    { text: "Feeling very emotionally upset when something reminded you of a stressful experience?" },
    { text: "Feeling detached or distant from yourself, your body, your physical surroundings, or your memories?" },
    { text: "Trying to avoid thoughts, feelings, or physical sensations that reminded you of a stressful experience?" },
    { text: "Being 'super alert', on guard, or constantly on the lookout for danger?" },
    { text: "Feeling jumpy or easily startled when you hear an unexpected noise?" },
    { text: "Being extremely irritable or angry to the point where you yelled at other people, got into fights, or destroyed things?" }
];

const options = [
    { text: "Not at all", score: 0 },
    { text: "A little bit", score: 1 },
    { text: "Moderately", score: 2 },
    { text: "Quite a bit", score: 3 },
    { text: "Extremely", score: 4 }
];

const getInterpretation = (rawScore) => {
    // Avg score = raw / 7
    // Mapping 0-4 scale to ranges
    if (rawScore <= 3) return 'None';             // Avg < 0.5
    if (rawScore <= 10) return 'Mild';            // Avg ~0.5 - 1.5
    if (rawScore <= 17) return 'Moderate';        // Avg ~1.6 - 2.5
    if (rawScore <= 24) return 'Severe';          // Avg ~2.6 - 3.5
    return 'Extreme';                             // Avg ~3.6 - 4.0
};

const seedAcuteStress = async () => {
    try {
        const master = await Master.findOne({ slug: 'acute_stress' });
        const masterId = master ? master._id : null;
        const masterSlug = master ? master.slug : 'acute_stress';

        await Question.deleteMany({ category: masterSlug });
        await StandardizedScore.deleteMany({ category: masterSlug });

        const questionsToSeed = acuteStressQuestions.map(q => ({
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
        console.log(`  ✅ ${questionsToSeed.length} Acute Stress questions seeded`);

        const scoresToSeed = [];
        // Max raw is 7 * 4 = 28
        for (let raw = 0; raw <= 28; raw++) {
            scoresToSeed.push({
                category: masterSlug,
                master: masterId,
                rawScore: raw,
                tScore: (raw / 28) * 100, // Visual percentage
                standardError: 0,
                interpretation: getInterpretation(raw)
            });
        }

        await StandardizedScore.insertMany(scoresToSeed);
        console.log(`  📊 ${scoresToSeed.length} Acute Stress interpretation records seeded`);

    } catch (err) {
        console.error('  ❌ Acute Stress Seeder Error:', err.message);
        throw err;
    }
};

module.exports = seedAcuteStress;
