const mongoose = require('mongoose');
const config = require('../../src/config/config');
const Question = require('../../src/models/Question');
const Counter = require('../../src/models/Counter');

const questions = [
    {
        text: "How have you been feeling lately? (Patient Version)",
        category: "general",
        type: "scale",
        uiType: "radio",
        options: [
            { text: "Very Poor", score: 0 },
            { text: "Poor", score: 1 },
            { text: "Average", score: 2 },
            { text: "Good", score: 3 },
            { text: "Excellent", score: 4 }
        ],
        targetRole: "patient"
    },
    {
        text: "Observe patient behavioral patterns (Specialist Version)",
        category: "behavioral",
        type: "scale",
        uiType: "radio",
        options: [
            { text: "Severely impaired", score: 0 },
            { text: "Moderately impaired", score: 1 },
            { text: "Mildly impaired", score: 2 },
            { text: "Normal", score: 3 }
        ],
        targetRole: "professional"
    },
    {
        text: "Common health screening question",
        category: "general",
        type: "boolean",
        uiType: "radio",
        options: [
            { text: "Yes", score: 1 },
            { text: "No", score: 0 }
        ],
        targetRole: "both"
    }
];

const seedQuestions = async () => {
    try {
        // Reset counter for questions if needed or just append
        // For testing, let's just insert
        for (const q of questions) {
            const existing = await Question.findOne({ text: q.text });
            if (!existing) {
                await Question.create(q);
                console.log(`Created: ${q.text}`);
            } else {
                console.log(`Skipped existing: ${q.text}`);
            }
        }

        console.log('Role based questions seeding completed');
    } catch (err) {
        console.error('Seeding failed:', err);
        throw err;
    }
};

module.exports = seedQuestions;
