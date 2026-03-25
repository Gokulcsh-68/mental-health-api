const Assessment = require('../models/Assessment');
const Question = require('../models/Question');
const User = require('../models/User');
const StandardizedScore = require('../models/StandardizedScore');
const { sendSuccess, sendError } = require('../utils/responseHelper');
const logger = require('../config/logger');

// Helper to calculate age from DOB
const calculateAge = (dob) => {
    if (!dob) return 25;
    const diff = Date.now() - new Date(dob).getTime();
    const ageDate = new Date(diff);
    return Math.abs(ageDate.getUTCFullYear() - 1970);
};

/**
 * @desc    Get DSM-5 topic-based assessment questions for professionals
 * @route   GET /api/v1/professional-assessments/questions
 * @access  Private (Staff)
 */
exports.getQuestions = async (req, res, next) => {
    try {
        const { patientId } = req.query;
        if (!patientId) {
            return sendError(res, 400, 'Please provide a patientId');
        }

        // Security check: Patients can only fetch their own clinical questions
        if (req.user.role === 'patient' && req.user.userId !== parseInt(patientId)) {
            return sendError(res, 403, 'Not authorized to fetch clinical questions for another patient');
        }

        const targetUser = await User.findOne({ userId: parseInt(patientId) });
        if (!targetUser) {
            return sendError(res, 404, 'Patient not found');
        }

        const age = calculateAge(targetUser.dateOfBirth);
        const gender = targetUser.gender || 'all';

        const questions = await Question.find({
            isActive: true,
            minAge: { $lte: age },
            maxAge: { $gte: age },
            $or: [{ gender: gender }, { gender: 'all' }],
            assessmentType: 'professional'
        }).sort({ category: 1, questionId: 1 });

        // Group by category (DSM-5 Topics)
        const groupedQuestions = questions.reduce((acc, q) => {
            const category = q.category || 'Other';
            if (!acc[category]) acc[category] = [];
            acc[category].push({
                questionId: q.questionId,
                text: q.professionalText || q.text,
                type: q.type,
                options: q.options,
                uiType: q.uiType
            });
            return acc;
        }, {});

        sendSuccess(res, 200, 'Professional assessment questions fetched (DSM-5 Topics)', {
            patient: {
                userId: targetUser.userId,
                firstName: targetUser.firstName,
                age,
                gender
            },
            topics: groupedQuestions
        });
    } catch (err) {
        next(err);
    }
};

/**
 * @desc    Submit professional assessment for a patient
 * @route   POST /api/v1/professional-assessments/submit
 * @access  Private (Staff)
 */
exports.submitAssessment = async (req, res, next) => {
    try {
        const { responses, patientId, consultId, notes, category } = req.body;

        logger.info('Professional assessment submission started by: %s for patient: %s', req.user._id, patientId);
        logger.debug('Consult ID: %s, Category: %s, Responses: %j', consultId, category, responses);

        if (!patientId) {
            return sendError(res, 400, 'Please provide a patientId');
        }

        const targetUser = await User.findOne({ userId: parseInt(patientId) });
        if (!targetUser) {
            return sendError(res, 404, 'Patient not found');
        }

        const processedResponses = [];
        let totalScore = 0;
        let maxPossibleScore = 0;

        for (const resp of responses) {
            const question = await Question.findOne({ questionId: resp.questionId });
            if (!question) continue;

            const selectedOption = question.options.id(resp.optionId);
            if (!selectedOption) continue;

            const score = selectedOption.score;
            const questionMax = Math.max(...question.options.map(o => o.score), 0);

            totalScore += score;
            maxPossibleScore += questionMax;
            processedResponses.push({
                question: question._id,
                questionId: question.questionId,
                optionId: resp.optionId,
                score: score
            });
        }

        const assessment = await Assessment.create({
            user: targetUser._id,
            conductedBy: req.user._id,
            consult_id: consultId,
            assessment_type: 'professional',
            category: category || 'clinical_assessment',
            responses: processedResponses,
            totalScore,
            maxPossibleScore,
            isSelfAssessment: false,
            status: 'completed',
            notes
        });

        sendSuccess(res, 201, 'Professional assessment submitted successfully', assessment);

        logger.info('Professional assessment submitted successfully for patient %s. ID: %s', 
            patientId, assessment.assessmentId);
    } catch (err) {
        next(err);
    }
};

/**
 * @desc    Get assessment history for a specific patient
 * @route   GET /api/v1/professional-assessments/patient/:patientId
 * @access  Private (Staff)
 */
exports.getPatientHistory = async (req, res, next) => {
    try {
        const patient = await User.findOne({ userId: parseInt(req.params.patientId) });
        if (!patient) {
            return sendError(res, 404, 'Patient not found');
        }

        // Security check: Patients can only see their own clinical history
        if (req.user.role === 'patient' && req.user._id.toString() !== patient._id.toString()) {
            return sendError(res, 403, 'Not authorized to access this clinical history');
        }

        const assessments = await Assessment.find({ user: patient._id, assessment_type: 'professional' })
            .populate('responses.question')
            .sort({ createdAt: -1 });

        const mappedAssessments = assessments.map(assessment => {
            const assessmentObj = assessment.toObject({ transform: false });
            assessmentObj.responses = assessmentObj.responses.map(resp => {
                const question = resp.question;
                let questionText = 'Question not found';
                let answerText = 'Option not found';

                if (question && typeof question === 'object') {
                    questionText = question.professionalText || question.patientText || question.text;
                    const option = question.options.find(o => o._id.toString() === resp.optionId.toString());
                    if (option) {
                        answerText = option.text;
                    }
                }

                return {
                    ...resp,
                    question: (question && question._id) ? question._id : resp.question,
                    questionText,
                    answerText
                };
            });
            return assessmentObj;
        });

        sendSuccess(res, 200, `Assessment history for ${patient.firstName} fetched`, mappedAssessments);
    } catch (err) {
        next(err);
    }
};
