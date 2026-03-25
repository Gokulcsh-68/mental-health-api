const Question = require('../../src/models/Question');
const Master = require('../../src/models/Master');
const StandardizedScore = require('../../src/models/StandardizedScore');

const personalityQuestions = [
    { text: "People would describe me as reckless." },
    { text: "I feel like I act totally on impulse." },
    { text: "Even though I know better, I can’t stop making rash decisions." },
    { text: "I often feel like nothing I do really matters." },
    { text: "Others see me as irresponsible." },
    { text: "I’m not good at planning ahead." },
    { text: "My thoughts often don’t make sense to others." },
    { text: "I worry about almost everything." },
    { text: "I get emotional easily, often for very little reason." },
    { text: "I fear being alone in life more than anything else." },
    { text: "I get stuck on one way of doing things, even when it’s clear it won’t work." },
    { text: "I have seen things that weren’t really there." },
    { text: "I steer clear of romantic relationships." },
    { text: "I’m not interested in making friends." },
    { text: "I get irritated easily by all sorts of things." },
    { text: "I don’t like to get too close to people." },
    { text: "It’s no big deal if I hurt other peoples’ feelings." },
    { text: "I rarely get enthusiastic about anything." },
    { text: "I crave attention." },
    { text: "I often have to deal with people who are less important than me." },
    { text: "I often have thoughts that make sense to me but that other people say are strange." },
    { text: "I use people to get what I want." },
    { text: "I often 'zone out' and then suddenly come to and realize that a lot of time has passed." },
    { text: "Things around me often feel unreal, or more real than usual." },
    { text: "It is easy for me to take advantage of others." }
];

const options = [
    { text: "Very False / Often False", score: 0 },
    { text: "Sometimes / Somewhat False", score: 1 },
    { text: "Sometimes / Somewhat True", score: 2 },
    { text: "Very True / Often True", score: 3 }
];

const getInterpretation = (rawScore) => {
    // Avg score = raw / 25
    if (rawScore <= 12) return 'Low / Minimal trait elevation';    // Avg 0.0 - 0.48
    if (rawScore <= 37) return 'Mild trait elevation';           // Avg 0.52 - 1.48
    if (rawScore <= 62) return 'Moderate trait elevation';       // Avg 1.52 - 2.48
    return 'Severe trait elevation';                             // Avg 2.52 - 3.0
};

const seedPersonalityInventory = async () => {
    try {
        const master = await Master.findOne({ slug: 'personality_inventory' });
        const masterId = master ? master._id : null;
        const masterSlug = master ? master.slug : 'personality_inventory';

        await Question.deleteMany({ category: masterSlug });
        await StandardizedScore.deleteMany({ category: masterSlug });

        const questionsToSeed = personalityQuestions.map(q => ({
            text: q.text,
            category: masterSlug,
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
        console.log(`  ✅ ${questionsToSeed.length} Personality Inventory questions seeded`);

        const scoresToSeed = [];
        // Max raw is 25 * 3 = 75
        for (let raw = 0; raw <= 75; raw++) {
            scoresToSeed.push({
                category: masterSlug,
                master: masterId,
                rawScore: raw,
                tScore: (raw / 75) * 100, // Visual percentage
                standardError: 0,
                interpretation: getInterpretation(raw)
            });
        }

        await StandardizedScore.insertMany(scoresToSeed);
        console.log(`  📊 ${scoresToSeed.length} Personality Inventory interpretation records seeded`);

    } catch (err) {
        console.error('  ❌ Personality Inventory Seeder Error:', err.message);
        throw err;
    }
};

module.exports = seedPersonalityInventory;
