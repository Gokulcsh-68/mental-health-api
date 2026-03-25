const Question = require('../models/Question');
const User = require('../models/User');
const { sendSuccess, sendError } = require('../utils/responseHelper');

// Helper to calculate age from DOB
const calculateAge = (dob) => {
    if (!dob) return 25; // Default age if not provided
    const diff = Date.now() - new Date(dob).getTime();
    const ageDate = new Date(diff);
    return Math.abs(ageDate.getUTCFullYear() - 1970);
};

// @desc    Get questions for a user or a specific patient
// @route   GET /api/v1/questions/assessment
// @access  Private
exports.getAssessmentQuestions = async (req, res, next) => {
    try {
        const { patientId } = req.query;
        let targetUser = req.user;

        // Logic for Professionals vs Patients
        if (req.user.role === 'patient') {
            // If the user IS a patient, ignore patientId (or block if it's different)
            if (patientId && req.user.userId !== parseInt(patientId)) {
                return sendError(res, 403, 'Not authorized to fetch questions for another patient');
            }
            targetUser = req.user;
        } else {
            // Staff members MUST provide a patientId
            if (!patientId) {
                return sendError(res, 400, 'Staff members must provide a patientId query parameter (e.g., ?patientId=9)');
            }

            targetUser = await User.findOne({ userId: parseInt(patientId) });

            if (!targetUser) {
                return sendError(res, 404, `User with userId ${patientId} not found`);
            }

            // Enforce that assessment questions are only for patients
            if (targetUser.role !== 'patient') {
                return sendError(res, 400, `User with userId ${patientId} is not a patient. Assessments are only for patients.`);
            }
        }

        const age = calculateAge(targetUser.dateOfBirth);
        const gender = targetUser.gender || 'other';

        const filter = {
            isActive: true,
            minAge: { $lte: age },
            maxAge: { $gte: age },
            $or: [
                { gender: gender },
                { gender: 'all' }
            ]
        };

        // If requestId is provided, filter by the category in the request
        if (req.query.requestId) {
            const ProfessionalRequest = require('../models/ProfessionalRequest');
            const request = await ProfessionalRequest.findOne({ requestId: parseInt(req.query.requestId) });
            if (!request) {
                return sendError(res, 404, 'Professional request not found');
            }
            filter.category = request.category;
        } else if (req.query.category) {
            filter.category = req.query.category;
        }

        const questions = await Question.find(filter).sort({ category: 1, questionId: 1 });

        // Map questions to return the correct text variant
        const view = req.query.view || (req.user.role === 'patient' ? 'patient' : 'professional');
        
        const mappedQuestions = questions.map(q => {
            const questionObj = q.toObject();
            let displayText = questionObj.text; // Default

            if (view === 'professional' && questionObj.professionalText) {
                displayText = questionObj.professionalText;
            } else if (view === 'patient' && questionObj.patientText) {
                displayText = questionObj.patientText;
            }

            return {
                ...questionObj,
                text: displayText,
                originalText: questionObj.text // Keep for reference
            };
        });

        sendSuccess(res, 200, `Found ${mappedQuestions.length} questions for ${targetUser.firstName}'s profile`, {
            profile: {
                userId: targetUser.userId,
                firstName: targetUser.firstName,
                age,
                gender,
                view
            },
            count: mappedQuestions.length,
            questions: mappedQuestions
        });
    } catch (err) {
        next(err);
    }
};

// @desc    Create a new question
// @route   POST /api/v1/questions
// @access  Private (Admin only)
exports.createQuestion = async (req, res, next) => {
    try {
        const question = await Question.create({
            ...req.body,
            createdBy: req.user._id
        });

        sendSuccess(res, 201, 'Question created successfully', question);
    } catch (err) {
        next(err);
    }
};

// @desc    Get all questions (Admin list)
// @route   GET /api/v1/questions
// @access  Private (Admin only)
exports.getAllQuestions = async (req, res, next) => {
    try {
        const query = {};
        if (req.query.category) {
            query.category = req.query.category;
        }

        const questions = await Question.find(query).sort({ questionId: 1 });
        sendSuccess(res, 200, 'Questions fetched successfully', questions);
    } catch (err) {
        next(err);
    }
};

// @desc    Get organized self-assessment aspects for patients
// @route   GET /api/v1/questions/self-assessments
// @access  Private (Patient)
exports.getPatientSelfAssessments = async (req, res, next) => {
    try {
        const targetUser = req.user;
        const age = calculateAge(targetUser.dateOfBirth);
        const gender = targetUser.gender || 'all';

        // 1. Fetch all active questions that match the user's profile
        const questions = await Question.find({
            isActive: true,
            minAge: { $lte: age },
            maxAge: { $gte: age },
            $or: [{ gender: gender }, { gender: 'all' }]
        }).sort({ category: 1, questionId: 1 });

        // 2. Define the Wellness Aspects mapping
        const aspectMapping = {
            'Mood & Emotions': {
                categories: ['depression', 'mania'],
                description: 'Understand your emotional peaks and valleys.',
                icon: 'smile-beam',
                estimatedTime: '3-5 mins'
            },
            'Focus & Energy': {
                categories: ['adhd', 'mania'],
                description: 'Check your concentration and daily drive.',
                icon: 'bolt',
                estimatedTime: '4 mins'
            },
            'Fear & Worry': {
                categories: ['anxiety', 'ocd', 'ptsd_pediatric'],
                description: 'Manage stress, worries, and past experiences.',
                icon: 'shield-alt',
                estimatedTime: '5-7 mins'
            },
            'Sleep & Rest': {
                categories: ['sleep'],
                description: 'Evaluate your sleep quality and recovery.',
                icon: 'moon',
                estimatedTime: '2 mins'
            },
            'Reality & Perception': {
                categories: ['psychosis'],
                description: 'Check in with how you perceive the world.',
                icon: 'eye',
                estimatedTime: '3 mins'
            },
            'General Wellness': {
                categories: ['general'],
                description: 'Reflect on your overall mental health and well-being.',
                icon: 'heartbeat',
                estimatedTime: '3 mins'
            }
        };

        // 3. Group questions into these aspects
        const aspects = Object.entries(aspectMapping).map(([aspectName, config]) => {
            const aspectQuestions = questions.filter(q => config.categories.includes(q.category));
            
            // Only return aspects that have questions
            if (aspectQuestions.length === 0) return null;

            return {
                name: aspectName,
                description: config.description,
                icon: config.icon,
                estimatedTime: config.estimatedTime,
                questionCount: aspectQuestions.length,
                categories: config.categories, // original clinical categories
                questions: aspectQuestions.map(q => ({
                    questionId: q.questionId,
                    text: q.patientText || q.text,
                    category: q.category,
                    type: q.type,
                    options: q.options
                }))
            };
        }).filter(Boolean);

        sendSuccess(res, 200, 'Patient self-assessment aspects fetched', {
            aspects
        });
    } catch (err) {
        next(err);
    }
};
