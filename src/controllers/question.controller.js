const Question = require('../models/Question');
const User = require('../models/User');
const { sendSuccess, sendError } = require('../utils/responseHelper');
const openAIService = require('../services/OpenAIService');
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
        "question": "How do you feel when you smile?",
        "options": ["Happy", "Angry", "Sad", "Scared"],
        "correct_index": 0,
        "feedback": "Smiling usually means we feel happy inside!"
    },
    {
        "id": 2,
        "emoji": "😢",
        "question": "When you feel sad, what helps?",
        "options": ["Talk to someone you trust", "Stay alone all day", "Shout at others", "Ignore the feeling"],
        "correct_index": 0,
        "feedback": "Talking to a parent, teacher, or friend really helps when we feel sad."
    },
    {
        "id": 3,
        "emoji": "😤",
        "question": "When you feel angry, what should you do?",
        "options": ["Hit someone", "Take deep breaths", "Break things", "Run away"],
        "correct_index": 1,
        "feedback": "Taking deep breaths helps your body calm down when you feel angry."
    },
    {
        "id": 4,
        "emoji": "😨",
        "question": "Feeling scared before a test is...?",
        "options": ["Very normal", "Very bad", "Only for babies", "Wrong"],
        "correct_index": 0,
        "feedback": "Everyone feels nervous sometimes. It's totally normal!"
    },
    {
        "id": 5,
        "emoji": "😴",
        "question": "Why is sleep important?",
        "options": ["It helps your brain rest", "It is not important", "Only for small kids", "It makes you taller"],
        "correct_index": 0,
        "feedback": "Sleep helps your brain and body feel better and ready for the day."
    },
    {
        "id": 6,
        "emoji": "🏃",
        "question": "Playing and running makes you feel...?",
        "options": ["Worse", "Happier and healthier", "More tired only", "Bored"],
        "correct_index": 1,
        "feedback": "Exercise makes your brain release happy chemicals that improve your mood!"
    },
    {
        "id": 7,
        "emoji": "👨‍👩‍👧",
        "question": "Who should you tell if you feel unsafe?",
        "options": ["Nobody", "A trusted adult", "Only your friend", "Your pet"],
        "correct_index": 1,
        "feedback": "Always tell a trusted adult — a parent, teacher, or school counselor — if you feel unsafe."
    },
    {
        "id": 8,
        "emoji": "💛",
        "question": "Being kind to a sad friend is...?",
        "options": ["A waste of time", "Very helpful", "Not your job", "Weird"],
        "correct_index": 1,
        "feedback": "Small acts of kindness can mean the world to someone who is feeling down."
    },
    {
        "id": 9,
        "emoji": "🌈",
        "question": "Is it okay to feel many emotions in one day?",
        "options": ["Yes, it is normal", "No, pick one feeling", "Only on weekends", "Feelings are not real"],
        "correct_index": 0,
        "feedback": "Feeling happy, sad, excited, and nervous all in one day is completely normal for everyone!"
    },
    {
        "id": 10,
        "emoji": "🧠",
        "question": "Taking care of your mind means...?",
        "options": ["Hiding your feelings", "Talking, resting, and playing", "Eating only sweets", "Watching TV all day"],
        "correct_index": 1,
        "feedback": "Talking about feelings, resting well, and playing keeps your mind healthy and strong."
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

// @desc    Submit answer for a child question and get AI-generated feedback
// @route   POST /api/v1/questions/children/:id/answer
// @access  Private (Patient)
exports.submitChildAnswer = async (req, res, next) => {
    try {
        const age = calculateAge(req.user.dateOfBirth);
        if (age > 12) {
            return sendError(res, 403, 'Only patients age 12 or below can submit child answers');
        }
        const questionId = parseInt(req.params.id);
        const { answer } = req.body; // expects the selected option text or index
        const question = CHILD_QUESTIONS.find(q => q.id === questionId);
        if (!question) {
            return sendError(res, 404, 'Question not found');
        }
        // Build prompt for OpenAI
        const prompt = `Question: ${question.question}\nOptions: ${question.options.join(', ')}\nUser answered: ${answer}\nProvide a brief supportive response based on the answer.`;
        const aiResponse = await openAIService.chatWithPatient([{ role: 'user', content: prompt }]);
        sendSuccess(res, 200, 'AI response generated', { questionId, answer, aiResponse });
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
