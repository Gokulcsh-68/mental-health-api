const Feedback = require('../../src/models/Feedback');
const User = require('../../src/models/User');

const seedFeedback = async () => {
    try {
        const karthik = await User.findOne({ username: 'karthik' });
        const gokul = await User.findOne({ username: 'Gokulgv' });

        if (!karthik || !gokul) {
            console.log('  ⚠️  Skipping feedback seeding: Test users (karthik/Gokulgv) not found');
            return;
        }

        const feedbackData = [
            // Bug Reports
            {
                userId: karthik._id,
                subject: 'Video call lagging',
                message: 'The video call starts lagging after 10 minutes specifically on mobile data.',
                category: 'bug',
                status: 'in_progress',
                adminNotes: 'Assigned to dev team to check WebRTC optimization.'
            },
            {
                userId: gokul._id,
                subject: 'Dark mode contrast',
                message: 'The text contrast in dark mode is too low on the profile screen.',
                category: 'bug',
                status: 'open'
            },
            // Feature Requests
            {
                userId: karthik._id,
                subject: 'Calm music in lobby',
                message: 'It would be nice to have some soothing background music while waiting in the virtual lobby.',
                category: 'feature_request',
                status: 'open'
            },
            // Complaints
            {
                userId: gokul._id,
                subject: 'Billing discrepancy',
                message: 'I was charged twice for my last session with Dr. Priya.',
                category: 'complaint',
                status: 'resolved',
                adminNotes: 'Duplicate transaction refunded.',
                resolvedAt: new Date()
            },
            // App Ratings
            {
                userId: karthik._id,
                subject: 'App Store Rating',
                message: 'Very helpful platform for mental health support.',
                category: 'app_rating',
                rating: 5,
                status: 'open'
            },
            {
                userId: gokul._id,
                subject: 'App Store Rating',
                message: 'The interface is clean but needs more local language support.',
                category: 'app_rating',
                rating: 4,
                status: 'open'
            }
        ];

        await Feedback.deleteMany();
        await Feedback.insertMany(feedbackData);
        console.log(`  ✅ Seeded ${feedbackData.length} feedback tickets and ratings`);
    } catch (err) {
        console.error('  ❌ Feedback seeder failed:', err.message);
    }
};

module.exports = seedFeedback;
