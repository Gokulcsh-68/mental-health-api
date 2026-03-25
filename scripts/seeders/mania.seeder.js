const Question = require('../../src/models/Question');
const Master = require('../../src/models/Master');
const StandardizedScore = require('../../src/models/StandardizedScore');

const maniaQuestions = [
    {
        text: "I do not feel happier or more cheerful than usual.",
        patientText: "I've been feeling unusually happy, 'high', or super cheerful lately.",
        professionalText: "Patient reports elevated or expansive mood; subjective experience of euphoria.",
        category: "mania",
        type: "scale",
        minAge: 11,
        maxAge: 120,
        gender: "all",
        options: [
            { text: "I do not feel happier or more cheerful than usual.", score: 1 },
            { text: "I occasionally feel happier or more cheerful than usual.", score: 2 },
            { text: "I often feel happier or more cheerful than usual.", score: 3 },
            { text: "I feel happier or more cheerful than usual most of the time.", score: 4 },
            { text: "I feel happier of more cheerful than usual all of the time.", score: 5 }
        ],
        uiType: "radio"
    },
    {
        text: "I do not feel more self-confident than usual.",
        patientText: "I've been feeling extremely confident in myself, maybe even more than is normal for me.",
        professionalText: "Presence of inflated self-esteem or grandiosity.",
        category: "mania",
        type: "scale",
        minAge: 11,
        maxAge: 120,
        gender: "all",
        options: [
            { text: "I do not feel more self-confident than usual.", score: 1 },
            { text: "I occasionally feel more self-confident than usual.", score: 2 },
            { text: "I often feel more self-confident than usual.", score: 3 },
            { text: "I frequently feel more self-confident than usual.", score: 4 },
            { text: "I feel extremely self-confident all of the time.", score: 5 }
        ],
        uiType: "radio"
    },
    {
        text: "I do not need less sleep than usual.",
        patientText: "I haven't felt the need to sleep as much as usual and I still have lots of energy.",
        professionalText: "Decreased need for sleep; patient remains functional with significantly reduced rest.",
        category: "mania",
        type: "scale",
        minAge: 11,
        maxAge: 120,
        gender: "all",
        options: [
            { text: "I do not need less sleep than usual.", score: 1 },
            { text: "I occasionally need less sleep than usual.", score: 2 },
            { text: "I often need less sleep than usual.", score: 3 },
            { text: "I frequently need less sleep than usual.", score: 4 },
            { text: "I can go all day and all night without any sleep and still not feel tired.", score: 5 }
        ],
        uiType: "radio"
    },
    {
        text: "I do not talk more than usual.",
        patientText: "I've been talking a lot more than I usually do, or talking very fast.",
        professionalText: "Logorrhea or pressured speech; increased talkativeness reported or observed.",
        category: "mania",
        type: "scale",
        minAge: 11,
        maxAge: 120,
        gender: "all",
        options: [
            { text: "I do not talk more than usual.", score: 1 },
            { text: "I occasionally talk more than usual.", score: 2 },
            { text: "I often talk more than usual.", score: 3 },
            { text: "I frequently talk more than usual.", score: 4 },
            { text: "I talk constantly and cannot be interrupted.", score: 5 }
        ],
        uiType: "radio"
    },
    {
        text: "I have not been more active (either socially, sexually, at work, home, or school) than usual.",
        patientText: "I've been way more busy or active than normal in my social life or projects.",
        professionalText: "Increase in goal-directed activity (social, work/school, or sexual) or psychomotor agitation.",
        category: "mania",
        type: "scale",
        minAge: 11,
        maxAge: 120,
        gender: "all",
        options: [
            { text: "I have not been more active (either socially, sexually, at work, home, or school) than usual.", score: 1 },
            { text: "I have occasionally been more active than usual.", score: 2 },
            { text: "I have often been more active than usual.", score: 3 },
            { text: "I have frequently been more active than usual.", score: 4 },
            { text: "I am constantly more active or on the go all the time.", score: 5 }
        ],
        uiType: "radio"
    }
];

const getInterpretation = (rawScore) => {
    if (rawScore <= 5) return 'Mania unlikely';
    return 'Possible mania – needs clinical attention';
};

const seedMania = async () => {
    try {
        const master = await Master.findOne({ slug: 'mania' });
        const masterId = master ? master._id : null;
        const masterSlug = master ? master.slug : 'mania';

        await Question.deleteMany({ category: masterSlug });
        await StandardizedScore.deleteMany({ category: masterSlug });

        // Seed Questions
        const questionsToSeed = maniaQuestions.map(q => ({
            ...q,
            category: masterSlug,
            master: masterId
        }));

        await Question.create(questionsToSeed);
        console.log(`  ✅ ${questionsToSeed.length} Mania questions seeded`);

        // Seed Standardized Scores (Raw Score Range 5 to 25)
        const scoresToSeed = [];
        for (let score = 5; score <= 25; score++) {
            scoresToSeed.push({
                category: masterSlug,
                master: masterId,
                rawScore: score,
                tScore: score, // Not explicitly provided, using raw as t for internal tracking
                interpretation: getInterpretation(score)
            });
        }

        await StandardizedScore.insertMany(scoresToSeed);
        console.log(`  📊 ${scoresToSeed.length} Mania score interpretation records seeded`);

    } catch (err) {
        console.error('  ❌ Mania Seeder Error:', err.message);
        throw err;
    }
};

module.exports = seedMania;
