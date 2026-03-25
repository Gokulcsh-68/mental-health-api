const Question = require('../../src/models/Question');
const Master = require('../../src/models/Master');
const StandardizedScore = require('../../src/models/StandardizedScore');

const substanceUseQuestions = [
    // Initial Concern
    {
        text: "What substance or behavior are you most concerned about?",
        category: "substance_use",
        type: "choice",
        minAge: 11,
        maxAge: 120,
        gender: "all",
        options: [
            { text: "Alcohol", score: 0 },
            { text: "Another drug or multiple drugs", score: 0 },
            { text: "Another behavior (gambling, self-harm, etc.)", score: 0 }
        ],
        uiType: "radio"
    },
    // Drug Checklist (Conditional)
    {
        text: "Which of the following substances are you concerned about? (Select all that apply)",
        category: "substance_use",
        type: "choice",
        minAge: 11,
        maxAge: 120,
        gender: "all",
        options: [
            { text: "Alcohol", score: 0 },
            { text: "Marijuana / cannabis", score: 0 },
            { text: "Nicotine", score: 0 },
            { text: "Benzodiazepines (e.g. Xanax, Valium)", score: 0 },
            { text: "Cocaine / crack", score: 0 },
            { text: "Fentanyl", score: 0 },
            { text: "Heroin", score: 0 },
            { text: "Prescription Opioids (e.g. OxyContin, Percocet, Vicodin)", score: 0 },
            { text: "Stimulants (e.g. speed, meth, Adderall, Ritalin)", score: 0 },
            { text: "Other...", score: 0 }
        ],
        uiType: "checkbox"
    },
    {
        text: "What other substance are you concerned about?",
        category: "substance_use",
        type: "choice", // Using choice to store as text in responses if needed, or could be others
        minAge: 11,
        maxAge: 120,
        gender: "all",
        uiType: "textarea"
    },
    // Behavior Checklist (Conditional)
    {
        text: "Which of the following behaviors are you concerned about? (Select all that apply)",
        category: "substance_use",
        type: "choice",
        minAge: 11,
        maxAge: 120,
        gender: "all",
        options: [
            { text: "Self-injury (cutting, burning, hitting, etc.)", score: 0 },
            { text: "Pornography", score: 0 },
            { text: "Sex", score: 0 },
            { text: "Internet (social media, videos, doomscrolling, etc.)", score: 0 },
            { text: "Food (junk food, binge eating, etc.)", score: 0 },
            { text: "Masturbation", score: 0 },
            { text: "Shopping", score: 0 },
            { text: "Video games", score: 0 },
            { text: "Gambling", score: 0 },
            { text: "Substances (nicotine/vaping, alcohol, drugs, etc.)", score: 0 },
            { text: "Other...", score: 0 }
        ],
        uiType: "checkbox"
    },
    {
        text: "What other behavior are you concerned about?",
        category: "substance_use",
        type: "choice",
        minAge: 11,
        maxAge: 120,
        gender: "all",
        uiType: "textarea"
    },
    // Core Scoring Questions (In the last 12 months)
    { text: "In the last 12 months, did you have strong desires or cravings for alcohol?", category: "substance_use", type: "boolean", minAge: 11, maxAge: 120, gender: "all", uiType: "radio" },
    { text: "In the last 12 months, did you want to cut back or stop drinking, but couldn’t?", category: "substance_use", type: "boolean", minAge: 11, maxAge: 120, gender: "all", uiType: "radio" },
    { text: "In the last 12 months, did you spend a lot of time getting alcohol, drinking, or feeling hungover?", category: "substance_use", type: "boolean", minAge: 11, maxAge: 120, gender: "all", uiType: "radio" },
    { text: "In the last 12 months, did you have times when you drank more or for longer than you wanted to?", category: "substance_use", type: "boolean", minAge: 11, maxAge: 120, gender: "all", uiType: "radio" },
    { text: "In the last 12 months, did drinking the same amount have less effect than it used to? Or did you have to drink more to feel the effect you wanted?", category: "substance_use", type: "boolean", minAge: 11, maxAge: 120, gender: "all", uiType: "radio" },
    { text: "In the last 12 months, did you have an upset stomach or get sweaty, shaky, or nervous when you weren’t drinking or when you tried to cut down? Or did you drink alcohol or take something to help you feel better?", category: "substance_use", type: "boolean", minAge: 11, maxAge: 120, gender: "all", uiType: "radio" },
    { text: "In the last 12 months, did you continue to drink even though you thought it might be causing physical or mental problems — or making them worse?", category: "substance_use", type: "boolean", minAge: 11, maxAge: 120, gender: "all", uiType: "radio" },
    { text: "In the last 12 months, did you drink alcohol even though you thought it might be causing problems with your family or other people?", category: "substance_use", type: "boolean", minAge: 11, maxAge: 120, gender: "all", uiType: "radio" },
    { text: "In the last 12 months, did drinking make it harder for you to keep up with your responsibilities at work, school, or home?", category: "substance_use", type: "boolean", minAge: 11, maxAge: 120, gender: "all", uiType: "radio" },
    { text: "In the last 12 months, did you spend less time working, enjoying hobbies, or being with others because of your drinking?", category: "substance_use", type: "boolean", minAge: 11, maxAge: 120, gender: "all", uiType: "radio" },
    { text: "In the last 12 months, did you do dangerous things more than once after drinking — like drive a car or operate machinery?", category: "substance_use", type: "boolean", minAge: 11, maxAge: 120, gender: "all", uiType: "radio" },

    // Recent Symptoms (Last 3 months)
    { text: "In the last three months, did you drink any alcohol?", category: "substance_use", type: "boolean", minAge: 11, maxAge: 120, gender: "all", uiType: "radio" },
    { text: "In the last three months, were there times when you drank alcohol more or for longer than you wanted?", category: "substance_use", type: "boolean", minAge: 11, maxAge: 120, gender: "all", uiType: "radio" },
    { text: "In the last three months, did you get into arguments with your family or friends because of drinking?", category: "substance_use", type: "boolean", minAge: 11, maxAge: 120, gender: "all", uiType: "radio" },
    { text: "In the last three months, did you miss school or work to go drinking or because you were hung over?", category: "substance_use", type: "boolean", minAge: 11, maxAge: 120, gender: "all", uiType: "radio" }
];

const booleanOptions = [
    { text: "Yes", score: 1 },
    { text: "No", score: 0 }
];

const scoringTable = [
    { min: 0, max: 1, interpretation: "Concern unlikely" },
    { min: 2, max: 3, interpretation: "Mild concern" },
    { min: 4, max: 5, interpretation: "Moderate concern" },
    { min: 6, max: 15, interpretation: "Severe concern" }
];

const seedSubstanceUse = async () => {
    try {
        const master = await Master.findOne({ slug: 'substance_use' });
        const masterId = master ? master._id : null;
        const masterSlug = master ? master.slug : 'substance_use';

        await Question.deleteMany({ category: masterSlug });
        await StandardizedScore.deleteMany({ category: masterSlug });

        const questionsToSeed = substanceUseQuestions.map(q => {
            const seedObj = {
                ...q,
                category: masterSlug,
                master: masterId
            };
            if (q.type === 'boolean') {
                seedObj.options = booleanOptions;
            }
            return seedObj;
        });

        // Use sequential create to avoid duplicate questionId errors (save hook)
        for (const q of questionsToSeed) {
            await Question.create(q);
        }
        console.log(`  ✅ ${questionsToSeed.length} Substance Use questions seeded`);

        const scoresToSeed = [];
        for (let raw = 0; raw <= 15; raw++) {
            const tableEntry = scoringTable.find(t => raw >= t.min && raw <= t.max);
            scoresToSeed.push({
                category: masterSlug,
                master: masterId,
                rawScore: raw,
                tScore: 50, // Standardized score placeholder
                standardError: 0,
                interpretation: tableEntry ? tableEntry.interpretation : "Unknown"
            });
        }

        await StandardizedScore.insertMany(scoresToSeed);
        console.log(`  📊 ${scoresToSeed.length} Substance Use interpretation records seeded`);

    } catch (err) {
        console.error('  ❌ Substance Use Seeder Error:', err.message);
        throw err;
    }
};

module.exports = seedSubstanceUse;
