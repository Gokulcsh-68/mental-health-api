/**
 * @desc  Treatment Progress & Outlook Questionnaire Definition
 */
const ProgressQuestionnaire = [
    {
        section: 'subjective_progress',
        title: 'Your Progress',
        questions: [
            {
                key: 'overall_improvement',
                professional_label: 'Subjective Global Improvement',
                patient_label: 'Overall Progress',
                type: 'select',
                options: ['Very much improved', 'Much improved', 'Minimally improved', 'No change', 'Minimally worse', 'Much worse']
            },
            {
                key: 'symptom_change',
                professional_label: 'Symptom Severity Change',
                patient_label: 'How are your symptoms now compared to last time?',
                type: 'select',
                options: ['Significantly better', 'Slightly better', 'About the same', 'Slightly worse', 'Significantly worse']
            }
        ]
    },
    {
        section: 'goal_status',
        title: 'Treatment Goals',
        questions: [
            {
                key: 'goal_attainment',
                professional_label: 'Goal Attainment Scaling',
                patient_label: 'Progress on your personal goals',
                type: 'select',
                options: ['Goal achieved', 'Significant progress', 'Some progress', 'No progress yet']
            },
            {
                key: 'new_goals',
                professional_label: 'New Therapeutic Objectives',
                patient_label: 'Any new things you want to work on?',
                type: 'text'
            }
        ]
    },
    {
        section: 'outlook',
        title: 'Outlook & Motivation',
        questions: [
            {
                key: 'motivation',
                professional_label: 'Treatment Motivation / Readiness',
                patient_label: 'How motivated do you feel to continue treatment?',
                type: 'select',
                options: ['Highly motivated', 'Moderately motivated', 'Unsure', 'Lacking motivation']
            },
            {
                key: 'hopefulness',
                professional_label: 'Degree of Hopefulness',
                patient_label: 'How hopeful do you feel about the future?',
                type: 'select',
                options: ['Very hopeful', 'Somewhat hopeful', 'Neutral', 'Feeling hopeless']
            }
        ]
    }
];

module.exports = ProgressQuestionnaire;
