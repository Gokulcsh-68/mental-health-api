const Question = require('../../src/models/Question');
const Master = require('../../src/models/Master');
const StandardizedScore = require('../../src/models/StandardizedScore');

const sleepQuestions = [
    {
        text: "My sleep was restless.",
        patientText: "I felt like I was tossing and turning a lot during the night.",
        professionalText: "Patient reports fragmented sleep or increased nocturnal motor activity.",
        category: "sleep",
        type: "scale",
        minAge: 11,
        maxAge: 120,
        gender: "all",
        options: [
            { text: "Not at all", score: 1 },
            { text: "A little bit", score: 2 },
            { text: "Somewhat", score: 3 },
            { text: "Quite a bit", score: 4 },
            { text: "Very much", score: 5 }
        ],
        uiType: "radio"
    },
    {
        text: "I was satisfied with my sleep.",
        patientText: "I felt happy and satisfied with how well I slept.",
        professionalText: "Subjective sleep quality assessment; patient satisfaction with continuity and duration.",
        category: "sleep",
        type: "scale",
        minAge: 11,
        maxAge: 120,
        gender: "all",
        options: [
            { text: "Not at all", score: 5 },
            { text: "A little bit", score: 4 },
            { text: "Somewhat", score: 3 },
            { text: "Quite a bit", score: 2 },
            { text: "Very much", score: 1 }
        ],
        uiType: "radio"
    },
    {
        text: "My sleep was refreshing.",
        patientText: "I felt energetic and 'woke up on the right side of the bed'.",
        professionalText: "Sleep restorativeness; patient reports feeling refreshed upon awakening.",
        category: "sleep",
        type: "scale",
        minAge: 11,
        maxAge: 120,
        gender: "all",
        options: [
            { text: "Not at all", score: 5 },
            { text: "A little bit", score: 4 },
            { text: "Somewhat", score: 3 },
            { text: "Quite a bit", score: 2 },
            { text: "Very much", score: 1 }
        ],
        uiType: "radio"
    },
    {
        text: "I had difficulty falling asleep.",
        patientText: "I had a hard time drifting off to sleep once I got into bed.",
        professionalText: "Sleep onset latency issues reported; difficulty transitioning from wakefulness to N1 sleep.",
        category: "sleep",
        type: "scale",
        minAge: 11,
        maxAge: 120,
        gender: "all",
        options: [
            { text: "Not at all", score: 1 },
            { text: "A little bit", score: 2 },
            { text: "Somewhat", score: 3 },
            { text: "Quite a bit", score: 4 },
            { text: "Very much", score: 5 }
        ],
        uiType: "radio"
    },
    {
        text: "I had trouble staying asleep.",
        patientText: "I kept waking up during the night or had trouble getting back to sleep.",
        professionalText: "Sleep maintenance difficulty; presence of frequent awakenings or prolonged wake after sleep onset (WASO).",
        category: "sleep",
        type: "scale",
        minAge: 11,
        maxAge: 120,
        gender: "all",
        options: [
            { text: "Never", score: 1 },
            { text: "Rarely", score: 2 },
            { text: "Sometimes", score: 3 },
            { text: "Often", score: 4 },
            { text: "Always", score: 5 }
        ],
        uiType: "radio"
    },
    {
        text: "I had trouble sleeping.",
        patientText: "I struggled with my sleep in general lately.",
        professionalText: "Global sleep disturbance reported by patient.",
        category: "sleep",
        type: "scale",
        minAge: 11,
        maxAge: 120,
        gender: "all",
        options: [
            { text: "Never", score: 1 },
            { text: "Rarely", score: 2 },
            { text: "Sometimes", score: 3 },
            { text: "Often", score: 4 },
            { text: "Always", score: 5 }
        ],
        uiType: "radio"
    },
    {
        text: "I got enough sleep.",
        patientText: "I felt like I got the right amount of sleep to function well.",
        professionalText: "Subjective sleep sufficiency; patient perceives duration as adequate for physiological needs.",
        category: "sleep",
        type: "scale",
        minAge: 11,
        maxAge: 120,
        gender: "all",
        options: [
            { text: "Never", score: 5 },
            { text: "Rarely", score: 4 },
            { text: "Sometimes", score: 3 },
            { text: "Often", score: 2 },
            { text: "Always", score: 1 }
        ],
        uiType: "radio"
    },
    {
        text: "My sleep quality was",
        patientText: "Thinking about my sleep overall, it was:",
        professionalText: "Global assessment of sleep quality over the reporting period.",
        category: "sleep",
        type: "scale",
        minAge: 11,
        maxAge: 120,
        gender: "all",
        options: [
            { text: "Very Poor", score: 5 },
            { text: "Poor", score: 4 },
            { text: "Fair", score: 3 },
            { text: "Good", score: 2 },
            { text: "Very good", score: 1 }
        ],
        uiType: "radio"
    }
];

const tScoreTable = [
    { raw: 8, t: 28.9, se: 4.0 },
    { raw: 9, t: 33.1, se: 3.7 },
    { raw: 10, t: 35.9, se: 3.3 },
    { raw: 11, t: 38.0, se: 3.0 },
    { raw: 12, t: 39.8, se: 2.9 },
    { raw: 13, t: 41.4, se: 2.8 },
    { raw: 14, t: 42.9, se: 2.7 },
    { raw: 15, t: 44.2, se: 2.7 },
    { raw: 16, t: 45.5, se: 2.6 },
    { raw: 17, t: 46.7, se: 2.6 },
    { raw: 18, t: 47.9, se: 2.6 },
    { raw: 19, t: 49.0, se: 2.6 },
    { raw: 20, t: 50.1, se: 2.5 },
    { raw: 21, t: 51.2, se: 2.5 },
    { raw: 22, t: 52.2, se: 2.5 },
    { raw: 23, t: 53.3, se: 2.5 },
    { raw: 24, t: 54.3, se: 2.5 },
    { raw: 25, t: 55.3, se: 2.5 },
    { raw: 26, t: 56.3, se: 2.5 },
    { raw: 27, t: 57.3, se: 2.5 },
    { raw: 28, t: 58.3, se: 2.5 },
    { raw: 29, t: 59.4, se: 2.5 },
    { raw: 30, t: 60.4, se: 2.5 },
    { raw: 31, t: 61.5, se: 2.5 },
    { raw: 32, t: 62.6, se: 2.5 },
    { raw: 33, t: 63.7, se: 2.6 },
    { raw: 34, t: 64.9, se: 2.6 },
    { raw: 35, t: 66.1, se: 2.7 },
    { raw: 36, t: 67.5, se: 2.8 },
    { raw: 37, t: 69.0, se: 3.0 },
    { raw: 38, t: 70.8, se: 3.2 },
    { raw: 39, t: 73.0, se: 3.5 },
    { raw: 40, t: 76.5, se: 4.4 }
];

const getInterpretation = (tScore) => {
    if (tScore < 55) return 'None to slight';
    if (tScore < 60) return 'Mild';
    if (tScore < 70) return 'Moderate';
    return 'Severe';
};

const seedSleep = async () => {
    try {
        const master = await Master.findOne({ slug: 'sleep' });
        const masterId = master ? master._id : null;
        const masterSlug = master ? master.slug : 'sleep';

        await Question.deleteMany({ category: masterSlug });
        await StandardizedScore.deleteMany({ category: masterSlug });

        const questionsToSeed = sleepQuestions.map(q => ({
            ...q,
            category: masterSlug,
            master: masterId
        }));

        await Question.create(questionsToSeed);
        console.log(`  ✅ ${questionsToSeed.length} Sleep questions seeded`);

        const scoresToSeed = tScoreTable.map(item => ({
            category: masterSlug,
            master: masterId,
            rawScore: item.raw,
            tScore: item.t,
            standardError: item.se,
            interpretation: getInterpretation(item.t)
        }));

        await StandardizedScore.insertMany(scoresToSeed);
        console.log(`  📊 ${scoresToSeed.length} Sleep T-score records seeded`);

    } catch (err) {
        console.error('  ❌ Sleep Seeder Error:', err.message);
        throw err;
    }
};

module.exports = seedSleep;
