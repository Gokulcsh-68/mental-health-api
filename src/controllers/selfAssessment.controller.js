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
 * @desc    Get simple self-assessment questions for patients (Flat list)
 * @route   GET /api/v1/self-assessments/questions
 * @access  Private (Patient)
 */
exports.getQuestions = async (req, res, next) => {
    try {
        let targetUser = req.user;
        const { patientId } = req.query;

        if (patientId) {
            // Security check: Patients cannot override their own ID
            if (req.user.role === 'patient' && req.user.userId !== parseInt(patientId)) {
                return sendError(res, 403, 'Not authorized to fetch questions for another patient');
            }
            
            if (req.user.role !== 'patient') {
                targetUser = await User.findOne({ userId: parseInt(patientId) });
                if (!targetUser) {
                    return sendError(res, 404, 'Patient not found');
                }
            }
        }

        const age = calculateAge(targetUser.dateOfBirth);
        const gender = targetUser.gender || 'all';

        const questions = await Question.find({
            isActive: true,
            minAge: { $lte: age },
            maxAge: { $gte: age },
            $or: [{ gender: gender }, { gender: 'all' }],
            assessmentType: 'self'
        }).sort({ questionId: 1 });

        const mappedQuestions = questions.map(q => ({
            questionId: q.questionId,
            text: q.patientText || q.text,
            category: q.category,
            type: q.type,
            options: q.options,
            uiType: q.uiType
        }));

        sendSuccess(res, 200, 'Self-assessment questions fetched', {
            count: mappedQuestions.length,
            questions: mappedQuestions
        });
    } catch (err) {
        next(err);
    }
};

/**
 * @desc    Submit self-assessment
 * @route   POST /api/v1/self-assessments/submit
 * @access  Private (Patient)
 */
exports.submitAssessment = async (req, res, next) => {
    try {
        const { responses, notes } = req.body;

        logger.info('Self-assessment submission started for user: %s', req.user._id);
        logger.debug('Submission responses: %j', responses);

        if (!responses || !Array.isArray(responses)) {
            return sendError(res, 400, 'Please provide an array of responses');
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
            const questionMax = question.options.length > 0
                ? Math.max(...question.options.map(o => o.score), 0)
                : 0;

            totalScore += score;
            maxPossibleScore += questionMax;
            processedResponses.push({
                question: question._id,
                questionId: question.questionId,
                optionId: resp.optionId,
                score: score
            });
        }

        const percentage = maxPossibleScore > 0 ? (totalScore / maxPossibleScore) * 100 : 0;

        // If professionalRequestId (Number) is provided, find the document and mark it completed
        let profReqId = null;
        let assessmentCategory = 'general_wellness';
        if (req.body.professionalRequestId) {
            const ProfessionalRequest = require('../models/ProfessionalRequest');
            const request = await ProfessionalRequest.findOne({ requestId: parseInt(req.body.professionalRequestId) });
            if (request) {
                profReqId = request._id;
                assessmentCategory = request.category;
                
                request.status = 'completed';
                request.completedAt = Date.now();
                await request.save();
            }
        }

        const assessment = await Assessment.create({
            user: req.user._id,
            assessment_type: 'self',
            category: assessmentCategory,
            responses: processedResponses,
            totalScore,
            maxPossibleScore,
            percentage: Math.round(percentage * 100) / 100,
            isSelfAssessment: true,
            status: 'completed',
            notes,
            professionalRequestId: profReqId
        });

        sendSuccess(res, 201, 'Self-assessment submitted successfully', {
            assessmentId: assessment.assessmentId,
            totalScore,
            percentage: assessment.percentage
        });

        logger.info('Self-assessment submitted successfully. ID: %s, Score: %d, Percentage: %d%%', 
            assessment.assessmentId, totalScore, assessment.percentage);
    } catch (err) {
        next(err);
    }
};

/**
 * @desc    Get patient's self-assessment history
 * @route   GET /api/v1/self-assessments/history
 * @access  Private (Patient)
 */
exports.getHistory = async (req, res, next) => {
    try {
        let userId = req.user._id;

        // If staff provides patientId, fetch history for that patient
        const patientId = req.params.patientId || req.query.patientId;
        if (patientId) {
            // Security check: Patients can only see their own history
            if (req.user.role === 'patient') {
                const patient = await User.findOne({ userId: parseInt(patientId) });
                if (patient && patient._id.toString() !== req.user._id.toString()) {
                    return sendError(res, 403, 'Not authorized to access this self-assessment history');
                }
            }

            if (req.user.role !== 'patient') {
                const patient = await User.findOne({ userId: parseInt(patientId) });
                if (!patient) {
                    return sendError(res, 404, 'Patient not found');
                }
                userId = patient._id;
            }
        }

        const assessments = await Assessment.find({ 
            user: userId,
            isSelfAssessment: true 
        })
        .populate('responses.question')
        .sort({ createdAt: -1 });

        const mappedAssessments = assessments.map(assessment => {
            const assessmentObj = assessment.toObject({ transform: false });
            assessmentObj.responses = assessmentObj.responses.map(resp => {
                const question = resp.question;
                let questionText = 'Question not found';
                let answerText = 'Option not found';

                if (question && typeof question === 'object') {
                    questionText = question.patientText || question.text;
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

        sendSuccess(res, 200, 'Self-assessment history fetched', mappedAssessments);
    } catch (err) {
        next(err);
    }
};

/**
 * @desc    Get single self-assessment details
 * @route   GET /api/v1/self-assessments/:id
 * @access  Private (Patient/Staff)
 */
exports.getAssessmentById = async (req, res, next) => {
    try {
        const assessment = await Assessment.findById(req.params.id)
            .populate('responses.question')
            .populate('user', 'userId firstName lastName');

        if (!assessment || !assessment.isSelfAssessment) {
            return sendError(res, 404, 'Self-assessment not found');
        }

        // Security check: Patients can only see their own assessments
        if (req.user.role === 'patient' && assessment.user._id.toString() !== req.user._id.toString()) {
            return sendError(res, 403, 'Not authorized to access this assessment');
        }

        const assessmentObj = assessment.toObject({ transform: false });
        assessmentObj.responses = assessmentObj.responses.map(resp => {
            const question = resp.question;
            let questionText = 'Question not found';
            let answerText = 'Option not found';

            if (question && typeof question === 'object') {
                questionText = question.patientText || question.text;
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

        sendSuccess(res, 200, 'Self-assessment details fetched', assessmentObj);
    } catch (err) {
        if (err.kind === 'ObjectId') {
            return sendError(res, 400, 'Invalid assessment ID format');
        }
        next(err);
    }
};
