const Question = require('../../src/models/Question');

async function seedProfessionalQuestions() {
    try {
        // Mark all existing questions that are NOT 'self' as 'professional'
        const result = await Question.updateMany(
            { assessmentType: { $ne: 'self' } },
            { $set: { assessmentType: 'professional' } }
        );

        console.log(`Successfully updated ${result.modifiedCount} questions to 'professional'.`);
    } catch (err) {
        console.error('Seeding professional assessment failed:', err);
        throw err;
    }
}

module.exports = seedProfessionalQuestions;
