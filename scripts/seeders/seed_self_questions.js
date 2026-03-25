const Question = require('../../src/models/Question');

const selfQuestions = [
    {
        text: "Overall, how would you rate your mental well-being today?",
        category: "general",
        assessmentType: "self",
        type: "scale",
        options: [{ text: "Poor", score: 0 }, { text: "Fair", score: 1 }, { text: "Good", score: 2 }, { text: "Very Good", score: 3 }, { text: "Excellent", score: 4 }],
        uiType: "radio"
    },
    {
        text: "How often have you felt down, depressed, or hopeless recently?",
        category: "depression",
        assessmentType: "self",
        type: "scale",
        options: [{ text: "Not at all", score: 0 }, { text: "Several days", score: 1 }, { text: "More than half the days", score: 2 }, { text: "Nearly every day", score: 3 }],
        uiType: "radio"
    },
    {
        text: "How often have you been bothered by feeling nervous, anxious, or on edge?",
        category: "anxiety",
        assessmentType: "self",
        type: "scale",
        options: [{ text: "Not at all", score: 0 }, { text: "Several days", score: 1 }, { text: "More than half the days", score: 2 }, { text: "Nearly every day", score: 3 }],
        uiType: "radio"
    },
    {
        text: "Do you find it difficult to stay focused on tasks or conversations?",
        category: "adhd",
        assessmentType: "self",
        type: "boolean",
        options: [{ text: "Yes", score: 1 }, { text: "No", score: 0 }],
        uiType: "radio"
    },
    {
        text: "Have you experienced sudden, unexplained surges of intense fear?",
        category: "panic_disorder",
        assessmentType: "self",
        type: "boolean",
        options: [{ text: "Yes", score: 1 }, { text: "No", score: 0 }],
        uiType: "radio"
    },
    {
        text: "How satisfied are you with your sleep quality lately?",
        category: "sleep",
        assessmentType: "self",
        type: "scale",
        options: [{ text: "Very Unsatisfied", score: 0 }, { text: "Unsatisfied", score: 1 }, { text: "Neutral", score: 2 }, { text: "Satisfied", score: 3 }, { text: "Very Satisfied", score: 4 }],
        uiType: "radio"
    },
    {
        text: "Do you often feel overwhelmed by daily stress?",
        category: "stress",
        assessmentType: "self",
        type: "scale",
        options: [{ text: "Never", score: 0 }, { text: "Sometimes", score: 1 }, { text: "Always", score: 2 }],
        uiType: "radio"
    },
    {
        text: "Have you felt disconnected from reality or your surroundings lately?",
        category: "psychosis",
        assessmentType: "self",
        type: "boolean",
        options: [{ text: "Yes", score: 1 }, { text: "No", score: 0 }],
        uiType: "radio"
    },
    {
        text: "Do you struggle with repetitive thoughts that you can't control?",
        category: "ocd",
        assessmentType: "self",
        type: "boolean",
        options: [{ text: "Yes", score: 1 }, { text: "No", score: 0 }],
        uiType: "radio"
    },
    {
        text: "How would you rate your current energy level?",
        category: "mania",
        assessmentType: "self",
        type: "scale",
        options: [{ text: "Very Low", score: 0 }, { text: "Normal", score: 2 }, { text: "Unusually High", score: 4 }],
        uiType: "radio"
    },
    {
        text: "Do you have trouble controlling your anger or frustration?",
        category: "anger",
        assessmentType: "self",
        type: "scale",
        options: [{ text: "Never", score: 0 }, { text: "Sometimes", score: 1 }, { text: "Often", score: 2 }],
        uiType: "radio"
    },
    {
        text: "How much do your physical health concerns affect your daily life?",
        category: "somatic",
        assessmentType: "self",
        type: "scale",
        options: [{ text: "Not at all", score: 0 }, { text: "Moderately", score: 1 }, { text: "Severely", score: 2 }],
        uiType: "radio"
    },
    {
        text: "In social situations, how often do you feel extremely self-conscious?",
        category: "social_anxiety",
        assessmentType: "self",
        type: "scale",
        options: [{ text: "Rarely", score: 0 }, { text: "Often", score: 1 }, { text: "Always", score: 2 }],
        uiType: "radio"
    },
    {
        text: "Have you had any trouble with alcohol or substance use recently?",
        category: "substance_use",
        assessmentType: "self",
        type: "boolean",
        options: [{ text: "Yes", score: 1 }, { text: "No", score: 0 }],
        uiType: "radio"
    },
    {
        text: "How would you describe your current appetite?",
        category: "eating_disorder",
        assessmentType: "self",
        type: "scale",
        options: [{ text: "Normal", score: 0 }, { text: "Increased", score: 1 }, { text: "Decreased", score: 1 }],
        uiType: "radio"
    },
    {
        text: "Do you feel optimistic about your future?",
        category: "purpose",
        assessmentType: "self",
        type: "scale",
        options: [{ text: "No", score: 0 }, { text: "Sometimes", score: 1 }, { text: "Yes", score: 2 }],
        uiType: "radio"
    },
    {
        text: "Do you feel supported by your family or friends?",
        category: "social",
        assessmentType: "self",
        type: "boolean",
        options: [{ text: "Yes", score: 0 }, { text: "No", score: 1 }],
        uiType: "radio"
    },
    {
        text: "How satisfied are you with your primary relationship or social life?",
        category: "family",
        assessmentType: "self",
        type: "scale",
        options: [{ text: "Satisfied", score: 0 }, { text: "Neutral", score: 1 }, { text: "Unsatisfied", score: 2 }],
        uiType: "radio"
    },
    {
        text: "Are you currently feeling any significant financial or career stress?",
        category: "financial",
        assessmentType: "self",
        type: "boolean",
        options: [{ text: "Yes", score: 1 }, { text: "No", score: 0 }],
        uiType: "radio"
    },
    {
        text: "How do you rate your self-confidence right now?",
        category: "self-image",
        assessmentType: "self",
        type: "scale",
        options: [{ text: "Low", score: 0 }, { text: "Average", score: 1 }, { text: "High", score: 2 }],
        uiType: "radio"
    }
];

async function seedSelfQuestions() {
    try {
        // Clear existing self questions to avoid duplicates and ensure exactly 20
        await Question.deleteMany({ assessmentType: 'self' });
        console.log('Cleared old self-assessment questions.');

        for (const q of selfQuestions) {
            await Question.create(q);
        }
        console.log(`Successfully seeded ${selfQuestions.length} self-assessment questions.`);
    } catch (err) {
        console.error('Seeding self-assessment failed:', err);
        throw err;
    }
}

module.exports = seedSelfQuestions;
