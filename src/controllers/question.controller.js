const Question = require('../models/Question');
const User = require('../models/User');
const { sendSuccess, sendError } = require('../utils/responseHelper');
const openAIService = require('../services/OpenAIService');
const ChildAssessment = require('../models/ChildAssessment');
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

// ---------------------------------------------------------------------------
// Child specific endpoints (age <= 12)
// ---------------------------------------------------------------------------

// Static list of child-friendly questions (subset)
const CHILD_QUESTIONS = [
    {
        "id": 1,
        "emoji": "😊",
        "category": "Emotional Awareness",
        "question": "How often do you feel happy at school?",
        "options": ["Always", "Sometimes", "Rarely", "Never"],
        "correct_index": 0,
        "feedback": "Feeling happy at school is a sign of good emotional health. If you rarely feel happy, it is okay to talk to a trusted adult."
    },
    {
        "id": 2,
        "emoji": "😟",
        "category": "Anxiety",
        "question": "Do you feel worried or nervous about things that might happen?",
        "options": ["Never", "Sometimes, and I talk about it", "Yes, and I keep it to myself", "All the time and it bothers me a lot"],
        "correct_index": 1,
        "feedback": "Feeling worried sometimes is normal. Talking about your worries to someone you trust helps make them feel smaller."
    },
    {
        "id": 3,
        "emoji": "😴",
        "category": "Sleep & Rest",
        "question": "How do you feel when you wake up in the morning?",
        "options": ["Rested and ready", "A little tired", "Very tired most days", "I do not want to get up at all"],
        "correct_index": 0,
        "feedback": "Waking up feeling rested means your body and brain got enough sleep. Children need 9 to 11 hours of sleep every night."
    },
    {
        "id": 4,
        "emoji": "👫",
        "category": "Social Skills",
        "question": "When you have a problem with a friend, what do you usually do?",
        "options": ["Talk to them calmly", "Ignore them completely", "Get angry and fight", "Tell a teacher or parent"],
        "correct_index": 0,
        "feedback": "Talking calmly to a friend about a problem is the healthiest way to handle disagreements and keep friendships strong."
    },
    {
        "id": 5,
        "emoji": "😢",
        "category": "Coping Skills",
        "question": "When something makes you very sad, what helps you the most?",
        "options": ["Talking to someone I trust", "Staying quiet and alone for days", "Pretending I am fine", "Getting angry at everyone"],
        "correct_index": 0,
        "feedback": "Opening up to a trusted person — a parent, teacher, or counselor — is the most healthy way to cope with sadness."
    },
    {
        "id": 6,
        "emoji": "🏠",
        "category": "Home Environment",
        "question": "How do you feel at home most of the time?",
        "options": ["Safe and loved", "Okay but sometimes worried", "Unsafe or scared", "Lonely most of the time"],
        "correct_index": 0,
        "feedback": "Feeling safe and loved at home is very important for a child's mental health. If you feel unsafe, always tell a trusted adult."
    },
    {
        "id": 7,
        "emoji": "📚",
        "category": "School Stress",
        "question": "How do you feel when you have a lot of schoolwork or exams?",
        "options": ["A little stressed but I manage", "Very stressed and I cannot cope", "I do not care at all", "I panic and cannot do anything"],
        "correct_index": 0,
        "feedback": "Feeling a little stressed about schoolwork is normal. Breaking work into small steps and asking for help makes it much easier."
    },
    {
        "id": 8,
        "emoji": "🤝",
        "category": "Self-Esteem",
        "question": "What do you think about yourself most of the time?",
        "options": ["I am good enough and I try my best", "I am not as good as others", "I am always wrong", "Nobody likes me"],
        "correct_index": 0,
        "feedback": "Believing in yourself and knowing you are good enough is a very important part of strong mental health."
    },
    {
        "id": 9,
        "emoji": "😠",
        "category": "Anger Management",
        "question": "When something makes you very angry, what do you do first?",
        "options": ["Take deep breaths and calm down", "Shout or hit immediately", "Cry and run away", "Break or throw things"],
        "correct_index": 0,
        "feedback": "Pausing and taking deep breaths before reacting helps your brain calm down and make better choices."
    },
    {
        "id": 10,
        "emoji": "🌟",
        "category": "Help Seeking",
        "question": "If you were feeling very low or unhappy for many days, what would you do?",
        "options": ["Tell a trusted adult right away", "Hide it so nobody worries", "Pretend to be happy", "Stop eating and sleeping"],
        "correct_index": 0,
        "feedback": "Always speak up if you feel low for many days. Asking for help is a sign of strength, not weakness. You are never alone."
    }

];

// @desc    Get child-friendly questions (age <=12)
// @route   GET /api/v1/questions/children
// @access  Private (Patient)
exports.getChildQuestions = async (req, res, next) => {
    try {
        // Assuming the authenticated user is a patient; enforce age check
        const age = calculateAge(req.user.dateOfBirth);
        if (age > 12) {
            return sendError(res, 403, 'Only patients age 12 or below can access child questions');
        }
        sendSuccess(res, 200, 'Child questions fetched', CHILD_QUESTIONS);
    } catch (err) {
        next(err);
    }
};

// @desc    Submit all child question answers and get AI-generated feedback
// @route   POST /api/v1/questions/children/answers
// @access  Private (All roles)
exports.submitChildAnswer = async (req, res, next) => {
    try {
        const { responses, patientId } = req.body; // expects { responses: [...], patientId?: "userId" }

        // Determine the target patient
        let targetPatient;
        if (req.user.role === 'patient') {
            targetPatient = req.user;
        } else {
            // Non-patient roles must provide a patientId
            if (!patientId) {
                return sendError(res, 400, 'patientId is required for non-patient roles');
            }
            targetPatient = await User.findOne({ userId: parseInt(patientId) });
            if (!targetPatient) {
                return sendError(res, 404, `Patient with userId ${patientId} not found`);
            }
            if (targetPatient.role !== 'patient') {
                return sendError(res, 400, `User with userId ${patientId} is not a patient`);
            }
        }

        const age = calculateAge(targetPatient.dateOfBirth);
        if (age > 12) {
            return sendError(res, 403, 'Only patients age 12 or below can submit child answers');
        }

        if (!responses || !Array.isArray(responses) || responses.length === 0) {
            return sendError(res, 400, 'responses array is required and must not be empty');
        }

        // Validate each response against CHILD_QUESTIONS
        const validatedResponses = [];
        for (const resp of responses) {
            if (!resp.questionId || resp.answer === undefined || resp.answer === null) {
                return sendError(res, 400, `Each response must have a questionId and an answer`);
            }
            const question = CHILD_QUESTIONS.find(q => q.id === resp.questionId);
            if (!question) {
                return sendError(res, 404, `Question with id ${resp.questionId} not found`);
            }
            validatedResponses.push({
                questionId: resp.questionId,
                answer: resp.answer
            });
        }

        // Build prompt for OpenAI with all answers
        const promptLines = validatedResponses.map(r => {
            const q = CHILD_QUESTIONS.find(cq => cq.id === r.questionId);
            return `Question: ${q.question}\nOptions: ${q.options.join(', ')}\nUser answered: ${r.answer}`;
        });
        const prompt = promptLines.join('\n\n') + '\n\nProvide a brief supportive overall response based on all the answers.';
        const aiResponse = await openAIService.chatWithPatient([{ role: 'user', content: prompt }]);

        // Save all answers in a single document with patient ID
        await ChildAssessment.create({
            patient: targetPatient._id,
            responses: validatedResponses
        });

        sendSuccess(res, 200, 'AI response generated', { responses: validatedResponses, aiResponse });
    } catch (err) {
        next(err);
    }
};

// @desc    Get child assessment history
// @route   GET /api/v1/questions/children/history
// @access  Private (All roles)
exports.getChildAssessmentHistory = async (req, res, next) => {
    try {
        const { patientId } = req.query;

        // Determine the target patient
        let targetPatient;
        if (req.user.role === 'patient') {
            targetPatient = req.user;
        } else {
            if (!patientId) {
                return sendError(res, 400, 'patientId query parameter is required for non-patient roles');
            }
            targetPatient = await User.findOne({ userId: parseInt(patientId) });
            if (!targetPatient) {
                return sendError(res, 404, `Patient with userId ${patientId} not found`);
            }
            if (targetPatient.role !== 'patient') {
                return sendError(res, 400, `User with userId ${patientId} is not a patient`);
            }
        }

        const assessments = await ChildAssessment.find({ patient: targetPatient._id })
            .sort({ createdAt: -1 });

        // Enrich responses with question text and feedback
        const enriched = assessments.map(a => {
            const obj = a.toObject();
            obj.responses = obj.responses.map(r => {
                const q = CHILD_QUESTIONS.find(cq => cq.id === r.questionId);
                return {
                    ...r,
                    question: q ? q.question : null,
                    emoji: q ? q.emoji : null,
                    category: q ? q.category : null,
                    options: q ? q.options : null,
                    feedback: q ? q.feedback : null
                };
            });
            return obj;
        });

        sendSuccess(res, 200, `Found ${enriched.length} assessment(s)`, {
            count: enriched.length,
            assessments: enriched
        });
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
