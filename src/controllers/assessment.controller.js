const Assessment = require('../models/Assessment');
const Question = require('../models/Question');
const User = require('../models/User');
const StandardizedScore = require('../models/StandardizedScore');
const ProfessionalRequest = require('../models/ProfessionalRequest');
const { sendSuccess, sendError } = require('../utils/responseHelper');
const openAIService = require('../services/OpenAIService');
const { notify } = require('../services/notificationService');

/**
 * Helper to format assessment responses with full question and answer text
 */
const formatAssessmentResponse = (assessment) => {
    const assessmentObj = assessment.toObject ? assessment.toObject() : assessment;
    
    if (assessmentObj.responses) {
        assessmentObj.responses = assessmentObj.responses.map(resp => {
            const questionData = resp.question || {};
            let answerText = null;
            
            if (questionData.options) {
                const selectedOption = questionData.options.find(opt => 
                    opt._id && resp.optionId && opt._id.toString() === resp.optionId.toString()
                );
                if (selectedOption) {
                    answerText = selectedOption.text;
                }
            }

            return {
                questionId: resp.questionId || (questionData.questionId),
                questionText: questionData.text || 'Question text not found',
                optionId: resp.optionId,
                answerText: answerText || 'Answer not found',
                score: resp.score,
                category: questionData.category || assessmentObj.category
            };
        });
    }
    
    return assessmentObj;
};

// @desc    Submit a new assessment
// @route   POST /api/v1/assessments
// @access  Private
exports.submitAssessment = async (req, res, next) => {
    try {
        const { responses, patientId, date, time, notes, professionalRequestId } = req.body;
        const consult_id = req.body.consult_id || req.body.consultId;

        if (!responses || !Array.isArray(responses)) {
            return sendError(res, 400, 'Please provide an array of responses');
        }

        // Determine target user (Staff can submit for patients)
        let targetUser = req.user;
        if (patientId && req.user.role !== 'patient') {
            const patient = await User.findOne({ userId: parseInt(patientId) });
            if (!patient) {
                return sendError(res, 404, `Target patient with userId ${patientId} not found`);
            }
            targetUser = patient;
        }

        // 1. Fetch all questions once and group responses by category
        const { slug: explicitSlug } = req.body;
        const categoryData = {}; // { category: { processedResponses: [], totalScore: 0, maxPossible: 0 } }

        for (const resp of responses) {
            const question = await Question.findOne({ questionId: resp.questionId });
            if (!question) continue;

            // Find the selected option to get its score
            const selectedOption = question.options.id(resp.optionId);
            if (!selectedOption) {
                console.warn(`Option ${resp.optionId} not found for Question ${resp.questionId}`);
                continue;
            }

            const score = selectedOption.score;
            // Use explicitSlug if provided, otherwise fallback to question's category
            const cat = explicitSlug || question.category || 'general';

            if (!categoryData[cat]) {
                categoryData[cat] = {
                    processedResponses: [],
                    totalScore: 0,
                    maxPossibleScore: 0
                };
            }

            // Find max possible score for this question
            const questionMax = question.options.length > 0
                ? Math.max(...question.options.map(o => o.score), 0)
                : 0;

            categoryData[cat].totalScore += score;
            categoryData[cat].maxPossibleScore += questionMax;
            categoryData[cat].processedResponses.push({
                question: question._id,
                questionId: question.questionId,
                optionId: resp.optionId,
                score: score
            });
        }

        const createdAssessments = [];

        // 2. Process each category separately
        for (const [cat, data] of Object.entries(categoryData)) {
            // Find clinical result for this specific category and raw score
            const clinicalResults = new Map();
            const standardObj = await StandardizedScore.findOne({
                category: cat,
                rawScore: data.totalScore
            });

            if (standardObj) {
                clinicalResults.set(cat, {
                    rawScore: data.totalScore,
                    tScore: standardObj.tScore,
                    standardError: standardObj.standardError,
                    interpretation: standardObj.interpretation
                });
            }

            const percentage = data.maxPossibleScore > 0
                ? (data.totalScore / data.maxPossibleScore) * 100
                : 0;

            let profReqId = null;
            let assessmentCategory = req.body.category || 'Clinical Assessment';

            if (professionalRequestId) {
                const request = await ProfessionalRequest.findOne({
                    $or: [
                        { requestId: isNaN(professionalRequestId) ? null : parseInt(professionalRequestId) },
                        { _id: professionalRequestId.toString().length === 24 ? professionalRequestId : null }
                    ].filter(q => q !== null)
                });

                if (request) {
                    profReqId = request._id;
                    assessmentCategory = request.category;

                    // Update the request status
                    request.status = 'completed';
                    request.assessment = null; // Will be set after create
                    request.completedAt = Date.now();
                    await request.save();
                }
            }

            const assessment = await Assessment.create({
                user: targetUser._id, // Changed from patient._id to targetUser._id to match existing logic
                consult_id: consult_id ? parseInt(consult_id) : undefined,
                assessment_type: consult_id ? 'initial' : 'follow_up', // Kept original assessment_type logic
                category: assessmentCategory,
                responses: data.processedResponses,
                totalScore: data.totalScore,
                maxPossibleScore: data.maxPossibleScore,
                percentage: Math.round(percentage * 100) / 100,
                categoryBreakdown: new Map([[cat, data.totalScore]]),
                clinicalResults,
                wellnessAspect: req.body.wellnessAspect || req.body.aspect,
                isSelfAssessment: req.user.role === 'patient' && !consult_id,
                professionalRequestId: profReqId || undefined,
                date,
                time,
                notes,
                status: 'completed'
            });

            // Link the assessment back to the request if it exists
            if (profReqId) {
                await ProfessionalRequest.findByIdAndUpdate(profReqId, { assessment: assessment._id });
            }

            // Trigger AI encouragement (background)
            setImmediate(async () => {
                try {
                    const encouragement = await openAIService.generatePostAssessmentEncouragement(cat, data.totalScore);
                    await notify({
                        userId: req.user._id,
                        title: encouragement.title,
                        message: encouragement.message,
                        type: 'general',
                        imageUrl: encouragement.imageUrl
                    });
                } catch (err) {
                    console.error('AI encouragement trigger failed:', err.message);
                }
            });

            createdAssessments.push({
                _id: assessment._id,
                assessmentId: assessment.assessmentId,
                category: assessment.category
            });
        }

        if (createdAssessments.length === 0) {
            return sendError(res, 400, 'No valid questions/options found in submission');
        }

        sendSuccess(res, 201, `Successfully created ${createdAssessments.length} categorized assessment(s)`, {
            count: createdAssessments.length,
            assessments: createdAssessments
        });
    } catch (err) {
        next(err);
    }
};

// @desc    Get assessment history for the logged-in user (or a specific user for staff)
// @route   GET /api/v1/assessments
// @access  Private
// @query   page, limit, category, status, startDate, endDate, userId
exports.getMyAssessments = async (req, res, next) => {
    try {
        const {
            page = 1,
            limit = 10,
            category,
            status,
            startDate,
            endDate,
            userId
        } = req.query;

        const pageNum = Math.max(1, parseInt(page));
        const limitNum = Math.min(100, Math.max(1, parseInt(limit)));
        const skip = (pageNum - 1) * limitNum;

        // Resolve target user
        let targetUserId = req.user._id;

        if (userId && req.user.role !== 'patient') {
            const targetUser = await User.findOne({ userId: parseInt(userId) }).select('_id');
            if (!targetUser) {
                return sendError(res, 404, `User with userId ${userId} not found`);
            }
            targetUserId = targetUser._id;
        }

        // Build filter query
        const filter = { user: targetUserId };

        if (category) filter.category = category;
        if (status) filter.status = status;

        if (startDate || endDate) {
            filter.createdAt = {};
            if (startDate) filter.createdAt.$gte = new Date(startDate);
            if (endDate) filter.createdAt.$lte = new Date(new Date(endDate).setHours(23, 59, 59, 999));
        }

        const [assessments, total] = await Promise.all([
            Assessment.find(filter)
                .populate('responses.question', 'text category options questionId')
                .sort({ createdAt: -1 })
                .skip(skip)
                .limit(limitNum),
            Assessment.countDocuments(filter)
        ]);

        const formattedAssessments = assessments.map(formatAssessmentResponse);

        sendSuccess(res, 200, 'Assessment history fetched', {
            pagination: {
                total,
                page: pageNum,
                limit: limitNum,
                totalPages: Math.ceil(total / limitNum),
            },
            assessments: formattedAssessments
        });
    } catch (err) {

        next(err);
    }
};

// @desc    Get specific assessment details
// @route   GET /api/v1/assessments/:id
// @access  Private
exports.getAssessmentById = async (req, res, next) => {
    try {
        const assessment = await Assessment.findById(req.params.id)
            .populate('responses.question', 'text category options');

        if (!assessment) {
            return sendError(res, 404, 'Assessment not found');
        }

        // Security check: Only the owner or a professional can see it
        if (req.user.role === 'patient' && assessment.user.toString() !== req.user._id.toString()) {
            return sendError(res, 403, 'Not authorized to view this assessment');
        }

        const formattedAssessment = formatAssessmentResponse(assessment);

        sendSuccess(res, 200, 'Assessment details fetched', formattedAssessment);
    } catch (err) {
        next(err);
    }
};

// @desc    Get assessment history for a specific patient (for professionals)
// @route   GET /api/v1/assessments/patient/:patientId
// @access  Private (Professional)
exports.getPatientAssessments = async (req, res, next) => {
    try {
        const patient = await User.findOne({ userId: parseInt(req.params.patientId) });

        if (!patient) {
            return sendError(res, 404, 'Patient not found');
        }

        // Security check: Patients can only see their own assessments
        if (req.user.role === 'patient' && req.user._id.toString() !== patient._id.toString()) {
            return sendError(res, 403, 'Not authorized to access these assessments');
        }

        const assessments = await Assessment.find({ user: patient._id })
            .populate('responses.question', 'text category options questionId')
            .sort({ createdAt: -1 });

        const formattedAssessments = assessments.map(formatAssessmentResponse);

        sendSuccess(res, 200, `Assessment history for ${patient.firstName} fetched`, formattedAssessments);
    } catch (err) {
        next(err);
    }
};

// @desc    Get all assessments (Admin/Staff overview)
// @route   GET /api/v1/assessments/admin
// @access  Private (Staff)
exports.getAllAssessments = async (req, res, next) => {
    try {
        const assessments = await Assessment.find()
            .populate('user', 'firstName lastName email')
            .populate('responses.question', 'text category options questionId')
            .sort({ createdAt: -1 });

        const formattedAssessments = assessments.map(formatAssessmentResponse);

        sendSuccess(res, 200, 'All assessments fetched', formattedAssessments);
    } catch (err) {
        next(err);
    }
};

// @desc    Update assessment status or metadata
// @route   PATCH /api/v1/assessments/:id
// @access  Private (Staff/Owner)
exports.updateAssessment = async (req, res, next) => {
    try {
        let assessment = await Assessment.findById(req.params.id);

        if (!assessment) {
            return sendError(res, 404, 'Assessment not found');
        }

        // Security check
        if (req.user.role === 'patient') {
            if (assessment.user.toString() !== req.user._id.toString()) {
                return sendError(res, 403, 'Not authorized to update this assessment');
            }
            if (!assessment.isSelfAssessment) {
                return sendError(res, 403, 'Patients can only update their own self-assessments');
            }
        }

        assessment = await Assessment.findByIdAndUpdate(req.params.id, req.body, {
            new: true,
            runValidators: true
        });

        sendSuccess(res, 200, 'Assessment updated successfully', assessment);
    } catch (err) {
        next(err);
    }
};

// @desc    Delete an assessment
// @route   DELETE /api/v1/assessments/:id
// @access  Private (Staff/Owner)
exports.deleteAssessment = async (req, res, next) => {
    try {
        const assessment = await Assessment.findById(req.params.id);

        if (!assessment) {
            return sendError(res, 404, 'Assessment not found');
        }

        // Security check
        if (req.user.role === 'patient') {
            if (assessment.user.toString() !== req.user._id.toString()) {
                return sendError(res, 403, 'Not authorized to delete this assessment');
            }
            if (!assessment.isSelfAssessment) {
                return sendError(res, 403, 'Patients can only delete their own self-assessments');
            }
        }

        await assessment.deleteOne();

        sendSuccess(res, 200, 'Assessment deleted successfully', null);
    } catch (err) {
        next(err);
    }
};
