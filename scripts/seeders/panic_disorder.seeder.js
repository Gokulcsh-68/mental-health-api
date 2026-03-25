const Question = require('../../src/models/Question');
const Master = require('../../src/models/Master');
const StandardizedScore = require('../../src/models/StandardizedScore');

const panicDisorderQuestions = [
    { text: "During the PAST 7 DAYS, I have felt moments of sudden terror, fear, or fright", category: "panic_disorder", type: "scale", minAge: 18, maxAge: 120, gender: "all", uiType: "radio" },
    { text: "During the PAST 7 DAYS, I have felt anxious, worried, or nervous about having more panic attacks", category: "panic_disorder", type: "scale", minAge: 18, maxAge: 120, gender: "all", uiType: "radio" },
    { text: "During the PAST 7 DAYS, I have had thoughts of losing control, dying, going crazy, or other bad things happening because of panic attacks", category: "panic_disorder", type: "scale", minAge: 18, maxAge: 120, gender: "all", uiType: "radio" },
    { text: "During the PAST 7 DAYS, I have felt a racing heart, sweaty, trouble breathing, faint, or shaky", category: "panic_disorder", type: "scale", minAge: 18, maxAge: 120, gender: "all", uiType: "radio" },
    { text: "During the PAST 7 DAYS, I have felt tense muscles, felt on edge or restless, or had trouble relaxing or trouble sleeping", category: "panic_disorder", type: "scale", minAge: 18, maxAge: 120, gender: "all", uiType: "radio" },
    { text: "During the PAST 7 DAYS, I have avoided, or did not approach or enter, situations in which panic attacks might occur", category: "panic_disorder", type: "scale", minAge: 18, maxAge: 120, gender: "all", uiType: "radio" },
    { text: "During the PAST 7 DAYS, I have left situations early, or participated only minimally, because of panic attacks", category: "panic_disorder", type: "scale", minAge: 18, maxAge: 120, gender: "all", uiType: "radio" },
    { text: "During the PAST 7 DAYS, I have spent a lot of time preparing for, or procrastinating about (putting off), situations in which panic attacks might occur", category: "panic_disorder", type: "scale", minAge: 18, maxAge: 120, gender: "all", uiType: "radio" },
    { text: "During the PAST 7 DAYS, I have distracted myself to avoid thinking about panic attacks", category: "panic_disorder", type: "scale", minAge: 18, maxAge: 120, gender: "all", uiType: "radio" },
    { text: "During the PAST 7 DAYS, I have needed help to cope with panic attacks (e.g., alcohol or medication, superstitious objects, other people)", category: "panic_disorder", type: "scale", minAge: 18, maxAge: 120, gender: "all", uiType: "radio" }
];

const options = [
    { text: "Never", score: 0 },
    { text: "Occasionally", score: 1 },
    { text: "Half of the time", score: 1 },
    { text: "Most of the time", score: 2 },
    { text: "All of the time", score: 3 }
];

const getInterpretation = (rawScore) => {
    const avg = rawScore / 10;
    if (avg < 0.5) return 'None';
    if (avg < 1.5) return 'Mild';
    if (avg < 2.5) return 'Moderate';
    if (avg < 3.5) return 'Severe';
    return 'Extreme';
};

const seedPanicDisorder = async () => {
    try {
        const master = await Master.findOne({ slug: 'panic_disorder' });
        const masterId = master ? master._id : null;
        const masterSlug = master ? master.slug : 'panic_disorder';

        await Question.deleteMany({ category: masterSlug });
        await StandardizedScore.deleteMany({ category: masterSlug });

        const questionsToSeed = panicDisorderQuestions.map(q => ({
            ...q,
            options,
            master: masterId
        }));

        for (const q of questionsToSeed) {
            await Question.create(q);
        }
        console.log(`  ✅ ${questionsToSeed.length} Panic Disorder questions seeded`);

        const scoresToSeed = [];
        // Max raw is 30 based on 10 * 3. 
        // We'll seed up to 40 as provided in range 0-40, though only 0-30 is reachable with current scores.
        for (let raw = 0; raw <= 40; raw++) {
            scoresToSeed.push({
                category: masterSlug,
                master: masterId,
                rawScore: raw,
                tScore: (raw / 30) * 100, // Visual percentage based on actually reachable max
                standardError: 0,
                interpretation: getInterpretation(raw)
            });
        }

        await StandardizedScore.insertMany(scoresToSeed);
        console.log(`  📊 ${scoresToSeed.length} Panic Disorder interpretation records seeded`);

    } catch (err) {
        console.error('  ❌ Panic Disorder Seeder Error:', err.message);
        throw err;
    }
};

module.exports = seedPanicDisorder;
