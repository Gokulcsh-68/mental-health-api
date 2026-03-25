const Question = require('../../src/models/Question');

const refinedQuestions = [
    {
        text: "How often do you find it hard to focus on a single task?",
        patientText: "How often do you find it hard to focus on a single task?",
        professionalText: "Assessment of concentration/attention span.",
        category: "adhd",
        type: "scale",
        options: [
            { text: "Never", score: 0 },
            { text: "Rarely", score: 1 },
            { text: "Sometimes", score: 2 },
            { text: "Often", score: 3 },
            { text: "Very Often", score: 4 }
        ],
        uiType: "radio"
    },
    {
        text: "Do you struggle with repetitive thoughts that you can't seem to stop?",
        patientText: "Do you struggle with repetitive thoughts that you can't seem to stop?",
        professionalText: "Assessment of intrusive thoughts/OCD symptoms.",
        category: "ocd",
        type: "boolean",
        options: [
            { text: "Yes", score: 1 },
            { text: "No", score: 0 }
        ],
        uiType: "radio"
    },
    {
        text: "Have you felt a sudden surge of fear or panic recently?",
        patientText: "Have you felt a sudden surge of fear or panic recently?",
        professionalText: "Assessment of panic symptoms.",
        category: "panic_disorder",
        type: "boolean",
        options: [
            { text: "No", score: 0 },
            { text: "Yes, once", score: 1 },
            { "text": "Yes, multiple times", score: 2 }
        ],
        uiType: "radio"
    }
];

async function seedRefinedQuestionsRoot() {
    try {
        for (const qData of refinedQuestions) {
            const exists = await Question.findOne({ text: qData.text });
            if (!exists) {
                await Question.create(qData);
                console.log(`Added: ${qData.text}`);
            } else {
                console.log(`Skipped (exists): ${qData.text}`);
            }
        }

        console.log('Seeding completed successfully.');
    } catch (err) {
        console.error('Seeding failed:', err);
        throw err;
    }
}

module.exports = seedRefinedQuestionsRoot;
