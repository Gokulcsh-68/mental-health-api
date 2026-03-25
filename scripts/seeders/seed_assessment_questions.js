const Question = require('../../src/models/Question');

const newQuestions = [
    {
        text: "How have you been feeling overall today?",
        patientText: "How have you been feeling overall today?",
        professionalText: "Patient's general mood assessment.",
        category: "simple_self",
        type: "scale",
        options: [
            { text: "Very Poor", score: 0 },
            { text: "Poor", score: 1 },
            { text: "Neutral", score: 2 },
            { text: "Good", score: 3 },
            { text: "Excellent", score: 4 }
        ],
        uiType: "radio"
    },
    {
        text: "Have you felt any unusual stress in the last 24 hours?",
        patientText: "Have you felt any unusual stress in the last 24 hours?",
        professionalText: "Acute stress level assessment.",
        category: "simple_self",
        type: "boolean",
        options: [
            { text: "Yes", score: 1 },
            { text: "No", score: 0 }
        ],
        uiType: "radio"
    },
    {
        text: "Did you manage to get enough rest last night?",
        patientText: "Did you manage to get enough rest last night?",
        professionalText: "Sleep sufficiency report.",
        category: "simple_self",
        type: "boolean",
        options: [
            { text: "Yes", score: 0 },
            { text: "No", score: 1 }
        ],
        uiType: "radio"
    },
    {
        text: "Have you taken your prescribed medications today?",
        patientText: "Have you taken your prescribed medications today?",
        professionalText: "Medication adherence check.",
        category: "baseline",
        type: "boolean",
        options: [
            { text: "Yes", score: 0 },
            { text: "No", score: 1 },
            { text: "N/A", score: 0 }
        ],
        uiType: "radio"
    },
    {
        text: "How would you rate your energy level right now?",
        patientText: "How would you rate your energy level right now?",
        professionalText: "Current energy/vitality level.",
        category: "simple_self",
        type: "scale",
        options: [
            { text: "Very Low", score: 0 },
            { text: "Low", score: 1 },
            { text: "Average", score: 2 },
            { text: "High", score: 3 },
            { text: "Very High", score: 4 }
        ],
        uiType: "radio"
    }
];

async function seedAssessmentQuestions() {
    try {
        for (const qData of newQuestions) {
            const exists = await Question.findOne({ text: qData.text });
            if (!exists) {
                await Question.create(qData);
                console.log(`Added: ${qData.text}`);
            } else {
                console.log(`Skipped (exists): ${qData.text}`);
            }
        }

        console.log('Assessment questions seeding completed successfully.');
    } catch (err) {
        console.error('Seeding failed:', err);
        throw err;
    }
}

module.exports = seedAssessmentQuestions;
