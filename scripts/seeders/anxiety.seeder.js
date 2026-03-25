const Question = require('../../src/models/Question');
const Master = require('../../src/models/Master');
const StandardizedScore = require('../../src/models/StandardizedScore');

const anxietyQuestions = [
    { 
        text: "I felt fearful.", 
        patientText: "I felt scared or afraid without a clear reason.", 
        professionalText: "Patient reports subjective experience of fear or apprehension.",
        category: "anxiety", type: "scale", minAge: 11, maxAge: 120, gender: "all", uiType: "radio" 
    },
    { 
        text: "I felt anxious.", 
        patientText: "I felt nervous, jittery, or on edge.", 
        professionalText: "Patient manifests symptoms of generalized anxiety or autonomic arousal.",
        category: "anxiety", type: "scale", minAge: 11, maxAge: 120, gender: "all", uiType: "radio" 
    },
    { 
        text: "I felt worried.", 
        patientText: "I had a hard time stopping myself from worrying about things.", 
        professionalText: "Presence of excessive worry or apprehensive expectation regarding multiple life domains.",
        category: "anxiety", type: "scale", minAge: 11, maxAge: 120, gender: "all", uiType: "radio" 
    },
    { 
        text: "I found it hard to focus on anything other than my anxiety.", 
        patientText: "My worries were so strong that I couldn't think about anything else.", 
        professionalText: "Anxiety-related cognitive interference; difficulty redirecting attention from stressors.",
        category: "anxiety", type: "scale", minAge: 11, maxAge: 120, gender: "all", uiType: "radio" 
    },
    { 
        text: "I felt nervous.", 
        patientText: "I felt like something bad was about to happen or felt very tense.", 
        professionalText: "Subjective reports of nervousness or state-anxiety.",
        category: "anxiety", type: "scale", minAge: 11, maxAge: 120, gender: "all", uiType: "radio" 
    },
    { 
        text: "I felt uneasy.", 
        patientText: "I felt uncomfortable or restless, like I couldn't relax.", 
        professionalText: "Psychomotor agitation or subjective sense of unease/restlessness.",
        category: "anxiety", type: "scale", minAge: 11, maxAge: 120, gender: "all", uiType: "radio" 
    },
    { 
        text: "I felt tense.", 
        patientText: "My muscles felt tight and I felt physically stressed.", 
        professionalText: "Muscle tension and somatic manifestations of anxiety.",
        category: "anxiety", type: "scale", minAge: 11, maxAge: 120, gender: "all", uiType: "radio" 
    }
];

const options = [
    { text: "Never", score: 1 },
    { text: "Rarely", score: 2 },
    { text: "Sometimes", score: 3 },
    { text: "Often", score: 4 },
    { text: "Always", score: 5 }
];

const tScoreTable = [
    { raw: 7, t: 36.3, se: 5.4 },
    { raw: 8, t: 42.1, se: 3.4 },
    { raw: 9, t: 44.7, se: 2.9 },
    { raw: 10, t: 46.7, se: 2.6 },
    { raw: 11, t: 48.4, se: 2.4 },
    { raw: 12, t: 49.9, se: 2.3 },
    { raw: 13, t: 51.3, se: 2.3 },
    { raw: 14, t: 52.6, se: 2.2 },
    { raw: 15, t: 53.8, se: 2.2 },
    { raw: 16, t: 55.1, se: 2.2 },
    { raw: 17, t: 56.3, se: 2.2 },
    { raw: 18, t: 57.6, se: 2.2 },
    { raw: 19, t: 58.8, se: 2.2 },
    { raw: 20, t: 60.0, se: 2.2 },
    { raw: 21, t: 61.3, se: 2.2 },
    { raw: 22, t: 62.6, se: 2.2 },
    { raw: 23, t: 63.8, se: 2.2 },
    { raw: 24, t: 65.1, se: 2.2 },
    { raw: 25, t: 66.4, se: 2.2 },
    { raw: 26, t: 67.7, se: 2.2 },
    { raw: 27, t: 68.9, se: 2.2 },
    { raw: 28, t: 70.2, se: 2.2 },
    { raw: 29, t: 71.5, se: 2.2 },
    { raw: 30, t: 72.9, se: 2.2 },
    { raw: 31, t: 74.3, se: 2.2 },
    { raw: 32, t: 75.8, se: 2.3 },
    { raw: 33, t: 77.4, se: 2.4 },
    { raw: 34, t: 79.5, se: 2.7 },
    { raw: 35, t: 82.7, se: 3.5 }
];

const getInterpretation = (tScore) => {
    if (tScore < 55) return 'None to slight';
    if (tScore < 60) return 'Mild';
    if (tScore < 70) return 'Moderate';
    return 'Severe';
};

const seedAnxiety = async () => {
    try {
        const master = await Master.findOne({ slug: 'anxiety' });
        const masterId = master ? master._id : null;
        const masterSlug = master ? master.slug : 'anxiety';

        await Question.deleteMany({ category: masterSlug });
        await StandardizedScore.deleteMany({ category: masterSlug });

        const questionsToSeed = anxietyQuestions.map(q => ({
            ...q,
            options,
            category: masterSlug,
            master: masterId
        }));

        await Question.create(questionsToSeed);
        console.log(`  ✅ ${questionsToSeed.length} Anxiety questions seeded`);

        const scoresToSeed = tScoreTable.map(item => ({
            category: masterSlug,
            master: masterId,
            rawScore: item.raw,
            tScore: item.t,
            standardError: item.se,
            interpretation: getInterpretation(item.t)
        }));

        await StandardizedScore.insertMany(scoresToSeed);
        console.log(`  📊 ${scoresToSeed.length} Anxiety T-score records seeded`);

    } catch (err) {
        console.error('  ❌ Anxiety Seeder Error:', err.message);
        throw err;
    }
};

module.exports = seedAnxiety;
