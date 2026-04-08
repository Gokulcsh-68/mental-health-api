const express = require('express');
const morgan = require('morgan');
const setupSecurity = require('./middleware/security');
const validateApiKey = require('./middleware/apiKey');
const errorHandler = require('./middleware/error');
const config = require('./config/config');

// Route imports
const healthRoutes = require('./routes/health.routes');
const authRoutes = require('./routes/auth.routes');
const userRoutes = require('./routes/user.routes');
const notificationRoutes = require('./routes/notification.routes');
const questionRoutes = require('./routes/question.routes');
const assessmentRoutes = require('./routes/assessment.routes');
const selfAssessmentRoutes = require('./routes/selfAssessment.routes');
const professionalAssessmentRoutes = require('./routes/professionalAssessment.routes');
const resourceRoutes = require('./routes/resource.routes');
const roleRoutes = require('./routes/role.routes');
const consultRoutes = require('./routes/consult.routes');
const apiAccessRoutes = require('./routes/apiAccess.routes');
const taxCodeRoutes = require('./routes/taxCode.routes');
const chargeCodeRoutes = require('./routes/chargeCode.routes');
const specialistScheduleRoutes = require('./routes/specialistSchedule.routes');
const specialistRoutes = require('./routes/specialist.routes');
const dashboardRoutes = require('./routes/dashboard.routes');
const treatmentRoutes = require('./routes/treatment.routes');
const chiefComplaintRoutes = require('./routes/chiefComplaint.routes');
const historyOfIllnessRoutes = require('./routes/historyOfIllness.routes');
const hpiRoutes = require('./routes/hpi.routes');
const rosRoutes = require('./routes/ros.routes');
const pastHistoryRoutes = require('./routes/pastHistory.routes');
const mseRoutes = require('./routes/mse.routes');
const clinicalSummaryRoutes = require('./routes/clinicalSummary.routes');
const advancedAssessmentRoutes = require('./routes/advancedAssessment.routes');
const professionalRequestRoutes = require('./routes/professionalRequest.routes');
const analyticsRoutes = require('./routes/analytics.routes');
const familyPortalRoutes = require('./routes/familyPortal.routes');
const aiRoutes = require('./routes/ai.routes');
const auditLogRoutes = require('./routes/auditLog.routes');
const portalRoutes = require('./routes/portal.routes');
const feedbackRoutes = require('./routes/feedback.routes');
const systemSettingRoutes = require('./routes/systemSetting.routes');
const scheduledJobRoutes = require('./routes/scheduledJob.routes');
const symptomRoutes = require('./routes/symptom.routes');

const app = express();

// Trust proxy for rate limiting behind load balancers
app.set('trust proxy', 1);

// Body parser — 50 MB to support long clinical narratives
app.use(express.json({ limit: '50mb' }));
app.use(express.urlencoded({ extended: true, limit: '50mb' }));

// Logging
const fs = require('fs');
const path = require('path');

// Ensure logs directory exists
const logDirectory = path.join(__dirname, '../logs');
fs.existsSync(logDirectory) || fs.mkdirSync(logDirectory);

// Create a write stream (in append mode)
const accessLogStream = fs.createWriteStream(path.join(logDirectory, 'access.log'), { flags: 'a' });

// Setup the logger
if (config.NODE_ENV === 'development') {
    app.use(morgan('dev'));
}
app.use(morgan('combined', { stream: accessLogStream }));

// Security Middleware
setupSecurity(app);

// Mount routers
const v1Router = express.Router();

// Apply API key validation to all v1 routes
v1Router.use(validateApiKey);

// Routes
v1Router.use('/health', healthRoutes);
v1Router.use('/auth', authRoutes);
v1Router.use('/users', userRoutes);
v1Router.use('/notifications', notificationRoutes);
v1Router.use('/questions', questionRoutes);
v1Router.use('/assessments', assessmentRoutes);
v1Router.use('/self-assessments', selfAssessmentRoutes);
v1Router.use('/professional-assessments', professionalAssessmentRoutes);
v1Router.use('/resource', resourceRoutes);
v1Router.use('/consults', consultRoutes);
v1Router.use('/roles', roleRoutes);
v1Router.use('/api-access', apiAccessRoutes);
v1Router.use('/tax-codes', taxCodeRoutes);
v1Router.use('/charge-codes', chargeCodeRoutes);
v1Router.use('/specialists/schedule', specialistScheduleRoutes);
v1Router.use('/specialists', specialistRoutes);
v1Router.use('/dashboard', dashboardRoutes);
v1Router.use('/dashboards', dashboardRoutes);
v1Router.use('/treatment', treatmentRoutes);
v1Router.use('/chief-complaints', chiefComplaintRoutes);
v1Router.use('/history-of-illness', historyOfIllnessRoutes);
v1Router.use('/hpis', hpiRoutes);
v1Router.use('/ros', rosRoutes);
v1Router.use('/past-history', pastHistoryRoutes);
v1Router.use('/mse', mseRoutes);
v1Router.use('/patients', clinicalSummaryRoutes);
v1Router.use('/clinical-assessments', advancedAssessmentRoutes);
v1Router.use('/professional-requests', professionalRequestRoutes);
v1Router.use('/analytics', analyticsRoutes);
v1Router.use('/family-portal', familyPortalRoutes);
v1Router.use('/ai', aiRoutes);
v1Router.use('/audit-logs', auditLogRoutes);
v1Router.use('/portal', portalRoutes);
v1Router.use('/feedback', feedbackRoutes);
v1Router.use('/system-settings', systemSettingRoutes);
v1Router.use('/scheduled-jobs', scheduledJobRoutes);
v1Router.use('/symptoms', symptomRoutes);

// Mounting both at root and /api/v1 for compatibility
app.use('/api/v1', v1Router);
app.use('/', v1Router);

// Global Error Handler
app.use(errorHandler);

module.exports = app;
