/**
 * @desc  Functional & Social Support Questionnaire Definition
 */
const FunctionalQuestionnaire = [
    {
        section: 'functional_status',
        title: 'Daily Functioning',
        questions: [
            {
                key: 'adl_status',
                professional_label: 'Activities of Daily Living (ADLs)',
                patient_label: 'Caring for Yourself Daily',
                type: 'select',
                options: ['Independent', 'Needs occasional help', 'Needs significant help', 'Unable to perform']
            },
            {
                key: 'work_school_performance',
                professional_label: 'Occupational / Academic Functioning',
                patient_label: 'Performance at Work/School',
                type: 'select',
                options: ['Excellent', 'Good (minor issues)', 'Fair (missing days)', 'Poor (at risk of job loss/failing)', 'N/A']
            }
        ]
    },
    {
        section: 'social_support',
        title: 'Social Support & Environment',
        questions: [
            {
                key: 'support_network',
                professional_label: 'Social Support Network',
                patient_label: 'Support from Family/Friends',
                type: 'select',
                options: ['Strong support', 'Adequate', 'Minimal', 'Isolated']
            },
            {
                key: 'financial_stress',
                professional_label: 'Financial Stressors',
                patient_label: 'Money Stress',
                type: 'select',
                options: ['None', 'Mild', 'Moderate', 'Severe/Crisis']
            }
        ]
    },
    {
        section: 'coping',
        title: 'Coping & Resilience',
        questions: [
            {
                key: 'coping_mechanisms',
                professional_label: 'Coping Strategies',
                patient_label: 'How do you handle stress?',
                type: 'multiselect',
                options: ['Exercise', 'Talking to friends', 'Meditation/Prayer', 'Hobbies', 'Withdrawal', 'Substance use', 'Self-harm']
            }
        ]
    }
];

module.exports = FunctionalQuestionnaire;
