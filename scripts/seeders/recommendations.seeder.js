const mongoose = require('mongoose');
const config = require('../../src/config/config');
const Recommendation = require('../../src/models/Recommendation');

const recommendations = [
    // Mood
    {
        category: "mood",
        minPercentage: 0,
        maxPercentage: 30,
        text: "It sounds like you're having a very tough time. Please consider reaching out to our support team or a loved one immediately.",
        priority: 10
    },
    {
        category: "mood",
        minPercentage: 31,
        maxPercentage: 70,
        text: "You're showing some signs of low mood. Try to engage in one activity you usually enjoy today, even for 5 minutes.",
        priority: 5
    },
    {
        category: "mood",
        minPercentage: 71,
        maxPercentage: 100,
        text: "You seem to be in a good headspace! Keep maintaining your positive daily habits.",
        priority: 1
    },
    // Sleep
    {
        category: "sleep",
        minPercentage: 0,
        maxPercentage: 50,
        text: "Poor sleep affects everything. Try a 'digital detox'—no screens for 1 hour before bed tonight.",
        actionLabel: "View Sleep Guide",
        actionUrl: "/resources/sleep-hygiene",
        priority: 8
    },
    // Anxiety
    {
        category: "anxiety",
        minPercentage: 0,
        maxPercentage: 40,
        text: "When you feel overwhelmed, try the 4-7-8 breathing technique: Inhale for 4s, hold for 7s, exhale for 8s.",
        priority: 9
    }
];

const seedRecommendations = async () => {
    try {
        await Recommendation.deleteMany({}); // Clear existing for testing
        await Recommendation.insertMany(recommendations);

        console.log('Recommendations seeding completed');
    } catch (err) {
        console.error('Seeding failed:', err);
        throw err;
    }
};

module.exports = seedRecommendations;
