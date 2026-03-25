const mongoose = require('mongoose');
const config = require('../../src/config/config');
const Question = require('../../src/models/Question');

const specializedQuestions = [
    // --- CHILDREN (5-12) ---
    {
        text: "Do you enjoy playing with your friends at school?",
        category: "social", minAge: 5, maxAge: 12, gender: "all", targetRole: "patient",
        type: "boolean", uiType: "radio", options: [{ text: "Yes", score: 1 }, { text: "No", score: 0 }]
    },
    {
        text: "Do you sometimes feel scared or worried about being away from your parents?",
        category: "anxiety", minAge: 5, maxAge: 12, gender: "all", targetRole: "patient",
        type: "scale", uiType: "radio", options: [{ text: "Never", score: 3 }, { text: "Sometimes", score: 1 }, { text: "Often", score: 0 }]
    },

    // --- TEENS (13-17) ---
    {
        text: "How often do you feel pressured by your friends to do things you don't want to do?",
        category: "peer-pressure", minAge: 13, maxAge: 17, gender: "all", targetRole: "patient",
        type: "scale", uiType: "radio", options: [{ text: "Never", score: 3 }, { text: "Sometimes", score: 1 }, { text: "Often", score: 0 }]
    },
    {
        text: "Do you feel like your parents or guardians understand what you are going through?",
        category: "family", minAge: 13, maxAge: 17, gender: "all", targetRole: "patient",
        type: "boolean", uiType: "radio", options: [{ text: "Yes", score: 1 }, { text: "No", score: 0 }]
    },

    // --- YOUNG ADULTS (18-30) ---
    {
        text: "How stressed do you feel about your career or finding your place in the world?",
        category: "career", minAge: 18, maxAge: 30, gender: "all", targetRole: "patient",
        type: "scale", uiType: "radio", options: [
            { text: "Not at all", score: 3 },
            { text: "Somewhat", score: 2 },
            { text: "Moderately", score: 1 },
            { text: "Extremely", score: 0 }
        ]
    },

    // --- ADULTS (31-55) ---
    {
        text: "How often do you feel burdened by the financial responsibilities of your household?",
        category: "financial", minAge: 31, maxAge: 55, gender: "all", targetRole: "patient",
        type: "scale", uiType: "radio", options: [
            { text: "Never", score: 3 }, { text: "Occasionally", score: 1 }, { text: "Frequently", score: 0 }
        ]
    },

    // --- SENIORS (56+) ---
    {
        text: "Do you feel like you have a clear sense of purpose in this stage of your life?",
        category: "purpose", minAge: 56, maxAge: 120, gender: "all", targetRole: "patient",
        type: "boolean", uiType: "radio", options: [{ text: "Yes", score: 1 }, { text: "No", score: 0 }]
    },

    // --- FEMALE SPECIFIC ---
    {
        text: "How much does your body image or appearance affect your mood on a daily basis?",
        category: "self-image", minAge: 13, maxAge: 60, gender: "female", targetRole: "patient",
        type: "scale", uiType: "radio", options: [
            { text: "Not at all", score: 3 }, { text: "A little", score: 2 }, { text: "Moderately", score: 1 }, { text: "Severely", score: 0 }
        ]
    },
    {
        text: "Do you feel like your emotional health is impacted by monthly hormonal changes?",
        category: "physical", minAge: 15, maxAge: 50, gender: "female", targetRole: "patient",
        type: "scale", uiType: "radio", options: [
            { text: "No impact", score: 3 }, { text: "Minor impact", score: 2 }, { text: "Significant impact", score: 0 }
        ]
    },

    // --- MALE SPECIFIC ---
    {
        text: "Do you find it difficult to talk to others when you're feeling emotionally low?",
        category: "emotional-expression", minAge: 18, maxAge: 120, gender: "male", targetRole: "patient",
        type: "boolean", uiType: "radio", options: [{ text: "Yes", score: 0 }, { text: "No", score: 1 }]
    },
    {
        text: "Do you feel a strong pressure to avoid showing vulnerability or weakness?",
        category: "social-pressure", minAge: 15, maxAge: 120, gender: "male", targetRole: "patient",
        type: "boolean", uiType: "radio", options: [{ text: "Yes", score: 0 }, { text: "No", score: 1 }]
    }
];

const seedRefinedQuestions = async () => {
    try {
        console.log('Cleaning up old generic patient questions...');
        // Remove old questions that were too generic or duplicate
        await Question.deleteMany({ targetRole: 'patient' });

        for (const q of specializedQuestions) {
            await Question.create(q);
            console.log(`Created [${q.minAge}-${q.maxAge}, ${q.gender}]: ${q.text.substring(0, 40)}...`);
        }

        console.log('Refined demographic questions seeding completed');
    } catch (err) {
        console.error('Seeding failed:', err);
        throw err;
    }
};

module.exports = seedRefinedQuestions;
