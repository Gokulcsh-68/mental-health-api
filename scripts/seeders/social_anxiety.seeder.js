const Question = require('../../src/models/Question');
const Master = require('../../src/models/Master');
const StandardizedScore = require('../../src/models/StandardizedScore');

const socialAnxietyQuestions = [
    { text: "Felt moments of sudden terror, fear, or fright in social situations", category: "social_anxiety", type: "scale", minAge: 18, maxAge: 120, gender: "all", uiType: "radio" },
    { text: "Felt anxious, worried, or nervous about social situations", category: "social_anxiety", type: "scale", minAge: 18, maxAge: 120, gender: "all", uiType: "radio" },
    { text: "Have had thoughts of being rejected, humiliated, embarrassed, ridiculed, or offending others", category: "social_anxiety", type: "scale", minAge: 18, maxAge: 120, gender: "all", uiType: "radio" },
    { text: "Felt a racing heart, sweaty, trouble breathing, faint, or shaky in social situations", category: "social_anxiety", type: "scale", minAge: 18, maxAge: 120, gender: "all", uiType: "radio" },
    { text: "Felt tense muscles, felt on edge or restless, or had trouble relaxing in social situations", category: "social_anxiety", type: "scale", minAge: 18, maxAge: 120, gender: "all", uiType: "radio" },
    { text: "Avoided, or did not approach or enter, social situations", category: "social_anxiety", type: "scale", minAge: 18, maxAge: 120, gender: "all", uiType: "radio" },
    { text: "Left social situations early or participated only minimally (e.g., said little, avoided eye contact)", category: "social_anxiety", type: "scale", minAge: 18, maxAge: 120, gender: "all", uiType: "radio" },
    { text: "Spent a lot of time preparing what to say or how to act in social situations", category: "social_anxiety", type: "scale", minAge: 18, maxAge: 120, gender: "all", uiType: "radio" },
    { text: "Distracted myself to avoid thinking about social situations", category: "social_anxiety", type: "scale", minAge: 18, maxAge: 120, gender: "all", uiType: "radio" },
    { text: "Needed help to cope with social situations (e.g., alcohol or medications, superstitious objects)", category: "social_anxiety", type: "scale", minAge: 18, maxAge: 120, gender: "all", uiType: "radio" },
    { text: "What is most distressing to you about the social anxiety symptoms you've been experiencing?", category: "social_anxiety", type: "choice", minAge: 18, maxAge: 120, gender: "all", uiType: "textarea", isOptional: true }
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

const seedSocialAnxiety = async () => {
    try {
        const master = await Master.findOne({ slug: 'social_anxiety' });
        const masterId = master ? master._id : null;
        const masterSlug = master ? master.slug : 'social_anxiety';

        await Question.deleteMany({ category: masterSlug });
        await StandardizedScore.deleteMany({ category: masterSlug });

        const questionsToSeed = socialAnxietyQuestions.map(q => ({
            ...q,
            options: q.type === 'scale' ? options : [],
            master: masterId
        }));

        for (const q of questionsToSeed) {
            await Question.create(q);
        }
        console.log(`  ✅ ${questionsToSeed.length} Social Anxiety questions seeded`);

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
        console.log(`  📊 ${scoresToSeed.length} Social Anxiety interpretation records seeded`);

    } catch (err) {
        console.error('  ❌ Social Anxiety Seeder Error:', err.message);
        throw err;
    }
};

module.exports = seedSocialAnxiety;
