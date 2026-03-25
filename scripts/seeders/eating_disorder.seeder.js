const Question = require('../../src/models/Question');
const Master = require('../../src/models/Master');
const StandardizedScore = require('../../src/models/StandardizedScore');

const eatingDisorderQuestions = [
    {
        text: "How much more or less do you feel you worry about your weight and body shape than other people your age?",
        category: "eating_disorder",
        type: "scale",
        uiType: "radio",
        options: [
            { text: "I worry a lot less than other people", score: 0 },
            { text: "I worry a little less than other people", score: 0 },
            { text: "I worry about the same as other people", score: 0 },
            { text: "I worry a little more than other people", score: 0 },
            { text: "I worry a lot more than other people", score: 0 }
        ]
    },
    {
        text: "How afraid are you of gaining 3 pounds?",
        category: "eating_disorder",
        type: "scale",
        uiType: "radio",
        options: [
            { text: "Not afraid of gaining", score: 0 },
            { text: "Slightly afraid of gaining", score: 0 },
            { text: "Moderately afraid of gaining", score: 0 },
            { text: "Very afraid of gaining", score: 0 },
            { text: "Terrified of gaining", score: 0 }
        ]
    },
    {
        text: "When was the last time you went on a diet?",
        category: "eating_disorder",
        type: "scale",
        uiType: "radio",
        options: [
            { text: "I have never been on a diet", score: 0 },
            { text: "I was on a diet about one year ago", score: 0 },
            { text: "I was on a diet about 6 months ago", score: 0 },
            { text: "I was on a diet about 3 months ago", score: 0 },
            { text: "I was on a diet about 1 month ago", score: 0 },
            { text: "I was on a diet less than 1 month ago", score: 0 },
            { text: "I’m on a diet now", score: 0 }
        ]
    },
    {
        text: "Compared to other things in your life, how important is your weight to you?",
        category: "eating_disorder",
        type: "scale",
        uiType: "radio",
        options: [
            { text: "My weight is not important compared to other things in my life", score: 0 },
            { text: "My weight is a little more important than some other things in my life", score: 0 },
            { text: "My weight is more important than most, but not all, things in my life", score: 0 },
            { text: "My weight is the most important thing in my life", score: 0 }
        ]
    },
    {
        text: "Do you ever feel fat?",
        category: "eating_disorder",
        type: "scale",
        uiType: "radio",
        options: [
            { text: "Never", score: 0 },
            { text: "Rarely", score: 0 },
            { text: "Sometimes", score: 0 },
            { text: "Often", score: 0 },
            { text: "Always", score: 0 }
        ]
    },
    { text: "In the past 3 months, how many times have you had a sense of loss of control AND you also ate what most people would regard as an unusually large amount of food at one time?", category: "eating_disorder", type: "choice", uiType: "textarea", isNumericInput: true },
    { text: "In the past 3 months, how many times have you made yourself throw-up as a means to control your weight and shape?", category: "eating_disorder", type: "choice", uiType: "textarea", isNumericInput: true },
    { text: "In the past 3 months, how many times have you used diuretics or laxatives as a means to control your weight and shape?", category: "eating_disorder", type: "choice", uiType: "textarea", isNumericInput: true },
    { text: "In the past 3 months, how many times have you exercised excessively as a means to control your weight and shape?", category: "eating_disorder", type: "choice", uiType: "textarea", isNumericInput: true },
    { text: "In the past 3 months, how many times have you fasted as a means to control your weight and shape?", category: "eating_disorder", type: "choice", uiType: "textarea", isNumericInput: true },

    // Risk triggering Yes/No
    { text: "Do you consume a small amount of food (i.e., less than 1200 calories/day) on a regular basis to influence your shape or weight?", category: "eating_disorder", type: "boolean" },
    { text: "Do you struggle with a lack of interest in eating or food?", category: "eating_disorder", type: "boolean" },
    { text: "Do you avoid certain or many foods because of such features as texture, consistency, temperature, or smell?", category: "eating_disorder", type: "boolean" },
    { text: "Do you avoid certain or many foods because of fear of experiencing negative consequences like choking or vomiting?", category: "eating_disorder", type: "boolean" },
    { text: "Have you experienced significant weight loss (or are at a low weight for your age and height) but are not overly concerned with the size or shape of your body?", category: "eating_disorder", type: "boolean" },

    {
        text: "Are you currently in treatment for an eating disorder?",
        category: "eating_disorder",
        type: "choice",
        uiType: "radio",
        options: [
            { text: "Yes", score: 0 },
            { text: "No", score: 0 },
            { text: "Not currently, but I have been in the past", score: 0 }
        ]
    },

    // Optional Stats
    { text: "(Optional) What is your current weight? (Select unit and enter number)", category: "eating_disorder", type: "choice", uiType: "textarea", isOptional: true },
    { text: "(Optional) What was your lowest weight in the past year, including today? (Select unit and enter number)", category: "eating_disorder", type: "choice", uiType: "textarea", isOptional: true },
    { text: "(Optional) What is your current height? (Enter Feet and Inches or CM)", category: "eating_disorder", type: "choice", uiType: "textarea", isOptional: true }
];

const booleanOptions = [
    { text: "Yes", score: 1 },
    { text: "No", score: 0 }
];

const seedEatingDisorder = async () => {
    try {
        const master = await Master.findOne({ slug: 'eating_disorder' });
        const masterId = master ? master._id : null;
        const masterSlug = master ? master.slug : 'eating_disorder';

        await Question.deleteMany({ category: masterSlug });
        await StandardizedScore.deleteMany({ category: masterSlug });

        const questionsToSeed = eatingDisorderQuestions.map(q => {
            const seedObj = {
                ...q,
                minAge: 11,
                maxAge: 17,
                gender: "all",
                master: masterId,
                isOptional: q.isOptional || false
            };
            if (q.type === 'boolean') {
                seedObj.options = booleanOptions;
            }
            return seedObj;
        });

        for (const q of questionsToSeed) {
            await Question.create(q);
        }
        console.log(`  ✅ ${questionsToSeed.length} Eating Disorder questions seeded`);

        const scoresToSeed = [];
        // Max risk score is 5 from the boolean questions
        for (let raw = 0; raw <= 5; raw++) {
            let interpretation = "Low risk";
            if (raw >= 2) {
                interpretation = "Possible eating disorder - professional evaluation recommended";
            }

            scoresToSeed.push({
                category: masterSlug,
                master: masterId,
                rawScore: raw,
                tScore: (raw / 5) * 100,
                standardError: 0,
                interpretation
            });
        }

        await StandardizedScore.insertMany(scoresToSeed);
        console.log(`  📊 ${scoresToSeed.length} Eating Disorder interpretation records seeded`);

    } catch (err) {
        console.error('  ❌ Eating Disorder Seeder Error:', err.message);
        throw err;
    }
};

module.exports = seedEatingDisorder;
