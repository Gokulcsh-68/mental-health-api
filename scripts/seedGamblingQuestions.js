const mongoose = require('mongoose');
const dotenv = require('dotenv');
const path = require('path');
const Question = require('../src/models/Question');
const Master = require('../src/models/Master');
const User = require('../src/models/User');

dotenv.config({ path: path.join(__dirname, '../.env') });

const gamblingQuestions = [
    {
        text: "Needs to gamble with increasing amounts of money in order to achieve the desired excitement.",
        patientText: "Do you find yourself needing to bet more and more money to get the same feeling of excitement?",
        category: "gambling",
        type: "scale",
        uiType: "radio",
        options: [
            { text: "Not at all", score: 0 },
            { text: "Several days", score: 1 },
            { text: "More than half the days", score: 2 },
            { text: "Nearly every day", score: 3 }
        ],
        assessmentType: "both"
    },
    {
        text: "Is restless or irritable when attempting to cut down or stop gambling.",
        patientText: "Do you feel restless or annoyed when you try to gamble less or stop altogether?",
        category: "gambling",
        type: "scale",
        uiType: "radio",
        options: [
            { text: "Not at all", score: 0 },
            { text: "Several days", score: 1 },
            { text: "More than half the days", score: 2 },
            { text: "Nearly every day", score: 3 }
        ],
        assessmentType: "both"
    },
    {
        text: "Has made repeated unsuccessful efforts to control, cut back, or stop gambling.",
        patientText: "Have you tried to stop or cut back on gambling several times but found you couldn't do it?",
        category: "gambling",
        type: "scale",
        uiType: "radio",
        options: [
            { text: "Not at all", score: 0 },
            { text: "Several days", score: 1 },
            { text: "More than half the days", score: 2 },
            { text: "Nearly every day", score: 3 }
        ],
        assessmentType: "both"
    },
    {
        text: "Is often preoccupied with gambling (e.g., having persistent thoughts of reliving past gambling experiences).",
        patientText: "Are you often thinking about gambling, such as planning your next bet or reliving past wins and losses?",
        category: "gambling",
        type: "scale",
        uiType: "radio",
        options: [
            { text: "Not at all", score: 0 },
            { text: "Several days", score: 1 },
            { text: "More than half the days", score: 2 },
            { text: "Nearly every day", score: 3 }
        ],
        assessmentType: "both"
    },
    {
        text: "Often gambles when feeling distressed (e.g., helpless, guilty, anxious, depressed).",
        patientText: "Do you gamble more when you're feeling down, stressed, or guilty?",
        category: "gambling",
        type: "scale",
        uiType: "radio",
        options: [
            { text: "Not at all", score: 0 },
            { text: "Several days", score: 1 },
            { text: "More than half the days", score: 2 },
            { text: "Nearly every day", score: 3 }
        ],
        assessmentType: "both"
    }
];

const seedQuestions = async () => {
    try {
        await mongoose.connect(process.env.MONGO_URI);
        console.log('Connected to MongoDB');

        // Optional: Get an admin user to set as creator
        const admin = await User.findOne({ role: 'admin' });
        const createdBy = admin ? admin._id : null;

        for (const qData of gamblingQuestions) {
            // Check if question already exists text-wise
            const exists = await Question.findOne({ text: qData.text });
            if (!exists) {
                await Question.create({
                    ...qData,
                    createdBy
                });
                console.log(`Created: ${qData.text.substring(0, 30)}...`);
            } else {
                console.log(`Skipped (exists): ${qData.text.substring(0, 30)}...`);
            }
        }

        console.log('Gambling questions seeded successfully');
        process.exit();
    } catch (err) {
        console.error('Error seeding questions:', err);
        process.exit(1);
    }
};

seedQuestions();
