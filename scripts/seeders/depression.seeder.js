const Question = require('../../src/models/Question');
const StandardizedScore = require('../../src/models/StandardizedScore');
const Master = require('../../src/models/Master');

const depressionQuestions = [
    {
        text: "I felt worthless.",
        patientText: "I felt like I didn't matter or had no value.",
        professionalText: "Presence of profound feelings of worthlessness or excessive/inappropriate guilt.",
        category: "depression",
        type: "scale",
        minAge: 11,
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
        text: "I felt that I had nothing to look forward to.",
        patientText: "I felt like my future looked bleak or had nothing to get excited about.",
        professionalText: "Patient exhibits signs of hopelessness and lack of future-oriented thinking.",
        category: "depression",
        type: "scale",
        minAge: 11,
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
        text: "I felt helpless.",
        patientText: "I felt like I was powerless to change my situation or make things better.",
        professionalText: "Patient exhibits signs of helplessness and a lack of control over their situation.",
        category: "depression",
        type: "scale",
        minAge: 11,
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
        text: "I felt sad.",
        patientText: "I felt a deep sense of sadness or blue throughout the day.",
        professionalText: "Patient reports subjective experience of depressed mood or pervasive sadness.",
        category: "depression",
        type: "scale",  
        minAge: 11,
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
        text: "I felt like a failure.",
        patientText: "I felt like I had failed at my goals or let others down.",
        professionalText: "Self-critical ideation and pervasive sense of failure or inadequacy.",
        category: "depression",
        type: "scale",
        minAge: 11,
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
        text: "I felt depressed.",
        patientText: "I felt low, down, or flat on most days.",
        professionalText: "Reported onset and persistence of depressive episodes/symptoms.",
        category: "depression",
        type: "scale",
        minAge: 11,
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
        text: "I felt unhappy.",
        patientText: "I struggled to find joy or felt unhappy with my life currently.",
        professionalText: "Pervasive anhedonia or subjective state of unhappiness.",
        category: "depression",
        type: "scale",
        minAge: 11,
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
        text: "I felt hopeless.",
        patientText: "I felt like things would never get better and that there was no hope.",
        professionalText: "Patient manifests significant hopelessness; potential risk marker for severe depression.",
        category: "depression",
        type: "scale",
        minAge: 11,
        gender: "all",
        options: [
            { text: "Never", score: 1 },
            { text: "Rarely", score: 2 },
            { text: "Sometimes", score: 3 },
            { text: "Often", score: 4 },
            { text: "Always", score: 5 }
        ],
        uiType: "radio"
    }
];

const tScoreTable = [
    { raw: 8, t: 38.1, se: 5.5 },
    { raw: 9, t: 43.3, se: 3.4 },
    { raw: 10, t: 46.2, se: 2.8 },
    { raw: 11, t: 48.2, se: 2.4 },
    { raw: 12, t: 49.8, se: 2.2 },
    { raw: 13, t: 51.0, se: 2.0 },
    { raw: 14, t: 52.3, se: 1.9 },
    { raw: 15, t: 53.4, se: 1.8 },
    { raw: 16, t: 54.3, se: 1.8 },
    { raw: 17, t: 55.3, se: 1.7 },
    { raw: 18, t: 56.2, se: 1.7 },
    { raw: 19, t: 57.1, se: 1.7 },
    { raw: 20, t: 57.9, se: 1.7 },
    { raw: 21, t: 58.8, se: 1.7 },
    { raw: 22, t: 59.7, se: 1.8 },
    { raw: 23, t: 60.7, se: 1.8 },
    { raw: 24, t: 61.6, se: 1.8 },
    { raw: 25, t: 62.5, se: 1.8 },
    { raw: 26, t: 63.5, se: 1.8 },
    { raw: 27, t: 64.4, se: 1.8 },
    { raw: 28, t: 65.4, se: 1.8 },
    { raw: 29, t: 66.4, se: 1.8 },
    { raw: 30, t: 67.4, se: 1.8 },
    { raw: 31, t: 68.3, se: 1.8 },
    { raw: 32, t: 69.3, se: 1.8 },
    { raw: 33, t: 70.4, se: 1.8 },
    { raw: 34, t: 71.4, se: 1.8 },
    { raw: 35, t: 72.5, se: 1.8 },
    { raw: 36, t: 73.6, se: 1.8 },
    { raw: 37, t: 74.8, se: 1.9 },
    { raw: 38, t: 76.2, se: 2.0 },
    { raw: 39, t: 77.9, se: 2.4 },
    { raw: 40, t: 81.1, se: 3.4 }
];

const getInterpretation = (tScore) => {
    if (tScore < 55) return 'None to slight';
    if (tScore < 60) return 'Mild';
    if (tScore < 70) return 'Moderate';
    return 'Severe';
};

const seedDepression = async () => {
    try {
        // Fetch the master record
        const master = await Master.findOne({ slug: 'depression' });
        const masterId = master ? master._id : null;
        const masterSlug = master ? master.slug : 'depression';

        await Question.deleteMany({ category: masterSlug });
        await StandardizedScore.deleteMany({ category: masterSlug });

        // Create questions with master mapping
        const questionsToSeed = depressionQuestions.map(q => ({
            ...q,
            category: masterSlug,
            master: masterId
        }));

        await Question.create(questionsToSeed);
        console.log(`  ✅ ${questionsToSeed.length} Depression questions seeded`);

        // Seed T-score table with master mapping
        const scoresToSeed = tScoreTable.map(item => ({
            category: masterSlug,
            master: masterId,
            rawScore: item.raw,
            tScore: item.t,
            standardError: item.se,
            interpretation: getInterpretation(item.t)
        }));

        await StandardizedScore.insertMany(scoresToSeed);
        console.log(`  📊 ${scoresToSeed.length} Depression T-score records seeded`);

    } catch (err) {
        console.error('  ❌ Depression Seeder Error:', err.message);
        throw err;
    }
};

module.exports = seedDepression;
