const Question = require('../../src/models/Question');
const Master = require('../../src/models/Master');
const StandardizedScore = require('../../src/models/StandardizedScore');

const gamblingQuestions = [
    { text: "In the last 12 months, have there been any periods lasting two weeks or longer when you spent a lot of time thinking about your gambling experiences or planning future gambling ventures or bets?", type: "boolean" }, // Q1
    { text: "In the last 12 months, have there been periods lasting two weeks or longer when you spent a lot of time thinking about ways of getting money to gamble with?", type: "boolean" }, // Q2
    { text: "In the last 12 months, have there been periods when you needed to gamble with increasing amounts of money or with larger bets than before in order to get the same feeling of excitement?", type: "boolean" }, // Q3
    { text: "In the last 12 months, have you tried to stop, cut down, or control your gambling?", type: "boolean" }, // Q4
    { text: "In the last 12 months, on one or more of the times when you tried to stop, cut down, or control your gambling, were you restless or irritable?", type: "boolean" }, // Q5
    { text: "In the last 12 months, have you tried but not succeeded in stopping, cutting down, or controlling your gambling?", type: "boolean" }, // Q6
    { text: "If Yes to failing to stop: Has this happened three or more times?", type: "boolean" }, // Q7
    { text: "In the last 12 months, have you gambled as a way to escape from personal problems?", type: "boolean" }, // Q8
    { text: "In the last 12 months, have you gambled to relieve uncomfortable feelings such as guilt, anxiety, helplessness, or depression?", type: "boolean" }, // Q9
    { text: "In the last 12 months, has there ever been a period when, if you lost money gambling on one day, you would often return another day to get even?", type: "boolean" }, // Q10
    { text: "In the last 12 months, have you more than once lied to family members, friends, or others about how much you gamble or how much money you lost on gambling?", type: "boolean" }, // Q11
    { text: "If Yes to lying: Has this happened three or more times?", type: "boolean" }, // Q12
    { text: "In the last 12 months, has your gambling caused serious or repeated problems in your relationships with any of your family members or friends?", type: "boolean" }, // Q13
    { text: "In the last 12 months, has your gambling caused you any problems in school, such as missing classes or days of school or getting worse grades?", type: "boolean" }, // Q14
    { text: "In the last 12 months, has your gambling caused you to lose a job, have trouble with your job, or miss out on an important job or career opportunity?", type: "boolean" }, // Q15
    { text: "In the last 12 months, have you needed to ask family members or anyone else to loan you money or otherwise bail you out of a desperate money situation that was largely caused by your gambling?", type: "boolean" }, // Q16

    // Optional / Informational
    { text: "What is most distressing to you about your gambling?", type: "choice", uiType: "textarea", isOptional: true },
    { text: "How often have you gambled in the past 12 months?", type: "choice", options: ["More than once a day", "More than once a week", "More than once a month", "Once a month or less"] },
    { text: "How strongly do you agree with this statement: 'I believe I am addicted to gambling.'", type: "choice", options: ["Strongly Disagree", "Disagree", "Agree", "Strongly Agree"] },
    { text: "Besides gambling, are you concerned that you may be addicted to any other behaviors?", type: "boolean" },
    {
        text: "Which of the following behaviors are you concerned about? (Select all that apply)",
        type: "choice",
        uiType: "checkbox",
        options: [
            "Self-injury (cutting, burning, hitting, etc.)",
            "Pornography",
            "Sex",
            "Internet (social media, videos, doomscrolling, etc.)",
            "Food (junk food, binge eating, etc.)",
            "Masturbation",
            "Shopping",
            "Video games",
            "Substances (nicotine/vaping, alcohol, drugs, etc.)",
            "Other…"
        ],
        isOptional: true
    },
    { text: "If Other behavior: What other behavior are you concerned about?", type: "choice", uiType: "textarea", isOptional: true }
];

const booleanOptions = [
    { text: "Yes", score: 1 },
    { text: "No", score: 0 }
];

const frequencyOptions = [
    { text: "More than once a day", score: 0 },
    { text: "More than once a week", score: 0 },
    { text: "More than once a month", score: 0 },
    { text: "Once a month or less", score: 0 }
];

const agreementOptions = [
    { text: "Strongly Disagree", score: 0 },
    { text: "Disagree", score: 0 },
    { text: "Agree", score: 0 },
    { text: "Strongly Agree", score: 0 }
];

const checklistOptions = [
    { text: "Self-injury (cutting, burning, hitting, etc.)", score: 0 },
    { text: "Pornography", score: 0 },
    { text: "Sex", score: 0 },
    { text: "Internet (social media, videos, doomscrolling, etc.)", score: 0 },
    { text: "Food (junk food, binge eating, etc.)", score: 0 },
    { text: "Masturbation", score: 0 },
    { text: "Shopping", score: 0 },
    { text: "Video games", score: 0 },
    { text: "Substances (nicotine/vaping, alcohol, drugs, etc.)", score: 0 },
    { text: "Other…", score: 0 }
];

const seedGambling = async () => {
    try {
        const master = await Master.findOne({ slug: 'gambling' });
        const masterId = master ? master._id : null;
        const masterSlug = master ? master.slug : 'gambling';

        await Question.deleteMany({ category: masterSlug });
        await StandardizedScore.deleteMany({ category: masterSlug });

        const questionsToSeed = gamblingQuestions.map((q, idx) => {
            const seedObj = {
                text: q.text,
                category: masterSlug,
                type: q.type,
                minAge: 18,
                maxAge: 120,
                gender: "all",
                uiType: q.uiType || "radio",
                master: masterId,
                isOptional: q.isOptional || false
            };

            if (q.type === 'boolean') {
                seedObj.options = booleanOptions;
            } else if (q.text.includes("How often have you gambled")) {
                seedObj.options = frequencyOptions;
            } else if (q.text.includes("How strongly do you agree")) {
                seedObj.options = agreementOptions;
            } else if (q.uiType === 'checkbox') {
                seedObj.options = checklistOptions;
            } else {
                seedObj.options = [];
            }
            return seedObj;
        });

        for (const q of questionsToSeed) {
            await Question.create(q);
        }
        console.log(`  ✅ ${questionsToSeed.length} Gambling questions seeded`);

        const scoresToSeed = [];
        // Raw score is based on the 16 core boolean questions (max 16)
        // Criteria met interpretation: 4-5 Mild, 6-7 Moderate, 8-16 Severe
        for (let raw = 0; raw <= 16; raw++) {
            let interpretation = "Below clinical threshold";
            if (raw >= 4 && raw <= 5) interpretation = "Mild Gambling Disorder";
            else if (raw >= 6 && raw <= 7) interpretation = "Moderate Gambling Disorder";
            else if (raw >= 8) interpretation = "Severe Gambling Disorder";

            scoresToSeed.push({
                category: masterSlug,
                master: masterId,
                rawScore: raw,
                tScore: (raw / 16) * 100,
                standardError: 0,
                interpretation
            });
        }

        await StandardizedScore.insertMany(scoresToSeed);
        console.log(`  📊 ${scoresToSeed.length} Gambling interpretation records seeded`);

    } catch (err) {
        console.error('  ❌ Gambling Seeder Error:', err.message);
        throw err;
    }
};

module.exports = seedGambling;
