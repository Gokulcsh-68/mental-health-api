/**
 * @desc  MSE Questionnaire Definition
 *        Used by GET /api/v1/mse/questions
 */
const MSEQuestionnaire = [
    {
        section: 'appearance',
        title: 'Appearance',
        description: "Physical presentation and grooming",
        questions: [
            { key: 'grooming', professional_label: 'Grooming', patient_label: 'Personal Care', type: 'select', options: ['Well groomed', 'Adequately groomed', 'Disheveled', 'Unkempt', 'Bizarre'] },
            { key: 'dress', professional_label: 'Dress / Attire', patient_label: 'Clothing', type: 'select', options: ['Appropriate', 'Casual', 'Bizarre', 'Inappropriate'] },
            { key: 'eye_contact', professional_label: 'Eye Contact', patient_label: 'Eye Contact', type: 'select', options: ['Normal', 'Avoidant', 'Intense', 'Absent'] }
        ]
    },
    {
        section: 'behavior',
        title: 'Behavior & Psychomotor Activity',
        questions: [
            { key: 'attitude', professional_label: 'Attitude', patient_label: 'Attitude', type: 'select', options: ['Cooperative', 'Guarded', 'Hostile', 'Withdrawn'] },
            { key: 'psychomotor', professional_label: 'Psychomotor Activity', patient_label: 'Movement Level', type: 'select', options: ['Normal', 'Agitated', 'Retarded (slow)', 'Catatonic'] },
            { key: 'mannerisms', professional_label: 'Abnormal Movements', patient_label: 'Repeated Movements', type: 'multiselect', options: ['Tremor', 'Tics', 'Stereotypies', 'None'] }
        ]
    },
    {
        section: 'speech',
        title: 'Speech',
        questions: [
            { key: 'rate', professional_label: 'Rate', patient_label: 'Speaking Speed', type: 'select', options: ['Normal', 'Pressured (fast)', 'Slow', 'Mutism'] },
            { key: 'volume', professional_label: 'Volume', patient_label: 'Loudness', type: 'select', options: ['Normal', 'Loud', 'Soft', 'Whispering'] },
            { key: 'articulation', professional_label: 'Articulation', patient_label: 'Clarity', type: 'select', options: ['Clear', 'Slurred', 'Stuttering'] }
        ]
    },
    {
        section: 'mood_affect',
        title: 'Mood & Affect',
        questions: [
            { key: 'subjective_mood', professional_label: 'Subjective Mood', patient_label: 'How do you describe your mood?', type: 'text' },
            { key: 'affect_quality', professional_label: 'Affect Quality', patient_label: 'Observed Emotion', type: 'select', options: ['Euthymic', 'Depressed', 'Elevated', 'Anxious', 'Irritable'] },
            { key: 'affect_range', professional_label: 'Affect Range', patient_label: 'Range of Expression', type: 'select', options: ['Full', 'Constricted', 'Blunted', 'Flat', 'Labile'] }
        ]
    },
    {
        section: 'thought_process',
        title: 'Thought Process (Form)',
        questions: [
            { key: 'process', professional_label: 'Thought Process', patient_label: 'Organization of Thoughts', type: 'select', options: ['Logical/Goal-directed', 'Tangential', 'Circumstantial', 'Flight of ideas', 'Loose associations', 'Incoherent'] }
        ]
    },
    {
        section: 'thought_content',
        title: 'Thought Content',
        questions: [
            { key: 'suicidal_ideation', professional_label: 'Suicidal Ideation', patient_label: 'Thoughts of Self-Harm', type: 'select', options: ['None', 'Passive', 'Active', 'With Plan'] },
            { key: 'homicidal_ideation', professional_label: 'Homicidal Ideation', patient_label: 'Thoughts of Harming Others', type: 'select', options: ['None', 'Passive', 'Active', 'With Target'] },
            { key: 'delusions', professional_label: 'Delusions', patient_label: 'False Beliefs', type: 'boolean', follow_up: [{ key: 'delusion_type', label: 'Type', type: 'multiselect', options: ['Paranoid', 'Grandiose', 'Somatic', 'Reference'] }] }
        ]
    },
    {
        section: 'perception',
        title: 'Perception',
        questions: [
            { key: 'hallucinations', professional_label: 'Hallucinations', patient_label: 'Strange Perceptions', type: 'boolean', follow_up: [{ key: 'hallucination_type', label: 'Type', type: 'multiselect', options: ['Auditory', 'Visual', 'Olfactory', 'Tactile'] }] },
            { key: 'depersonalization', professional_label: 'Depersonalization / Derealization', patient_label: 'Feeling Unaware or Detached', type: 'boolean' }
        ]
    },
    {
        section: 'insight_judgment',
        title: 'Insight & Judgment',
        questions: [
            { key: 'insight', professional_label: 'Insight', patient_label: 'Awareness of Illness', type: 'select', options: ['Good', 'Partial', 'Poor', 'Absent'] },
            { key: 'judgment', professional_label: 'Judgment', patient_label: 'Decision Making', type: 'select', options: ['Intact', 'Fair', 'Poor', 'Grossly impaired'] }
        ]
    },
    {
        section: 'cognition',
        title: 'Cognition',
        questions: [
            { key: 'orientation', professional_label: 'Orientation', patient_label: 'Awareness of Surroundings', type: 'multiselect', options: ['Person', 'Place', 'Time', 'Situation'] },
            { key: 'memory', professional_label: 'Memory', patient_label: 'Memory', type: 'select', options: ['Intact', 'Mildly impaired', 'Severely impaired'] },
            { key: 'attention', professional_label: 'Attention / Concentration', patient_label: 'Focus', type: 'select', options: ['Intact', 'Distractible', 'Severely impaired'] }
        ]
    }
];

module.exports = MSEQuestionnaire;
