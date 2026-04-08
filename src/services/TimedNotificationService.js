const cron = require('node-cron');
const { notify } = require('./notificationService');
const User = require('../models/User');
const ScheduledJob = require('../models/ScheduledJob');
const openAIService = require('./OpenAIService');

/**
 * Service to handle scheduled notifications (Greetings, Reminders, etc.)
 */
class TimedNotificationService {
    constructor() {
        this.jobs = {};
        this.isProcessingRedFlags = false;
    }

    /**
     * Start all scheduled jobs
     */
    async init() {
        console.log('  🕒 Timed Notification Service Initializing...');

        // 1. Morning Greeting & Medication (Daily at 08:00 AM)
        this.jobs.morningProtocols = cron.schedule('0 8 * * *', () => {
            this.sendBulkGreeting('Good Morning! ☀️', 'Time for your morning medication and a positive start to your day.');
        });

        // 2. Noon Medication (Daily at 01:00 PM)
        this.jobs.noonMeds = cron.schedule('0 13 * * *', () => {
            this.sendToRole('patient', 'Medication Reminder 💊', 'It is time for your afternoon dosage. Please stay compliant with your plan.');
        });

        // 3. Mood Check-in (Daily at 06:00 PM)
        this.jobs.moodCheck = cron.schedule('0 18 * * *', () => {
            this.sendToRole('patient', 'Daily Mood Check-in 📊', 'How has your day been? Take a moment to log your mood in the Skyheal app.');
        });

        // 4. Night Medication (Daily at 09:00 PM)
        this.jobs.nightMeds = cron.schedule('0 21 * * *', () => {
            this.sendToRole('patient', 'Night Medication 🌙', 'Time for your evening dosage. Rest well and stay consistent.');
        });

        // 5. Appointment Reminders (Daily at 10:00 AM)
        this.jobs.appointmentReminders = cron.schedule('0 10 * * *', () => {
            this.sendAppointmentReminders();
        });

        // 6. Inactivity Check (Daily at 11:00 AM)
        this.jobs.inactivityCheck = cron.schedule('0 11 * * *', () => {
            this.checkPatientInactivity();
        });

        // 7. Psychiatrist Weekly Summary (Mondays at 09:00 AM)
        this.jobs.weeklySummary = cron.schedule('0 9 * * 1', () => {
            this.sendWeeklySummaries();
        });

        // 8. Red Flag Background Audit (Every 2 minutes)
        this.jobs.redFlagAudit = cron.schedule('*/2 * * * *', () => {
            this.processRedFlags();
        });

        // 9. Daily AI Engagement Tip (Daily at 04:00 PM)
        this.jobs.dailyAIEngagementTip = cron.schedule('0 16 * * *', () => {
            this.sendDailyAIEngagementTip();
        });

        // 10. Load Dynamic Custom Jobs from DB
        await this.loadCustomJobs();

        console.log('  ✅ All Comprehensive Timed Notification Jobs Scheduled');
    }

    /**
     * Load all active custom jobs from DB and register them
     */
    async loadCustomJobs() {
        try {
            const customJobs = await ScheduledJob.find({ isActive: true });
            console.log(`  📂 Loading ${customJobs.length} custom automation jobs...`);
            
            for (const job of customJobs) {
                this.registerCustomJob(job);
            }
        } catch (err) {
            console.error('❌ Error loading custom jobs:', err.message);
        }
    }

    /**
     * Map a dynamic job to the node-cron scheduler
     */
    registerCustomJob(job) {
        try {
            // Stop existing if already in memory
            if (this.jobs[job.name]) {
                this.jobs[job.name].stop();
            }

            this.jobs[job.name] = cron.schedule(job.cron, async () => {
                console.log(`🚀 [Automation] Executing ${job.name} (${job.actionType})...`);
                await this.executeAction(job);
                
                // Update last run timestamp
                await ScheduledJob.findByIdAndUpdate(job._id, { lastRunAt: new Date() });
            });

            console.log(`     ✅ Scheduled: ${job.name} [${job.cron}]`);
        } catch (err) {
            console.error(`❌ Failed to schedule job ${job.name}:`, err.message);
        }
    }

    /**
     * Execution dispatcher for custom jobs
     */
    async executeAction(job) {
        const { actionType, payload } = job;

        switch (actionType) {
            case 'NOTIFICATION_BROADCAST':
                await this.sendToRole(payload.role || 'all', payload.title, payload.message);
                break;
            case 'AI_TIP_BROADCAST':
                await this.sendDailyAIEngagementTip();
                break;
            case 'DB_CLEANUP':
                console.log('🧹 [Automation] DB Cleanup action triggered (Not yet implemented)');
                break;
            case 'RESEARCH_PULSE':
                console.log('🧬 [Automation] Research Pulse action triggered (Not yet implemented)');
                break;
            default:
                console.warn(`⚠️ Unknown action type: ${actionType}`);
        }
    }

    /**
     * Send notification to all active users of a specific role
     */
    async sendToRole(role, title, message) {
        try {
            const users = await User.find({ role, isActive: true });
            for (const user of users) {
                await notify({
                    userId: user._id,
                    title,
                    message,
                    type: 'general'
                });
            }
        } catch (err) {
            console.error(`❌ Error sending to role ${role}:`, err.message);
        }
    }

    /**
     * Send mapping to all active patients (alias for backward compatibility)
     */
    async sendBulkGreeting(title, message) {
        return this.sendToRole('patient', title, message);
    }

    /**
     * Logic for appointment reminders (Fetch Consults in next 24h)
     */
    async sendAppointmentReminders() {
        try {
            console.log('📅 [CRON] Running appointment reminders...');
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);

            const now = new Date();

            // Find virtual/home/clinic consults scheduled between now and tomorrow
            const Consult = require('../models/Consult');
            const upcomingAppointments = await Consult.find({
                scheduled_at: { $gte: now, $lte: tomorrow },
                active: true,
                'consult_status.slug': 'scheduled'
            });

            console.log(`   Found ${upcomingAppointments.length} upcoming appointments.`);

            for (const consult of upcomingAppointments) {
                // Find patient in participants
                const patientPart = consult.participants.find(p => p.role === 'subscriber'); // Usually patients are subscribers in this architecture
                if (patientPart && patientPart.ref_number) {
                    const patient = await User.findOne({ userId: parseInt(patientPart.ref_number) });
                    if (patient) {
                        await notify({
                            userId: patient._id,
                            title: 'Upcoming Appointment 📅',
                            message: `Reminder: You have a ${consult.consult_type} consultation scheduled for ${new Date(consult.scheduled_at).toLocaleTimeString()}.`,
                            type: 'reminder'
                        });
                    }
                }
            }
        } catch (err) {
            console.error('❌ Appointment reminder error:', err.message);
        }
    }

    /**
     * Logic for inactivity check (Users not synced for 3+ days)
     */
    async checkPatientInactivity() {
        try {
            console.log('🔍 [CRON] Running inactivity check...');
            const threeDaysAgo = new Date();
            threeDaysAgo.setDate(threeDaysAgo.getDate() - 3);

            const inactivePatients = await User.find({
                role: 'patient',
                isActive: true,
                $or: [
                    { last_synced_at: { $lt: threeDaysAgo } },
                    { last_synced_at: { $exists: false } }
                ]
            });

            console.log(`   Found ${inactivePatients.length} inactive patients.`);

            for (const patient of inactivePatients) {
                await notify({
                    userId: patient._id,
                    title: 'We miss you! 👋',
                    message: 'It has been a few days since your last check-in. Consistency is key to progress. Open Skyheal to update your vitals!',
                    type: 'general'
                });
            }
        } catch (err) {
            console.error('❌ Inactivity check error:', err.message);
        }
    }

    /**
     * Audit and process any missed or background Red Flag notifications
     */
    async processRedFlags() {
        try {
            if (this.isProcessingRedFlags) {
                console.log('🚩 [CRON] Red Flag Audit already in progress, skipping...');
                return;
            }
            this.isProcessingRedFlags = true;

            console.log('🚩 [CRON] Checking for unprocessed red flags...');
            const ChiefComplaint = require('../models/ChiefComplaint');
            const ROS = require('../models/ROS');
            const MSE = require('../models/MSE');
            const HPI = require('../models/HPI');
            const AlertService = require('./AlertService');

            const clinicalModels = [
                { model: ChiefComplaint, filter: { 'risk_markers.risk_level': 'High', redFlagNotified: false }, getFlags: (doc) => doc.risk_markers.keywords_found },
                { model: ROS, filter: { organic_red_flags: { $not: { $size: 0 } }, redFlagNotified: false }, getFlags: (doc) => doc.organic_red_flags },
                { model: MSE, filter: { redFlagNotified: false }, getFlags: (doc) => {
                    const flags = [];
                    if (doc.thought_content.suicidal_ideation !== 'None') flags.push(`Suicidal Ideation (${doc.thought_content.suicidal_ideation})`);
                    if (doc.thought_content.homicidal_ideation !== 'None') flags.push(`Homicidal Ideation (${doc.thought_content.homicidal_ideation})`);
                    if (doc.thought_content.delusions) flags.push('Delusions');
                    if (doc.perception.hallucinations) flags.push('Hallucinations');
                    return flags;
                }},
                { model: HPI, filter: { redFlagNotified: false }, getFlags: (doc) => {
                    const flags = [];
                    if (doc.structured.suicidal_ideation && doc.structured.suicidal_ideation !== 'None') flags.push(`Suicidal Ideation (${doc.structured.suicidal_ideation})`);
                    if (doc.severity_index > 80) flags.push(`High Severity (${doc.severity_index})`);
                    return flags;
                }}
            ];

            for (const config of clinicalModels) {
                const pending = await config.model.find(config.filter)
                    .limit(10) // Limit to 10 per cycle to prevent huge spikes
                    .populate('patient');
                for (const doc of pending) {
                    const flags = config.getFlags(doc);
                    if (flags.length > 0 && doc.patient && doc.patient.reportingTo) {
                        console.log(`   Internal Alert for ${doc.patient.firstName}: [${flags.join(', ')}]`);
                        await AlertService.triggerRedFlagAlert(
                            doc.patient.reportingTo,
                            { id: doc.patient._id, name: `${doc.patient.firstName} ${doc.patient.lastName}` },
                            flags
                        );
                    }
                    // Mark as notified regardless of patient state to avoid infinite loops
                    doc.redFlagNotified = true;
                    await doc.save();
                }
            }
            this.isProcessingRedFlags = false;
        } catch (err) {
            this.isProcessingRedFlags = false;
            console.error('❌ Red Flag Audit Error:', err.message);
        }
    }

    /**
     * Fetch a fresh AI mental health tip and broadcast to all patients
     */
    async sendDailyAIEngagementTip() {
        try {
            console.log('🤖 [CRON] Generating daily AI engagement tip...');
            const tip = await openAIService.generateEngagementTip();
            
            const activePatients = await User.find({ role: 'patient', isActive: true });
            console.log(`   Broadcasting AI tip to ${activePatients.length} patients.`);

            for (const patient of activePatients) {
                await notify({
                    userId: patient._id,
                    title: tip.title,
                    message: tip.message,
                    type: 'general',
                    imageUrl: tip.imageUrl
                });
            }
        } catch (err) {
            console.error('❌ Daily AI Tip error:', err.message);
        }
    }

    /**
     * Logic for weekly summaries to Psychiatrists
     */
    async sendWeeklySummaries() {
        try {
            console.log('📊 [CRON] Sending psychiatrist weekly summaries...');
            const psychiatrists = await User.find({ role: 'psychiatrist', isActive: true });

            for (const psych of psychiatrists) {
                // Find high-risk patients reporting to this psychiatrist
                const highRiskCount = await User.countDocuments({
                    reportingTo: psych._id,
                    role: 'patient',
                    'clinical_record.ai_inference.risk_stratification.level': { $in: ['High', 'Critical'] }
                });

                await notify({
                    userId: psych._id,
                    title: 'Weekly Practice Summary 📊',
                    message: `You have ${highRiskCount} patients currently categorized as High or Critical risk. Please review your dashboard for priority cases.`,
                    type: 'alert'
                });
            }
        } catch (err) {
            console.error('❌ Weekly summary error:', err.message);
        }
    }

    /**
     * Get status of all scheduled jobs
     */
    getJobStatus() {
        return Object.keys(this.jobs).map(name => {
            const job = this.jobs[name];
            return {
                name,
                // node-cron doesn't natively expose nextDate, so we'll just show status
                status: typeof job.getStatus === 'function' ? job.getStatus() : 'scheduled',
                isRunning: typeof job.isRunning === 'function' ? job.isRunning() : true
            };
        });
    }

    /**
     * Stop a specific job
     */
    stopJob(name) {
        if (this.jobs[name]) {
            this.jobs[name].stop();
            return true;
        }
        return false;
    }

    /**
     * Start/Resume a specific job
     */
    startJob(name) {
        if (this.jobs[name]) {
            this.jobs[name].start();
            return true;
        }
        return false;
    }
}

module.exports = new TimedNotificationService();
