/**
 * @desc  Filters and formats questionnaire items based on demographics and view mode
 * @param {Array} questionnaire - The raw questionnaire config array
 * @param {Object} options - { age: Number, gender: String, view: 'patient' | 'professional' }
 * @returns {Array} - Filtered and formatted questionnaire
 */
const filterQuestions = (questionnaire, { age, gender, view = 'professional' }) => {
    return questionnaire.map(section => {
        const filteredQuestions = section.questions.filter(q => {
            if (!q.visibility_rules) return true;

            const { min_age, max_age, genders } = q.visibility_rules;

            if (min_age !== undefined && age !== null && age < min_age) return false;
            if (max_age !== undefined && age !== null && age > max_age) return false;
            if (genders !== undefined && gender !== null && !genders.includes(gender.toLowerCase())) return false;

            return true;
        }).map(q => {
            // Select appropriate label/description based on view
            const formatted = {
                ...q,
                label: (view === 'patient' && q.patient_label) ? q.patient_label : (q.professional_label || q.label),
                description: (view === 'patient' && q.patient_description) ? q.patient_description : (q.professional_description || q.description)
            };

            // Recursively filter follow-up questions
            if (formatted.follow_up) {
                formatted.follow_up = formatted.follow_up.map(fu => ({
                    ...fu,
                    label: (view === 'patient' && fu.patient_label) ? fu.patient_label : (fu.professional_label || fu.label)
                }));
            }

            return formatted;
        });

        return {
            ...section,
            questions: filteredQuestions
        };
    }).filter(section => section.questions.length > 0);
};

module.exports = { filterQuestions };
