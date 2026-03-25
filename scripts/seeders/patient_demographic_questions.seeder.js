const mongoose = require('mongoose');
const config = require('../../src/config/config');
const Question = require('../../src/models/Question');

const demographicQuestions = [
    // --- Children/Teens (5-17) ---
    {
        text: "Do you feel like you have a good friend to talk to at school?",
        category: "social",
        minAge: 5, maxAge: 17, gender: "all", targetRole: "patient",
        type: "boolean", uiType: "radio",
        options: [{ text: "Yes", score: 1 }, { text: "No", score: 0 }]
    },
    {
        text: "How often do you feel nervous about going to school?",
        category: "school",
        minAge: 5, maxAge: 17, gender: "all", targetRole: "patient",
        type: "scale", uiType: "radio",
        options: [
            { text: "Never", score: 3 },
            { text: "Rarely", score: 2 },
            { text: "Often", score: 1 },
            { text: "Always", score: 0 }
        ]
    },
    {
        text: "Do you feel comfortable talking to your parents about your feelings?",
        category: "family",
        minAge: 5, maxAge: 17, gender: "all", targetRole: "patient",
        type: "boolean", uiType: "radio",
        options: [{ text: "Yes", score: 1 }, { text: "No", score: 0 }]
    },

    // --- Young Adults (18-25) ---
    {
        text: "How confident do you feel about your career or education paths right now?",
        category: "future",
        minAge: 18, maxAge: 25, gender: "all", targetRole: "patient",
        type: "scale", uiType: "radio",
        options: [
            { text: "Very Confident", score: 3 },
            { text: "Somewhat Confident", score: 2 },
            { text: "A little worried", score: 1 },
            { text: "Very Unsure", score: 0 }
        ]
    },
    {
        text: "How often do you worry about your financial future?",
        category: "future",
        minAge: 18, maxAge: 25, gender: "all", targetRole: "patient",
        type: "scale", uiType: "radio",
        options: [
            { text: "Not at all", score: 3 },
            { text: "Sometimes", score: 2 },
            { text: "Often", score: 1 },
            { text: "Constantly", score: 0 }
        ]
    },

    // --- Middle Adults (26-55) ---
    {
        text: "How often does your work stress prevent you from enjoying time with family?",
        category: "work-life",
        minAge: 26, maxAge: 55, gender: "all", targetRole: "patient",
        type: "scale", uiType: "radio",
        options: [
            { text: "Never", score: 3 },
            { text: "Rarely", score: 2 },
            { text: "Frequently", score: 1 },
            { text: "Almost always", score: 0 }
        ]
    },
    {
        text: "Do you feel like you have enough time for yourself outside of work and family duties?",
        category: "self-care",
        minAge: 26, maxAge: 55, gender: "all", targetRole: "patient",
        type: "boolean", uiType: "radio",
        options: [{ text: "Yes", score: 1 }, { text: "No", score: 0 }]
    },

    // --- Seniors (56+) ---
    {
        text: "How much does your physical health limit your ability to do things you love?",
        category: "health",
        minAge: 56, maxAge: 120, gender: "all", targetRole: "patient",
        type: "scale", uiType: "radio",
        options: [
            { text: "Not at all", score: 3 },
            { text: "A little", score: 2 },
            { text: "Somewhat", score: 1 },
            { text: "A lot", score: 0 }
        ]
    },
    {
        text: "How often do you reflect on your life experiences with a sense of satisfaction?",
        category: "purpose",
        minAge: 56, maxAge: 120, gender: "all", targetRole: "patient",
        type: "scale", uiType: "radio",
        options: [
            { text: "Always", score: 3 },
            { text: "Frequently", score: 2 },
            { text: "Occasionally", score: 1 },
            { text: "Rarely", score: 0 }
        ]
    },

    // --- Gender-Specific ---
    {
        text: "Have you noticed any significant changes in your mood related to your hormonal cycle?",
        category: "physical",
        minAge: 12, maxAge: 55, gender: "female", targetRole: "patient",
        type: "boolean", uiType: "radio",
        options: [{ text: "Yes", score: 1 }, { text: "No", score: 0 }]
    },
    {
        text: "When you feel stressed, do you find it easier to get angry than to talk about your feelings?",
        category: "emotional",
        minAge: 18, maxAge: 120, gender: "male", targetRole: "patient",
        type: "boolean", uiType: "radio",
        options: [{ text: "Yes", score: 0 }, { text: "No", score: 1 }]
    },
    {
        text: "Do you feel pressure to 'be strong' and hide your emotions from others?",
        category: "social",
        minAge: 10, maxAge: 120, gender: "male", targetRole: "patient",
        type: "boolean", uiType: "radio",
        options: [{ text: "Yes", score: 0 }, { text: "No", score: 1 }]
    },
    {
        text: "Does seeing others' lives on social media make you feel less happy with your own life?",
        category: "digital",
        minAge: 10, maxAge: 30, gender: "female", targetRole: "patient",
        type: "scale", uiType: "radio",
        options: [
            { text: "Never", score: 3 },
            { text: "Rarely", score: 2 },
            { text: "Often", score: 1 },
            { text: "Very often", score: 0 }
        ]
    }
];

const seedDemographicQuestions = async () => {
    try {
        for (const q of demographicQuestions) {
            const existing = await Question.findOne({ text: q.text });
            if (!existing) {
                await Question.create(q);
                console.log(`Created [${q.minAge}-${q.maxAge}, ${q.gender}]: ${q.text.substring(0, 40)}...`);
            } else {
                console.log(`Skipped existing: ${q.text.substring(0, 40)}...`);
            }
        }

        console.log('Demographic questions seeding completed');
    } catch (err) {
        console.error('Seeding failed:', err);
        throw err;
    }
};

module.exports = seedDemographicQuestions;
