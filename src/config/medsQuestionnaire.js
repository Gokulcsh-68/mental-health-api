/**
 * @desc  Medication & Adherence Questionnaire Definition
 */
const MedsQuestionnaire = [
    {
        section: 'current_regimen',
        title: 'Current Medications',
        questions: [
            {
                key: 'taking_meds',
                professional_label: 'Currently Prescribed Psychotropic Medication',
                patient_label: 'Taking Mental Health Meds',
                type: 'boolean',
                follow_up: [
                    { key: 'med_list', label: 'List medications and doses', type: 'text', placeholder: 'e.g. Sertraline 50mg daily' }
                ]
            }
        ]
    },
    {
        section: 'adherence',
        title: 'Medication Adherence',
        questions: [
            {
                key: 'adherence_level',
                professional_label: 'Medication Adherence Level',
                patient_label: 'How often do you take your meds?',
                type: 'select',
                options: ['Always as prescribed', 'Most of the time', 'Sometimes', 'Rarely', 'Not at all']
            },
            {
                key: 'barriers',
                professional_label: 'Barriers to Adherence',
                patient_label: 'What makes it hard to take meds?',
                type: 'multiselect',
                options: ['Forgetfulness', 'Side effects', 'Cost', 'Don\'t think they help', 'Stigma', 'Pharmacy access']
            }
        ]
    },
    {
        section: 'side_effects',
        title: 'Side Effects Screening',
        questions: [
            {
                key: 'common_side_effects',
                professional_label: 'Side Effect Presence',
                patient_label: 'Any side effects?',
                type: 'multiselect',
                options: ['Weight gain', 'Sedation/Sleepiness', 'Insomnia', 'Nausea', 'Tremors/Shakes', 'Sexual dysfunction', 'Dry mouth', 'Other']
            }
        ]
    }
];

module.exports = MedsQuestionnaire;
