const mongoose = require('mongoose');
const config = require('../../src/config/config');
const Question = require('../../src/models/Question');

const topicQuestions = [
    // Topic: Social Connection
    {
        text: "How much have you enjoyed interacting with friends or family this week?",
        category: "social",
        type: "scale",
        uiType: "radio",
        options: [
            { text: "Not at all", score: 0 },
            { text: "A little", score: 1 },
            { text: "Somewhat", score: 2 },
            { text: "A lot", score: 3 }
        ],
        targetRole: "patient"
    },
    {
        text: "Did you feel supported by your loved ones today?",
        category: "social",
        type: "boolean",
        uiType: "radio",
        options: [
            { text: "Yes", score: 1 },
            { text: "No", score: 0 }
        ],
        targetRole: "patient"
    },
    // Topic: Self-Care
    {
        text: "How often have you managed to maintain your regular hygiene routine (showering, brushing teeth)?",
        category: "self-care",
        type: "scale",
        uiType: "radio",
        options: [
            { text: "Never", score: 0 },
            { text: "Sometimes", score: 1 },
            { text: "Most days", score: 2 },
            { text: "Every day", score: 3 }
        ],
        targetRole: "patient"
    },
    {
        text: "Have you been able to keep your living space tidy?",
        category: "self-care",
        type: "boolean",
        uiType: "radio",
        options: [
            { text: "Yes", score: 1 },
            { text: "No", score: 0 }
        ],
        targetRole: "patient"
    },
    // Topic: Daily Motivation
    {
        text: "How difficult was it for you to get out of bed this morning?",
        category: "motivation",
        type: "scale",
        uiType: "radio",
        options: [
            { text: "Very easy", score: 3 },
            { text: "Somewhat easy", score: 2 },
            { text: "Somewhat difficult", score: 1 },
            { text: "Very difficult", score: 0 }
        ],
        targetRole: "patient"
    },
    {
        text: "Do you feel like you have a goal or purpose for today?",
        category: "motivation",
        type: "boolean",
        uiType: "radio",
        options: [
            { text: "Yes", score: 1 },
            { text: "No", score: 0 }
        ],
        targetRole: "patient"
    },
    // Topic: Relaxation
    {
        text: "How often did you find time to relax or engage in a hobby you enjoy?",
        category: "relaxation",
        type: "scale",
        uiType: "radio",
        options: [
            { text: "Not at all", score: 0 },
            { text: "Rarely", score: 1 },
            { text: "Occasionally", score: 2 },
            { text: "Frequently", score: 3 }
        ],
        targetRole: "patient"
    }
];

const seedTopicQuestions = async () => {
    try {
        for (const q of topicQuestions) {
            const existing = await Question.findOne({ text: q.text });
            if (!existing) {
                await Question.create(q);
                console.log(`Created [${q.category}]: ${q.text.substring(0, 40)}...`);
            } else {
                console.log(`Skipped existing [${q.category}]: ${q.text.substring(0, 40)}...`);
            }
        }

        console.log('Topic-based patient questions seeding completed');
    } catch (err) {
        console.error('Seeding failed:', err);
        throw err;
    }
};

module.exports = seedTopicQuestions;
