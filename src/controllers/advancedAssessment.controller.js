const { filterQuestions } = require('../utils/questionFilter');
const SafetyQuestionnaire = require('../config/safetyQuestionnaire');
const MedsQuestionnaire = require('../config/medsQuestionnaire');
const FunctionalQuestionnaire = require('../config/functionalQuestionnaire');
const ProgressQuestionnaire = require('../config/progressQuestionnaire');

const questionnaires = {
    safety: SafetyQuestionnaire,
    meds: MedsQuestionnaire,
    functional: FunctionalQuestionnaire,
    progress: ProgressQuestionnaire
};

/**
 * @desc    Get Advanced Assessment questions (Safety, Meds, Functional)
 * @route   GET /api/v1/clinical-assessments/:type/questions
 */
exports.getQuestions = (req, res) => {
    const { type } = req.params;
    const { age, gender, view } = req.query;

    const questionnaire = questionnaires[type];

    if (!questionnaire) {
        return res.status(404).json({
            code: 404,
            message: `Assessment type '${type}' not found`,
            data: null
        });
    }

    const filtered = filterQuestions(questionnaire, {
        age: age ? parseInt(age) : null,
        gender: gender || null,
        view: view || 'professional'
    });

    res.status(200).json({
        code: 200,
        message: `${type.charAt(0).toUpperCase() + type.slice(1)} questionnaire retrieved`,
        data: filtered
    });
};

/**
 * @desc    Submit Advanced Assessment (Safety, Meds, Functional)
 * @route   POST /api/v1/clinical-assessments/:type
 */
exports.submitAssessment = async (req, res) => {
    // Note: For now, we return 200 to acknowledge implementation. 
    // In a real scenario, this would save to a generic 'Assessment' or 'ClinicalRecord' model.
    res.status(200).json({
        code: 200,
        message: `${req.params.type} assessment submitted successfully (Mock Implementation)`,
        data: req.body
    });
};
