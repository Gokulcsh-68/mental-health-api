const Consult = require('../models/Consult');
const Invoice = require('../models/Invoice');
const User = require('../models/User');
const { sendSuccess } = require('../utils/responseHelper');
const TeleConsultApiService = require('../services/CureselectApis/TeleConsultApiService');
const logger = require('../config/logger');

const teleConsultService = new TeleConsultApiService();

/**
 * @desc    Get specialist dashboard summary
 * @route   GET /api/v1/dashboards/specialist
 * @access  Private (Specialist)
 */
exports.getSpecialistDashboard = async (req, res, next) => {
    try {
        const specialistId = req.user.userId;
        logger.info(`DEBUG SpecialistDashboard: Fetching for ID ${specialistId}`);

        // 1. Get Today's Sessions
        const today = new Date().toISOString().split('T')[0];
        let sessionRes;
        try {
            sessionRes = await teleConsultService.fetch({
                participant_ref_number: String(specialistId),
                scheduled_from_date: today,
                scheduled_to_date: today
            });
        } catch (error) {
            logger.error(`SpecialistDashboard Error fetching today's sessions: ${error.message}`);
            sessionRes = { data: { consults: [] } };
        }
        logger.info(`DEBUG SpecialistDashboard: todaySessions response code: ${sessionRes ? sessionRes.code : 'null'}`);
        const todaySessions = sessionRes.data?.consults || [];

        // 2. Get Revenue Stats
        const invoices = await Invoice.find({ specialist_id: specialistId, status: 'paid' });
        const totalRevenue = invoices.reduce((acc, curr) => acc + curr.total_amount, 0);

        // 3. Get Patient Count
        let allSessionsRes;
        try {
            allSessionsRes = await teleConsultService.fetch({
                participant_ref_number: String(specialistId)
            });
        } catch (error) {
            logger.error(`SpecialistDashboard Error fetching all sessions: ${error.message}`);
            allSessionsRes = { data: { consults: [] } };
        }
        logger.info(`DEBUG SpecialistDashboard: allSessions response code: ${allSessionsRes ? allSessionsRes.code : 'null'}`);
        const allSessions = allSessionsRes.data?.consults || [];

        const patientIds = new Set(allSessions.map(c => {
            const p = c.participants.find(part => part.participant_type?.code === 'patient' || part.role === 'subscriber');
            return p ? p.ref_number : null;
        }).filter(Boolean));
        logger.info(`DEBUG SpecialistDashboard: stats calculated: sessions=${allSessions.length}, patients=${patientIds.size}`);

        sendSuccess(res, 200, 'Specialist dashboard summary fetched', {
            todaySessions: {
                count: todaySessions.length,
                sessions: todaySessions
            },
            stats: {
                totalRevenue,
                activePatients: patientIds.size,
                totalSessions: allSessions.length
            }
        });
    } catch (err) {
        next(err);
    }
};

/**
 * @desc    Get patient dashboard summary
 * @route   GET /api/v1/dashboards/patient
 * @access  Private (Patient)
 */
exports.getPatientDashboard = async (req, res, next) => {
    try {
        const patientId = req.user.userId;
        const patientObjectId = req.user._id;

        const MSE = require('../models/MSE');
        const TreatmentStage = require('../models/TreatmentStage');
        const Assessment = require('../models/Assessment');
        const Recommendation = require('../models/Recommendation');
        const User = require('../models/User');
        const Master = require('../models/Master');

        // 1. Get Upcoming Session & Recent History from External Service
        const today = new Date().toISOString().split('T')[0];
        let upcomingRes;
        try {
            upcomingRes = await teleConsultService.fetch({
                participant_ref_number: String(patientId),
                scheduled_from_date: today
            });
        } catch (error) {
            logger.error(`PatientDashboard Error fetching consults: ${error.message}`);
            upcomingRes = { data: { consults: [] } };
        }

        let upcoming = null;
        let totalConsultations = 0;
        let recentHistory = [];

        if (upcomingRes.data && upcomingRes.data.consults) {
            upcoming = upcomingRes.data.consults
                .filter(c => new Date(c.scheduled_at) > new Date())
                .sort((a, b) => new Date(a.scheduled_at) - new Date(b.scheduled_at))[0];

            recentHistory = upcomingRes.data.consults
                .filter(c => new Date(c.scheduled_at) <= new Date())
                .sort((a, b) => new Date(b.scheduled_at) - new Date(a.scheduled_at))
                .slice(0, 5);

            totalConsultations = upcomingRes.data.consults.length;
        }

        // 2. Mood Trend (Last 5 MSE)
        const moodLogs = await MSE.find({ patient: patientObjectId })
            .sort({ createdAt: -1 })
            .limit(5)
            .select('mood.subjective color_code createdAt');

        const moodTrend = moodLogs.map(log => ({
            mood: log.mood?.subjective || 'Neutral',
            status: log.color_code,
            date: log.createdAt
        }));

        // 3. Treatment Progress & Assigned Clinician
        const currentStage = await TreatmentStage.findOne({
            patient: patientObjectId,
            status: { $in: ['in_progress', 'pending'] }
        }).sort({ order: 1 });

        const patientProfile = await User.findById(patientObjectId)
            .populate('reportingTo', 'name firstName lastName specialization experienceYears about');

        // 4. Recommendations with Guides
        const recommendations = await Recommendation.find({ isActive: true })
            .sort({ priority: -1, createdAt: -1 })
            .limit(3);

        const recommendationsWithGuides = await Promise.all(recommendations.map(async (r) => {
            const guideSlug = `${r.category}_guide`;
            const guide = await Master.findOne({ master_type_slug: 'portal_content', slug: guideSlug });
            return {
                id: r._id,
                category: r.category,
                text: r.text,
                action: r.actionLabel,
                guideContent: guide ? guide.attributes.body : null
            };
        }));

        // 5. Activity Stats
        const assessmentCount = await Assessment.countDocuments({ patient: patientObjectId });

        // 6. Profile Completeness Logic
        let completenessPercentage = 0;
        if (patientProfile) {
            const completenessFields = ['firstName', 'lastName', 'email', 'phone', 'gender', 'dateOfBirth', 'address'];
            const filledFields = completenessFields.filter(field => patientProfile[field] && patientProfile[field] !== '');
            completenessPercentage = Math.round((filledFields.length / completenessFields.length) * 100);
        }

        sendSuccess(res, 200, 'Patient dashboard summary fetched', {
            upcomingSession: upcoming || null,
            recentHistory,
            healthSnapshot: {
                current: moodTrend[0] || null,
                moodTrend: moodTrend
            },
            treatmentProgress: currentStage ? {
                stage: currentStage.stage,
                status: currentStage.status,
                order: currentStage.order
            } : null,
            clinician: patientProfile.reportingTo ? {
                id: patientProfile.reportingTo._id,
                name: patientProfile.reportingTo.name || `${patientProfile.reportingTo.firstName} ${patientProfile.reportingTo.lastName}`,
                specialization: patientProfile.reportingTo.specialization,
                experience: patientProfile.reportingTo.experienceYears
            } : null,
            recommendations: recommendationsWithGuides,
            stats: {
                totalConsultations,
                totalAssessments: assessmentCount,
                profileCompleteness: completenessPercentage
            }
        });
    } catch (err) {
        next(err);
    }
};

/**
 * @desc    Get Super Admin dashboard summary
 * @route   GET /api/v1/dashboards/super-admin
 * @access  Private (Super Admin)
 */
exports.getSuperAdminStats = async (req, res, next) => {
    try {
        // 1. User distribution by role
        const userStats = await User.aggregate([
            { $group: { _id: '$role', count: { $sum: 1 } } }
        ]);

        // 2. Consultation summary
        const totalConsults = await Consult.countDocuments();
        const activeConsults = await Consult.countDocuments({ status: 'in_progress' });

        // 3. Financial summary (Mock/Simple from Invoices)
        const revenueRes = await Invoice.aggregate([
            { $match: { status: 'paid' } },
            { $group: { _id: null, total: { $sum: '$total_amount' } } }
        ]);
        const totalRevenue = revenueRes[0]?.total || 0;

        // 4. Hospital & Specialist counts
        const hospitalCount = await User.countDocuments({ role: 'hospital' });
        const specialistCount = await User.countDocuments({
            role: { $in: ['psychiatrist', 'psychologist', 'nurse', 'social_worker', 'counselor'] }
        });

        sendSuccess(res, 200, 'Super Admin summary fetched successfully', {
            users: userStats,
            consultations: {
                total: totalConsults,
                active: activeConsults
            },
            financials: {
                totalRevenue
            },
            infrastructure: {
                hospitals: hospitalCount,
                specialists: specialistCount
            }
        });
    } catch (err) {
        next(err);
    }
};

/**
 * @desc    Get detailed patient statistics for specialists
 * @route   GET /api/v1/dashboards/specialist/patient-statistics
 * @access  Private (Specialist)
 */
exports.getPatientStatistics = async (req, res, next) => {
    try {
        const specialistId = req.user._id;

        const MSE = require('../models/MSE');
        const Assessment = require('../models/Assessment');
        const User = require('../models/User');

        // 1. Get all assigned patients
        const patients = await User.find({ reportingTo: specialistId, role: 'patient' });
        const patientObjectIds = patients.map(p => p._id);

        if (patientObjectIds.length === 0) {
            return sendSuccess(res, 200, 'No patients assigned to this specialist', {
                summary: { totalPatients: 0, activeAssessments: 0 },
                demographics: { gender: { male: 0, female: 0, other: 0 }, ageGroups: { '0-18': 0, '19-35': 0, '36-50': 0, '51+': 0 } },
                clinical: { moodDistribution: {} },
                engagement: { enrollmentTrend: [] },
                assessments: { total: 0, completed: 0, pending: 0 }
            });
        }

        // 2. Demographic Stats
        const demographics = {
            gender: { male: 0, female: 0, other: 0 },
            ageGroups: { '0-18': 0, '19-35': 0, '36-50': 0, '51+': 0 }
        };

        const now = new Date();
        patients.forEach(p => {
            // Gender
            if (p.gender) {
                const g = p.gender.toLowerCase();
                if (demographics.gender.hasOwnProperty(g)) {
                    demographics.gender[g]++;
                }
            }

            // Age
            if (p.dateOfBirth) {
                const age = now.getFullYear() - new Date(p.dateOfBirth).getFullYear();
                if (age <= 18) demographics.ageGroups['0-18']++;
                else if (age <= 35) demographics.ageGroups['19-35']++;
                else if (age <= 50) demographics.ageGroups['36-50']++;
                else demographics.ageGroups['51+']++;
            }
        });

        // 3. Clinical Snapshot (Latest Mood for each patient)
        const recentMSEs = await MSE.aggregate([
            { $match: { patient: { $in: patientObjectIds } } },
            { $sort: { createdAt: -1 } },
            { 
                $group: { 
                    _id: "$patient", 
                    latestMood: { $first: "$mood.subjective" },
                    color: { $first: "$color_code" }
                } 
            }
        ]);

        const moodDistribution = {};
        recentMSEs.forEach(m => {
            const mood = m.latestMood || 'Neutral';
            moodDistribution[mood] = (moodDistribution[mood] || 0) + 1;
        });

        // 4. Assessment Completion Stats
        const assessmentStats = await Assessment.aggregate([
            { $match: { patient: { $in: patientObjectIds } } },
            { $group: { _id: "$status", count: { $sum: 1 } } }
        ]);

        const assessments = { total: 0, completed: 0, pending: 0 };
        assessmentStats.forEach(s => {
            const count = s.count;
            assessments.total += count;
            if (s._id === 'completed') assessments.completed = count;
            else assessments.pending += count;
        });

        // 5. Enrollment Trend (New patients per month - last 6 months)
        const sixMonthsAgo = new Date();
        sixMonthsAgo.setMonth(sixMonthsAgo.getMonth() - 6);

        const enrollmentTrend = await User.aggregate([
            { 
                $match: { 
                    reportingTo: specialistId, 
                    role: 'patient',
                    createdAt: { $gte: sixMonthsAgo }
                } 
            },
            {
                $group: {
                    _id: { $dateToString: { format: "%Y-%m", date: "$createdAt" } },
                    count: { $sum: 1 }
                }
            },
            { $sort: { "_id": 1 } }
        ]);

        sendSuccess(res, 200, 'Patient statistics fetched successfully', {
            summary: {
                totalPatients: patients.length,
                pendingAssessments: assessments.pending
            },
            demographics,
            clinical: {
                moodDistribution
            },
            assessments,
            engagement: {
                enrollmentTrend: enrollmentTrend.map(t => ({ month: t._id, count: t.count }))
            }
        });
    } catch (err) {
        next(err);
    }
};

/**
 * @desc    Get patient's own progress and engagement statistics
 * @route   GET /api/v1/dashboards/patient/statistics
 * @access  Private (Patient)
 */
exports.getPatientOwnStatistics = async (req, res, next) => {
    try {
        const patientId = req.user._id;
        const patientRefNumber = req.user.userId;

        const MSE = require('../models/MSE');
        const Assessment = require('../models/Assessment');

        // 1. Mood Analytics (Last 30 days)
        const thirtyDaysAgo = new Date();
        thirtyDaysAgo.setDate(thirtyDaysAgo.getDate() - 30);

        const moodLogs = await MSE.find({
            patient: patientId,
            createdAt: { $gte: thirtyDaysAgo }
        }).sort({ createdAt: 1 });

        const moodDistribution = {};
        moodLogs.forEach(log => {
            const mood = log.mood.subjective || 'Neutral';
            moodDistribution[mood] = (moodDistribution[mood] || 0) + 1;
        });

        // 2. Activity Streak Calculation
        let currentStreak = 0;
        if (moodLogs.length > 0) {
            const uniqueLogDays = new Set(moodLogs.map(log => 
                new Date(log.createdAt).toISOString().split('T')[0]
            ));
            const sortedDays = Array.from(uniqueLogDays).sort().reverse();
            
            const today = new Date().toISOString().split('T')[0];
            const yesterday = new Date(Date.now() - 86400000).toISOString().split('T')[0];
            
            let checkDate = sortedDays[0] === today ? today : (sortedDays[0] === yesterday ? yesterday : null);
            
            if (checkDate) {
                for (let i = 0; i < sortedDays.length; i++) {
                    const d = new Date(checkDate);
                    d.setDate(d.getDate() - i);
                    const expectedStr = d.toISOString().split('T')[0];
                    
                    if (uniqueLogDays.has(expectedStr)) {
                        currentStreak++;
                    } else {
                        break;
                    }
                }
            }
        }

        // 3. Consultation Stats (Attended vs Missed)
        let consultations = { total: 0, attended: 0, upcoming: 0, cancelled: 0 };
        try {
            const consultResponse = await teleConsultService.fetch({ 
                participant_ref_number: [patientRefNumber.toString()]
            }, 100);
            
            if (consultResponse && consultResponse.data) {
                const allConsults = consultResponse.data.consults || [];
                consultations.total = allConsults.length;
                allConsults.forEach(c => {
                    const status = c.consult_current_status?.slug;
                    if (status === 'completed') consultations.attended++;
                    else if (status === 'cancelled') consultations.cancelled++;
                    else if (['scheduled', 'payment_pending', 'not_started'].includes(status)) consultations.upcoming++;
                });
            }
        } catch (apiErr) {
            logger.warn('Failed to fetch consult stats for patient: %s', apiErr.message);
        }

        // 4. Assessment Progress
        const assessmentStats = await Assessment.aggregate([
            { $match: { patient: patientId } },
            { $group: { _id: "$status", count: { $sum: 1 } } }
        ]);

        const assessments = { total: 0, completed: 0, pending: 0 };
        assessmentStats.forEach(s => {
            const count = s.count;
            assessments.total += count;
            if (s._id === 'completed') assessments.completed = count;
            else assessments.pending += count;
        });

        sendSuccess(res, 200, 'Your progress statistics fetched successfully', {
            activity: {
                currentStreak,
                totalMoodLogs: moodLogs.length
            },
            moodAnalytics: {
                period: '30_days',
                distribution: moodDistribution
            },
            consultations,
            assessments: {
                completionRate: assessments.total > 0 ? Math.round((assessments.completed / assessments.total) * 100) : 0,
                completed: assessments.completed,
                pending: assessments.pending
            }
        });
    } catch (err) {
        next(err);
    }
};
