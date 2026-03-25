const mongoose = require('mongoose');
const config = require('../../src/config/config');
const Question = require('../../src/models/Question');

const patientQuestions = [
    {
        text: "Over the last 2 weeks, how often have you been bothered by feeling down, depressed, or hopeless?",
        category: "mood",
        type: "scale",
        uiType: "radio",
        options: [
            { text: "Not at all", score: 0 },
            { text: "Several days", score: 1 },
            { text: "More than half the days", score: 2 },
            { text: "Nearly every day", score: 3 }
        ],
        targetRole: "patient"
    },
    {
        text: "Over the last 2 weeks, how often have you had little interest or pleasure in doing things?",
        category: "mood",
        type: "scale",
        uiType: "radio",
        options: [
            { text: "Not at all", score: 0 },
            { text: "Several days", score: 1 },
            { text: "More than half the days", score: 2 },
            { text: "Nearly every day", score: 3 }
        ],
        targetRole: "patient"
    },
    {
        text: "Feeling nervous, anxious, or on edge?",
        category: "anxiety",
        type: "scale",
        uiType: "radio",
        options: [
            { text: "Not at all", score: 0 },
            { text: "Several days", score: 1 },
            { text: "More than half the days", score: 2 },
            { text: "Nearly every day", score: 3 }
        ],
        targetRole: "patient"
    },
    {
        text: "Not being able to stop or control worrying?",
        category: "anxiety",
        type: "scale",
        uiType: "radio",
        options: [
            { text: "Not at all", score: 0 },
            { text: "Several days", score: 1 },
            { text: "More than half the days", score: 2 },
            { text: "Nearly every day", score: 3 }
        ],
        targetRole: "patient"
    },
    {
        text: "Trouble falling or staying asleep, or sleeping too much?",
        category: "sleep",
        type: "scale",
        uiType: "radio",
        options: [
            { text: "Not at all", score: 0 },
            { text: "Several days", score: 1 },
            { text: "More than half the days", score: 2 },
            { text: "Nearly every day", score: 3 }
        ],
        targetRole: "patient"
    },
    {
        text: "Feeling tired or having little energy?",
        category: "energy",
        type: "scale",
        uiType: "radio",
        options: [
            { text: "Not at all", score: 0 },
            { text: "Several days", score: 1 },
            { text: "More than half the days", score: 2 },
            { text: "Nearly every day", score: 3 }
        ],
        targetRole: "patient"
    },
    {
        text: "Poor appetite or overeating?",
        category: "appetite",
        type: "scale",
        uiType: "radio",
        options: [
            { text: "Not at all", score: 0 },
            { text: "Several days", score: 1 },
            { text: "More than half the days", score: 2 },
            { text: "Nearly every day", score: 3 }
        ],
        targetRole: "patient"
    },
    {
        text: "Do you feel physically active enough during the week?",
        category: "lifestyle",
        type: "boolean",
        uiType: "radio",
        options: [
            { text: "Yes", score: 1 },
            { text: "No", score: 0 }
        ],
        targetRole: "patient"
    }
];

const seedPatientQuestions = async () => {
    try {
        for (const q of patientQuestions) {
            const existing = await Question.findOne({ text: q.text });
            if (!existing) {
                await Question.create(q);
                console.log(`Created: ${q.text.substring(0, 40)}...`);
            } else {
                console.log(`Skipped existing: ${q.text.substring(0, 40)}...`);
            }
        }

        console.log('Patient questions seeding completed');
    } catch (err) {
        console.error('Seeding failed:', err);
        throw err;
    }
};

module.exports = seedPatientQuestions;
