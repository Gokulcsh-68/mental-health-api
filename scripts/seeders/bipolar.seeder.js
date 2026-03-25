const Question = require('../../src/models/Question');
const Master = require('../../src/models/Master');
const StandardizedScore = require('../../src/models/StandardizedScore');

const bipolarQuestions = [
    // PART 1: Manic/Hypomanic Symptoms (a-m)
    { text: "Has there ever been a period of time when you were not your usual self and: You felt so good or hyper that other people thought you were not your normal self or were so hyper that you got into trouble?", category: "bipolar", type: "boolean", minAge: 7, maxAge: 120, gender: "all", uiType: "radio" },
    { text: "Has there ever been a period of time when you were not your usual self and: You were so irritable that you shouted at people or started fights or arguments?", category: "bipolar", type: "boolean", minAge: 7, maxAge: 120, gender: "all", uiType: "radio" },
    { text: "Has there ever been a period of time when you were not your usual self and: You felt much more self-confident than usual?", category: "bipolar", type: "boolean", minAge: 7, maxAge: 120, gender: "all", uiType: "radio" },
    { text: "Has there ever been a period of time when you were not your usual self and: You got much less sleep than usual and found you didn't really miss it?", category: "bipolar", type: "boolean", minAge: 7, maxAge: 120, gender: "all", uiType: "radio" },
    { text: "Has there ever been a period of time when you were not your usual self and: You were much more talkative or spoke much faster than usual?", category: "bipolar", type: "boolean", minAge: 7, maxAge: 120, gender: "all", uiType: "radio" },
    { text: "Has there ever been a period of time when you were not your usual self and: Thoughts raced through your head or you couldn't slow your mind down?", category: "bipolar", type: "boolean", minAge: 7, maxAge: 120, gender: "all", uiType: "radio" },
    { text: "Has there ever been a period of time when you were not your usual self and: You were so easily distracted by things around you that you had trouble concentrating or staying on track?", category: "bipolar", type: "boolean", minAge: 7, maxAge: 120, gender: "all", uiType: "radio" },
    { text: "Has there ever been a period of time when you were not your usual self and: You had much more energy than usual?", category: "bipolar", type: "boolean", minAge: 7, maxAge: 120, gender: "all", uiType: "radio" },
    { text: "Has there ever been a period of time when you were not your usual self and: You were much more social or outgoing than usual, for example, you telephoned friends in the middle of the night?", category: "bipolar", type: "boolean", minAge: 7, maxAge: 120, gender: "all", uiType: "radio" },
    { text: "Has there ever been a period of time when you were not your usual self and: You were much more interested in sex than usual?", category: "bipolar", type: "boolean", minAge: 7, maxAge: 120, gender: "all", uiType: "radio" },
    { text: "Has there ever been a period of time when you were not your usual self and: You did things that were unusual for you or that other people might have thought were excessive, foolish, or risky?", category: "bipolar", type: "boolean", minAge: 7, maxAge: 120, gender: "all", uiType: "radio" },
    { text: "Has there ever been a period of time when you were not your usual self and: Spending money got you or your family into trouble?", category: "bipolar", type: "boolean", minAge: 7, maxAge: 120, gender: "all", uiType: "radio" },
    { text: "If you checked YES to more than one of the above, have several of these ever happened during the same period of time?", category: "bipolar", type: "boolean", minAge: 7, maxAge: 120, gender: "all", uiType: "radio" },

    // Q2: Impact
    {
        text: "How much of a problem did any of these cause you? (Like being unable to work; having family, money or legal troubles; getting into arguments or fights?)",
        category: "bipolar",
        type: "choice",
        minAge: 7,
        maxAge: 120,
        gender: "all",
        uiType: "radio",
        options: [
            { text: "No Problem", score: 0 },
            { text: "Minor Problem", score: 1 },
            { text: "Moderate Problem", score: 2 },
            { text: "Serious Problem", score: 3 }
        ]
    },

    // Q3 & Q4: History
    { text: "Have any of your blood relatives had manic-depressive illness or bipolar disorder? (Children, siblings, parents, grandparents, aunts, and uncles)", category: "bipolar", type: "boolean", minAge: 7, maxAge: 120, gender: "all", uiType: "radio" },
    { text: "Has a health professional ever told you that you have manic-depressive illness or bipolar disorder?", category: "bipolar", type: "boolean", minAge: 7, maxAge: 120, gender: "all", uiType: "radio" },

    // PART 2: Recent Symptoms (Last 3 Months)
    { text: "In the last three months, have you had a time when you've been grouchy or angry for several days in a row? (Getting really annoyed when people interrupt you or don't agree with you)", category: "recent_symptoms", type: "boolean", minAge: 7, maxAge: 120, gender: "all", uiType: "radio" },
    { text: "In the last three months, has there been a time when you felt very restless so that you had to keep walking around or be on the move all the time?", category: "recent_symptoms", type: "boolean", minAge: 7, maxAge: 120, gender: "all", uiType: "radio" },
    { text: "In the last three months, has there been a time when you talked too much or too quickly?", category: "recent_symptoms", type: "boolean", minAge: 7, maxAge: 120, gender: "all", uiType: "radio" },
    { text: "In the last three months, has there been a time when you thought you had special abilities or powers which made you stronger, smarter, or better than most other people?", category: "recent_symptoms", type: "boolean", minAge: 7, maxAge: 120, gender: "all", uiType: "radio" },
    { text: "In the last three months, has there been a time when you often felt like your mind was racing too quickly from one thought to another?", category: "recent_symptoms", type: "boolean", minAge: 7, maxAge: 120, gender: "all", uiType: "radio" },
    { text: "In the last three months, has there been a time when you felt so elated, excited, on top of the world, or hyper that other people thought you were not your normal self or that you got into trouble?", category: "recent_symptoms", type: "boolean", minAge: 7, maxAge: 120, gender: "all", uiType: "radio" }
];

const booleanOptions = [
    { text: "Yes", score: 1 },
    { text: "No", score: 0 }
];

const seedBipolar = async () => {
    try {
        const master = await Master.findOne({ slug: 'bipolar' });
        const masterId = master ? master._id : null;
        const masterSlug = master ? master.slug : 'bipolar';

        // Clear existing bipolar questions
        await Question.deleteMany({ category: { $in: ['bipolar', 'recent_symptoms'] } });

        const questionsToSeed = bipolarQuestions.map(q => {
            const seedObj = {
                ...q,
                master: masterId
            };
            // Only add default boolean options if q.options is NOT already provided (e.g. for Q2)
            if (!q.options) {
                seedObj.options = booleanOptions;
            }
            return seedObj;
        });

        await Question.create(questionsToSeed);
        console.log(`  ✅ ${questionsToSeed.length} Bipolar Disorder questions seeded`);

        await StandardizedScore.deleteMany({ category: masterSlug });

        const scoresToSeed = [];
        // Raw score is sum of Part 1 (13 questions)
        for (let raw = 0; raw <= 13; raw++) {
            let interpretation = 'Bipolar screening negative';
            if (raw >= 7) {
                interpretation = 'Bipolar screening positive - Requires confirmation of Symptom Clustering and Functional Impact';
            }
            scoresToSeed.push({
                category: masterSlug,
                master: masterId,
                rawScore: raw,
                tScore: (raw / 13) * 100,
                standardError: 0,
                interpretation
            });
        }

        await StandardizedScore.insertMany(scoresToSeed);
        console.log(`  📊 ${scoresToSeed.length} Bipolar Disorder interpretation records seeded`);

    } catch (err) {
        console.error('  ❌ Bipolar Seeder Error:', err.message);
        throw err;
    }
};

module.exports = seedBipolar;
