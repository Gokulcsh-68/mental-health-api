/**
 * @desc  Past History Questionnaire Definition
 *        Used by GET /api/v1/past-history/questions to drive the frontend form
 */
const PastHistoryQuestionnaire = [
    {
        section: 'psychiatric_past',
        title: 'Psychiatric History',
        questions: [
            {
                key: 'previous_diagnosis',
                professional_label: 'Prior Psychiatric Diagnoses',
                patient_label: 'Past Diagnoses',
                type: 'multiselect',
                options: ['Depression', 'Anxiety', 'Bipolar', 'Schizophrenia', 'PTSD', 'OCD', 'ADHD', 'Eating Disorder', 'Personality Disorder', 'Other'],
                allow_custom: true
            },
            {
                key: 'hospitalizations',
                professional_label: 'Prior Psychiatric Hospitalizations',
                patient_label: 'Prior Hospital Stays',
                type: 'array',
                item_structure: [
                    { key: 'year', label: 'Year', type: 'text' },
                    { key: 'reason', label: 'Reason', type: 'text' },
                    { key: 'location', label: 'Location', type: 'text' },
                    { key: 'duration', label: 'Duration', type: 'text' }
                ]
            },
            {
                key: 'suicide_attempts',
                professional_label: 'Suicide Attempts / Self-Harm History',
                patient_label: 'History of Self-Harm',
                type: 'array',
                item_structure: [
                    { key: 'year', label: 'Year', type: 'text' },
                    { key: 'method', label: 'Method', type: 'text' },
                    { key: 'intent', label: 'Intent', type: 'text' }
                ]
            },
            {
                key: 'medication_trials',
                professional_label: 'Medication History',
                patient_label: 'Past Medications',
                type: 'array',
                item_structure: [
                    { key: 'name', label: 'Medication Name', type: 'text' },
                    { key: 'dose', label: 'Dose', type: 'text' },
                    { key: 'duration', label: 'How long?', type: 'text' },
                    { key: 'response', label: 'Response (Helpful?)', type: 'text' },
                    { key: 'side_effects', label: 'Side Effects', type: 'text' }
                ]
            },
            {
                key: 'psychotherapy_history',
                professional_label: 'Psychotherapy History',
                patient_label: 'History of Therapy',
                type: 'textarea',
                placeholder: 'Types of therapy, duration, and helpfulness'
            }
        ]
    },
    {
        section: 'medical_surgical',
        title: 'Medical & Surgical History',
        questions: [
            {
                key: 'chronic_conditions',
                professional_label: 'Chronic Medical Conditions',
                patient_label: 'Ongoing Health Issues',
                type: 'multiselect',
                options: ['Hypertension', 'Diabetes', 'Thyroid Disorder', 'Seizures', 'Asthma', 'Heart Disease', 'Migraines', 'Chronic Pain', 'None'],
                allow_custom: true
            },
            {
                key: 'surgeries',
                professional_label: 'Surgical History',
                patient_label: 'Past Operations',
                type: 'array',
                item_structure: [
                    { key: 'procedure', label: 'Procedure', type: 'text' },
                    { key: 'year', label: 'Year', type: 'text' }
                ]
            },
            {
                key: 'head_injury',
                professional_label: 'History of Head Injury',
                patient_label: 'Any Head Injuries?',
                type: 'boolean_group',
                fields: [
                    { key: 'detected', label: 'Have you ever had a head injury?', type: 'boolean' },
                    { key: 'loss_of_consciousness', label: 'Did you lose consciousness?', type: 'boolean' },
                    { key: 'details', label: 'Details', type: 'text' }
                ]
            },
            {
                key: 'seizures',
                professional_label: 'Seizure History',
                patient_label: 'History of Seizures',
                type: 'boolean_group',
                fields: [
                    { key: 'detected', label: 'Ever had a seizure?', type: 'boolean' },
                    { key: 'frequency', label: 'Frequency', type: 'text' },
                    { key: 'last_seizure', label: 'Last Seizure Date', type: 'text' }
                ]
            },
            {
                key: 'allergies',
                professional_label: 'Allergies',
                patient_label: 'Allergies',
                type: 'multiselect',
                options: ['Drug Allergies', 'Food Allergies', 'Environmental Allergies', 'Latex', 'None'],
                allow_custom: true
            }
        ]
    },
    {
        section: 'family_history',
        title: 'Family History',
        questions: [
            {
                key: 'conditions',
                professional_label: 'Family Mental Health / Substance History',
                patient_label: 'Family Health History',
                type: 'array',
                item_structure: [
                    { key: 'relative', label: 'Relative (e.g. Mother)', type: 'text' },
                    { key: 'condition', label: 'Condition (e.g. Bipolar)', type: 'text' },
                    { key: 'outcome', label: 'Outcome/Notes', type: 'text' }
                ]
            },
            {
                key: 'suicide_in_family',
                professional_label: 'Family History of Suicide',
                patient_label: 'Family History of Suicide',
                type: 'boolean'
            },
            {
                key: 'substance_abuse_in_family',
                professional_label: 'Family History of Substance Abuse',
                patient_label: 'Family History of Drug/Alcohol Problems',
                type: 'boolean'
            }
        ]
    },
    {
        section: 'substance_history',
        title: 'Substance Use History',
        questions: [
            {
                key: 'alcohol',
                professional_label: 'Alcohol Use',
                patient_label: 'Alcohol consumption',
                type: 'boolean_group',
                fields: [
                    { key: 'status', label: 'Current Status', type: 'select', options: ['Current', 'Past', 'Never'] },
                    { key: 'quantity', label: 'Quantity (drinks/week)', type: 'text' },
                    { key: 'frequency', label: 'Frequency', type: 'text' },
                    { key: 'last_use', label: 'Last Drink', type: 'text' }
                ]
            },
            {
                key: 'tobacco_nicotine',
                professional_label: 'Tobacco / Nicotine',
                patient_label: 'Smoking / Vaping',
                type: 'boolean_group',
                fields: [
                    { key: 'status', label: 'Status', type: 'select', options: ['Current', 'Past', 'Never'] },
                    { key: 'type', label: 'Type', type: 'text' },
                    { key: 'quantity', label: 'Pack years / daily use', type: 'text' }
                ]
            },
            {
                key: 'illicit_drugs',
                professional_label: 'Illicit Drug Use',
                patient_label: 'Recreational Drugs',
                type: 'array',
                item_structure: [
                    { key: 'drug', label: 'Drug Name', type: 'text' },
                    { key: 'status', label: 'Status', type: 'select', options: ['Current', 'Past'] },
                    { key: 'frequency', label: 'Frequency', type: 'text' },
                    { key: 'last_use', label: 'Last Use', type: 'text' }
                ]
            },
            { key: 'caffeine', professional_label: 'Caffeine Intake', patient_label: 'Daily Caffeine', type: 'text' },
            { key: 'prescription_misuse', professional_label: 'Prescription Misuse', patient_label: 'Misuse of prescribed meds?', type: 'text' }
        ]
    },
    {
        section: 'developmental_history',
        title: 'Developmental History',
        questions: [
            { key: 'pregnancy_complications', professional_label: 'Birth/Pregnancy Complications', patient_label: 'Birth Issues', type: 'text' },
            { key: 'delivery_type', professional_label: 'Delivery Type', type: 'select', options: ['Normal', 'C-Section', 'Forceps', 'Other'] },
            { key: 'milestones', professional_label: 'Developmental Milestones', type: 'select', options: ['On-time', 'Delayed', 'Early'] },
            { key: 'childhood_behavior', professional_label: 'Childhood Behavior/Temperament', type: 'text' },
            { key: 'school_performance', professional_label: 'Academic Performance', type: 'text' }
        ]
    },
    {
        section: 'social_history',
        title: 'Personal & Social History',
        questions: [
            { key: 'education', professional_label: 'Highest Education', type: 'select', options: ['Primary', 'High School', 'Vocational', 'Undergraduate', 'Graduate', 'Doctorate'] },
            { key: 'employment', professional_label: 'Current Employment', type: 'text' },
            { key: 'marital_status', professional_label: 'Relationship Status', type: 'select', options: ['Single', 'Married', 'Partnered', 'Divorced', 'Widowed'] },
            { key: 'living_situation', professional_label: 'Living Situation', type: 'text' },
            {
                key: 'legal_history',
                professional_label: 'Legal History',
                type: 'boolean_group',
                fields: [
                    { key: 'legal_issues', label: 'Any Legal Issues?', type: 'boolean' },
                    { key: 'legal_details', label: 'Details', type: 'text' }
                ]
            },
            { key: 'spiritual_beliefs', professional_label: 'Spiritual/Cultural Beliefs', type: 'text' },
            { key: 'strengths_hobbies', professional_label: 'Strengths & Hobbies', type: 'text' }
        ]
    },
    {
        section: 'trauma_history',
        title: 'Trauma & Abuse History',
        questions: [
            { key: 'physical_abuse', professional_label: 'History of Physical Abuse', type: 'boolean' },
            { key: 'emotional_abuse', professional_label: 'History of Emotional Abuse', type: 'boolean' },
            { key: 'sexual_abuse', professional_label: 'History of Sexual Abuse', type: 'boolean' },
            { key: 'significant_losses', professional_label: 'Significant Losses / Grief', type: 'text' },
            { key: 'military_service', professional_label: 'Military Service History', type: 'boolean' },
            { key: 'trauma_notes', professional_label: 'Additional Trauma Notes', type: 'textarea' }
        ]
    }
];

module.exports = PastHistoryQuestionnaire;
