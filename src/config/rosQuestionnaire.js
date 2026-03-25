/**
 * @desc  ROS Questionnaire Definition
 *        Used by GET /api/v1/ros/questions to drive the frontend form
 */
const ROSQuestionnaire = [
    {
        section: 'psychiatric_mood',
        title: 'Mood & Neurovegetative Symptoms',
        questions: [
            {
                key: 'depressed_mood',
                professional_label: 'Depressed Mood / Anhedonia',
                patient_label: 'Feeling Sad or Low',
                type: 'boolean',
                professional_description: 'Persistent sadness, emptiness, or hopelessness',
                patient_description: 'Have you felt constantly sad, down, or "empty" lately?',
                follow_up: [
                    { key: 'depressed_duration', professional_label: 'Duration', patient_label: 'How long has this been going on?', type: 'text' },
                    { key: 'depressed_severity', professional_label: 'Severity (1-10)', patient_label: 'How bad is it on a scale of 1 to 10?', type: 'number', min: 1, max: 10 }
                ]
            },
            {
                key: 'anxiety',
                professional_label: 'Anxiety / Excessive Worry',
                patient_label: 'Worry or Nervousness',
                type: 'boolean',
                patient_description: 'Do you feel constantly worried, nervous, or afraid of something bad happening?',
                follow_up: [
                    { key: 'anxiety_type', label: 'Type', type: 'select', options: ['Generalized worry', 'Panic attacks', 'Social anxiety', 'Specific phobia', 'Other'] }
                ]
            },
            {
                key: 'sleep_disturbance',
                professional_label: 'Sleep Disturbance',
                patient_label: 'Sleep Problems',
                type: 'boolean',
                patient_description: 'Have you had trouble falling asleep, staying asleep, or sleeping too much?',
                follow_up: [
                    { key: 'sleep_type', label: 'Type of sleep issue', type: 'multiselect', options: ['Initial insomnia (trouble falling asleep)', 'Middle insomnia (waking up)', 'Terminal insomnia (waking too early)', 'Hypersomnia (sleeping too much)'] }
                ]
            },
            {
                key: 'appetite_change',
                professional_label: 'Appetite / Weight Change',
                patient_label: 'Appetite or Weight Changes',
                type: 'boolean',
                patient_description: 'Have you noticed a significant change in your appetite or weight?',
                follow_up: [
                    { key: 'appetite_direction', label: 'Change type', type: 'select', options: ['Increased appetite/weight gain', 'Decreased appetite/weight loss'] }
                ]
            },
            {
                key: 'energy_fatigue',
                professional_label: 'Anergia / Fatigue',
                patient_label: 'Low Energy or Fatigue',
                type: 'boolean',
                patient_description: 'Do you feel constantly tired or lacking energy even after resting?'
            },
            {
                key: 'concentration',
                professional_label: 'Concentration Deficit',
                patient_label: 'Trouble Concentrating',
                type: 'boolean',
                patient_description: 'Do you find it hard to focus, read, or make decisions?'
            }
        ]
    },
    {
        section: 'psychiatric_specialty',
        title: 'Specialty Psychiatric Screening',
        questions: [
            {
                key: 'mania',
                professional_label: 'Mania / Hypomania',
                patient_label: 'Periods of High Energy',
                type: 'boolean',
                patient_description: 'Have you had times where you felt unusually "high," super energetic, or didn\'t need much sleep?',
                follow_up: [
                    { key: 'mania_features', label: 'What did you notice?', type: 'multiselect', options: ['Decreased sleep need', 'Racing thoughts', 'Grandiosity', 'Impulsive spending', 'Hypersexuality', 'Pressured speech'] }
                ]
            },
            {
                key: 'psychosis',
                professional_label: 'Psychosis / Perceptual Disturbances',
                patient_label: 'Strange Experiences',
                type: 'boolean',
                patient_description: 'Have you seen or heard things others don\'t, or had thoughts that others find hard to believe?',
                follow_up: [
                    { key: 'psychosis_type', label: 'Details', type: 'multiselect', options: ['Auditory hallucinations', 'Visual hallucinations', 'Paranoid delusions', 'Grandiose delusions', 'Thought insertion'] }
                ]
            },
            {
                key: 'ocd_symptoms',
                professional_label: 'Obsessive-Compulsive Symptoms',
                patient_label: 'Unwanted Thoughts or Behaviors',
                type: 'boolean',
                professional_description: 'Recurrent intrusive thoughts and/or repetitive behaviors',
                patient_description: 'Do you have repetitive thoughts that you can\'t stop, or feel you MUST do certain things over and over?'
            },
            {
                key: 'ptsd_symptoms',
                professional_label: 'Trauma / PTSD Symptoms',
                patient_label: 'Trauma or Flashbacks',
                type: 'boolean',
                professional_description: 'Flashbacks, avoidance, or hypervigilance following a traumatic event',
                patient_description: 'Do you ever have intense memories or nightmares about a past scary event, or avoid things that remind you of it?'
            },
            {
                key: 'eating_disorder',
                professional_label: 'Eating Disorder Screening',
                patient_label: 'Eating Habits & Body Image',
                type: 'boolean',
                patient_description: 'Are you very concerned about your body weight or have unusual eating habits (like restricting or bingeing)?'
            }
        ]
    },
    {
        section: 'medical_rule_out',
        title: 'Medical Rule-Out',
        description: 'Physical symptoms that can affect mental health',
        questions: [
            {
                key: 'neurological',
                professional_label: 'Neurological Symptoms',
                patient_label: 'Headaches or Dizziness',
                type: 'boolean',
                patient_description: 'Have you had frequent headaches, dizziness, or fainting spells?',
                follow_up: [
                    { key: 'neurological_type', label: 'Type', type: 'multiselect', options: ['Headaches', 'Dizziness/Vertigo', 'Seizures', 'Memory loss', 'Weakness/Numbness'] }
                ]
            },
            {
                key: 'cardiovascular',
                professional_label: 'Cardiovascular / Respiratory',
                patient_label: 'Heart or Breathing Issues',
                type: 'boolean',
                patient_description: 'Have you felt your heart racing (palpitations) or had trouble breathing?',
                follow_up: [
                    { key: 'cardio_type', label: 'Details', type: 'multiselect', options: ['Palpitations', 'Chest pain', 'Shortness of breath'] }
                ]
            },
            {
                key: 'thyroid_symptoms',
                professional_label: 'Thyroid Dysfunction',
                patient_label: 'Thyroid Problems',
                type: 'boolean',
                patient_description: 'Have you had unexplained weight changes, feeling unusually cold/hot, or hair loss?'
            },
            {
                key: 'hormonal_changes',
                professional_label: 'Hormonal / Reproductive',
                patient_label: 'Hormonal Life Events',
                type: 'boolean',
                visibility_rules: { genders: ['female'] },
                patient_description: 'Are you currently pregnant, postpartum, or experiencing menopause changes?',
                follow_up: [
                    { key: 'hormonal_type', label: 'Type', type: 'select', options: ['Pregnancy', 'Postpartum', 'Menopause', 'PCOS'] }
                ]
            }
        ]
    }
];

module.exports = ROSQuestionnaire;
