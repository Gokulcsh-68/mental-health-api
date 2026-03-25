const Question = require('../../src/models/Question');
const Master = require('../../src/models/Master');
const StandardizedScore = require('../../src/models/StandardizedScore');

const agoraphobiaQuestions = [
    { text: "felt moments of sudden terror, fear, or fright in these situations", category: "agoraphobia", type: "scale", minAge: 11, maxAge: 17, gender: "all", uiType: "radio" },
    { text: "felt anxious, worried, or nervous about these situations", category: "agoraphobia", type: "scale", minAge: 11, maxAge: 17, gender: "all", uiType: "radio" },
    { text: "have had thoughts about panic attacks, uncomfortable physical sensations, getting lost, or being overcome with fear in these situations", category: "agoraphobia", type: "scale", minAge: 11, maxAge: 17, gender: "all", uiType: "radio" },
    { text: "felt a racing heart, sweaty, trouble breathing, faint, or shaky in these situations", category: "agoraphobia", type: "scale", minAge: 11, maxAge: 17, gender: "all", uiType: "radio" },
    { text: "felt tense muscles, felt on edge or restless, or had trouble relaxing in these situations", category: "agoraphobia", type: "scale", minAge: 11, maxAge: 17, gender: "all", uiType: "radio" },
    { text: "avoided, or did not approach or enter, these situations", category: "agoraphobia", type: "scale", minAge: 11, maxAge: 17, gender: "all", uiType: "radio" },
    { text: "moved away from these situations, left them early, or remained close to the exits", category: "agoraphobia", type: "scale", minAge: 11, maxAge: 17, gender: "all", uiType: "radio" },
    { text: "spent a lot of time preparing for, or procrastinating about (putting off), these situations", category: "agoraphobia", type: "scale", minAge: 11, maxAge: 17, gender: "all", uiType: "radio" },
    { text: "distracted myself to avoid thinking about these situations", category: "agoraphobia", type: "scale", minAge: 11, maxAge: 17, gender: "all", uiType: "radio" },
    { text: "needed help to cope with these situations (e.g., alcohol or medication, superstitious objects, other people)", category: "agoraphobia", type: "scale", minAge: 11, maxAge: 17, gender: "all", uiType: "radio" }
];

const options = [
    { text: "Never", score: 0 },
    { text: "Occasionally", score: 1 },
    { text: "Half of the time", score: 2 },
    { text: "Most of the time", score: 3 },
    { text: "All of the time", score: 4 }
];

const getInterpretation = (rawScore) => {
    const avg = rawScore / 10;
    if (avg < 0.5) return 'None';
    if (avg < 1.5) return 'Mild';
    if (avg < 2.5) return 'Moderate';
    if (avg < 3.5) return 'Severe';
    return 'Extreme';
};

const seedAgoraphobia = async () => {
    try {
        const master = await Master.findOne({ slug: 'agoraphobia' });
        const masterId = master ? master._id : null;
        const masterSlug = master ? master.slug : 'agoraphobia';

        await Question.deleteMany({ category: masterSlug });
        await StandardizedScore.deleteMany({ category: masterSlug });

        const questionsToSeed = agoraphobiaQuestions.map(q => ({
            ...q,
            options,
            master: masterId
        }));

        for (const q of questionsToSeed) {
            await Question.create(q);
        }
        console.log(`  ✅ ${questionsToSeed.length} Agoraphobia questions seeded`);

        const scoresToSeed = [];
        for (let raw = 0; raw <= 40; raw++) {
            scoresToSeed.push({
                category: masterSlug,
                master: masterId,
                rawScore: raw,
                tScore: (raw / 40) * 100, // Visual representation
                standardError: 0,
                interpretation: getInterpretation(raw)
            });
        }

        await StandardizedScore.insertMany(scoresToSeed);
        console.log(`  📊 ${scoresToSeed.length} Agoraphobia interpretation records seeded`);

    } catch (err) {
        console.error('  ❌ Agoraphobia Seeder Error:', err.message);
        throw err;
    }
};

module.exports = seedAgoraphobia;
