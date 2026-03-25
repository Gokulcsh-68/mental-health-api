const Question = require('../../src/models/Question');
const Master = require('../../src/models/Master');
const StandardizedScore = require('../../src/models/StandardizedScore');

const anxietyPediatricQuestions = [
    { text: "I felt like something awful might happen.", category: "anxiety_pediatric", type: "scale", minAge: 11, maxAge: 17, gender: "all", uiType: "radio" },
    { text: "I felt nervous.", category: "anxiety_pediatric", type: "scale", minAge: 11, maxAge: 17, gender: "all", uiType: "radio" },
    { text: "I felt scared.", category: "anxiety_pediatric", type: "scale", minAge: 11, maxAge: 17, gender: "all", uiType: "radio" },
    { text: "I felt worried.", category: "anxiety_pediatric", type: "scale", minAge: 11, maxAge: 17, gender: "all", uiType: "radio" },
    { text: "I worried about what could happen to me.", category: "anxiety_pediatric", type: "scale", minAge: 11, maxAge: 17, gender: "all", uiType: "radio" },
    { text: "I worried when I went to bed at night.", category: "anxiety_pediatric", type: "scale", minAge: 11, maxAge: 17, gender: "all", uiType: "radio" },
    { text: "I got scared really easy.", category: "anxiety_pediatric", type: "scale", minAge: 11, maxAge: 17, gender: "all", uiType: "radio" },
    { text: "I was afraid of going to school.", category: "anxiety_pediatric", type: "scale", minAge: 11, maxAge: 17, gender: "all", uiType: "radio" },
    { text: "I was worried I might die.", category: "anxiety_pediatric", type: "scale", minAge: 11, maxAge: 17, gender: "all", uiType: "radio" },
    { text: "I woke up at night scared.", category: "anxiety_pediatric", type: "scale", minAge: 11, maxAge: 17, gender: "all", uiType: "radio" },
    { text: "I worried when I was at home.", category: "anxiety_pediatric", type: "scale", minAge: 11, maxAge: 17, gender: "all", uiType: "radio" },
    { text: "I worried when I was away from home.", category: "anxiety_pediatric", type: "scale", minAge: 11, maxAge: 17, gender: "all", uiType: "radio" },
    { text: "It was hard for me to relax.", category: "anxiety_pediatric", type: "scale", minAge: 11, maxAge: 17, gender: "all", uiType: "radio" }
];

const options = [
    { text: "Never", score: 1 },
    { text: "Almost Never", score: 2 },
    { text: "Sometimes", score: 3 },
    { text: "Often", score: 4 },
    { text: "Almost Always", score: 5 }
];

const tScoreTable = [
    { raw: 13, t: 32.3 }, { raw: 14, t: 36.6 }, { raw: 15, t: 38.9 }, { raw: 16, t: 41.1 },
    { raw: 17, t: 42.8 }, { raw: 18, t: 44.3 }, { raw: 19, t: 45.7 }, { raw: 20, t: 47 },
    { raw: 21, t: 48.2 }, { raw: 22, t: 49.4 }, { raw: 23, t: 50.4 }, { raw: 24, t: 51.4 },
    { raw: 25, t: 52.4 }, { raw: 26, t: 53.3 }, { raw: 27, t: 54.2 }, { raw: 28, t: 55.1 },
    { raw: 29, t: 56 }, { raw: 30, t: 56.8 }, { raw: 31, t: 57.6 }, { raw: 32, t: 58.4 },
    { raw: 33, t: 59.2 }, { raw: 34, t: 60 }, { raw: 35, t: 60.8 }, { raw: 36, t: 61.6 },
    { raw: 37, t: 62.3 }, { raw: 38, t: 63.1 }, { raw: 39, t: 63.8 }, { raw: 40, t: 64.5 },
    { raw: 41, t: 65.3 }, { raw: 42, t: 66 }, { raw: 43, t: 66.8 }, { raw: 44, t: 67.5 },
    { raw: 45, t: 68.2 }, { raw: 46, t: 69 }, { raw: 47, t: 69.7 }, { raw: 48, t: 70.5 },
    { raw: 49, t: 71.3 }, { raw: 50, t: 72 }, { raw: 51, t: 72.8 }, { raw: 52, t: 73.6 },
    { raw: 53, t: 74.4 }, { raw: 54, t: 75.3 }, { raw: 55, t: 76.1 }, { raw: 56, t: 77 },
    { raw: 57, t: 77.9 }, { raw: 58, t: 78.9 }, { raw: 59, t: 79.9 }, { raw: 60, t: 81 },
    { raw: 61, t: 82.1 }, { raw: 62, t: 83.3 }, { raw: 63, t: 84.7 }, { raw: 64, t: 86.1 },
    { raw: 65, t: 88 }
];

const getInterpretation = (tScore) => {
    if (tScore < 55) return 'None to slight';
    if (tScore < 60) return 'Mild';
    if (tScore < 70) return 'Moderate';
    return 'Severe';
};

const seedAnxietyPediatric = async () => {
    try {
        const master = await Master.findOne({ slug: 'anxiety_pediatric' });
        const masterId = master ? master._id : null;
        const masterSlug = master ? master.slug : 'anxiety_pediatric';

        await Question.deleteMany({ category: masterSlug });
        await StandardizedScore.deleteMany({ category: masterSlug });

        const questionsToSeed = anxietyPediatricQuestions.map(q => ({
            ...q,
            options,
            master: masterId
        }));

        for (const q of questionsToSeed) {
            await Question.create(q);
        }
        console.log(`  ✅ ${questionsToSeed.length} Anxiety (Pediatric) questions seeded`);

        const scoresToSeed = tScoreTable.map(item => ({
            category: masterSlug,
            master: masterId,
            rawScore: item.raw,
            tScore: item.t,
            standardError: 0,
            interpretation: getInterpretation(item.t)
        }));

        await StandardizedScore.insertMany(scoresToSeed);
        console.log(`  📊 ${scoresToSeed.length} Anxiety (Pediatric) T-score records seeded`);

    } catch (err) {
        console.error('  ❌ Anxiety (Pediatric) Seeder Error:', err.message);
        throw err;
    }
};

module.exports = seedAnxietyPediatric;
