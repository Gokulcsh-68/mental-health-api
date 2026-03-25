/**
 * @desc  Safety & Risk Questionnaire Definition
 */
const SafetyQuestionnaire = [
    {
        section: 'suicide_risk',
        title: 'Suicide Risk Assessment',
        questions: [
            {
                key: 'suicide_ideation',
                professional_label: 'Suicidal Ideation',
                patient_label: 'Thoughts of Self-Harm',
                type: 'select',
                options: ['None', 'Passive (wish to be dead)', 'Active (intent to die)', 'With Plan', 'With Intent to Act'],
                follow_up: [
                    { key: 'suicide_plan', label: 'Details of Plan', type: 'text' },
                    { key: 'access_to_means', label: 'Access to Means (e.g. weapons, drugs)', type: 'boolean' }
                ]
            },
            {
                key: 'past_attempts',
                professional_label: 'Prior Suicide Attempts',
                patient_label: 'Past Attempts',
                type: 'boolean',
                follow_up: [
                    { key: 'attempt_count', label: 'How many times?', type: 'number' },
                    { key: 'latest_attempt_date', label: 'Most recent date', type: 'text' }
                ]
            },
            {
                key: 'protective_factors',
                professional_label: 'Protective Factors',
                patient_label: 'Reasons to Keep Going',
                type: 'multiselect',
                options: ['Family/Children', 'Religious beliefs', 'Future goals', 'Supportive friends', 'Pets', 'Employment']
            }
        ]
    },
    {
        section: 'violence_risk',
        title: 'Violence & Aggression Risk',
        questions: [
            {
                key: 'homicidal_ideation',
                professional_label: 'Homicidal Ideation',
                patient_label: 'Thoughts of Harming Others',
                type: 'select',
                options: ['None', 'Passive ideation', 'Active ideation', 'With specific target', 'With plan']
            },
            {
                key: 'history_of_violence',
                professional_label: 'History of Aggressive Behavior',
                patient_label: 'Past Aggression',
                type: 'boolean'
            },
            {
                key: 'access_to_weapons',
                professional_label: 'Access to Weapons',
                patient_label: 'Access to Weapons/Firearms',
                type: 'boolean'
            }
        ]
    },
    {
        section: 'vulnerability',
        title: 'Vulnerability & Safety',
        questions: [
            {
                key: 'self_neglect',
                professional_label: 'Self-Neglect Risk',
                patient_label: 'Ability to Care for Self',
                type: 'select',
                options: ['No risk', 'Mild (forgetting meals)', 'Moderate', 'Severe (unable to care for hygiene/safety)']
            },
            {
                key: 'abuse_screening',
                professional_label: 'Abuse / Victimization Screening',
                patient_label: 'Safety at Home',
                type: 'boolean',
                patient_description: 'Do you feel safe in your current living environment?',
                follow_up: [{ key: 'safety_concerns', label: 'Please describe your concerns', type: 'text' }]
            }
        ]
    }
];

module.exports = SafetyQuestionnaire;
