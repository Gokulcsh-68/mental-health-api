const User = require('../models/User');
const Assessment = require('../models/Assessment');
const Consult = require('../models/Consult');
const logger = require('../config/logger');

/**
 * @desc    User-related Socket Handlers (Patient View, etc.)
 */
const userHandler = (io, socket) => {
    const user = socket.user;

    /**
     * @desc    Get comprehensive patient view (Profile + History)
     * @event   get_patient_view
     */
    socket.on('get_patient_view', async () => {
        try {
            logger.info(`Fetching patient-view via socket for user: ${user._id}`);
            
            if (user.role !== 'patient' && user.role !== 'super_admin') {
                return socket.emit('user_error', { message: 'Not authorized to access patient view' });
            }

            const patientObjectId = user._id;

            // 1. Get Basic Profile
            const profile = await User.findById(patientObjectId).select('firstName lastName email phone gender dateOfBirth profileImage address bloodGroup createdAt');

            if (!profile) {
                return socket.emit('user_error', { message: 'Patient profile not found' });
            }

            // 2. Get Professional History: Consultations
            const consultations = await Consult.find({
                'participants.ref_number': String(user.userId)
            }).sort({ scheduled_at: -1 });

            const mappedConsultations = consultations.map(c => {
                const provider = c.participants.find(p => p.role === 'publisher' || p.participant_info?.role === 'specialist');
                return {
                    id: c._id,
                    consultId: c.consultId,
                    date: c.scheduled_at,
                    type: c.consult_type,
                    reason: c.reason,
                    status: c.consult_current_status?.name || c.consult_status?.name,
                    provider: provider ? provider.participant_info?.name : 'Unknown Specialist',
                    hasPrescription: !!(c.clinical_record?.prescription?.medications?.length > 0)
                };
            });

            // 3. Get Professional History: Assessments
            const assessments = await Assessment.find({ user: patientObjectId }).sort({ createdAt: -1 });

            const mappedAssessments = assessments.map(a => ({
                id: a._id,
                assessmentId: a.assessmentId,
                category: a.category,
                wellnessAspect: a.wellnessAspect || a.category,
                isSelfAssessment: a.isSelfAssessment,
                recordedBy: a.isSelfAssessment ? 'Patient' : 'Specialist',
                date: a.createdAt,
                score: a.totalScore,
                percentage: a.percentage,
                interpretation: a.clinicalResults ? Array.from(a.clinicalResults.values())[0]?.interpretation : 'Completed'
            }));

            // 4. Emit Data
            socket.emit('patient_view_data', {
                profile,
                history: {
                    consultations: mappedConsultations,
                    assessments: mappedAssessments
                }
            });

        } catch (err) {
            logger.error('Socket Patient View Error: %s', err.message);
            socket.emit('user_error', { message: 'Failed to fetch patient view data' });
        }
    });
};

module.exports = userHandler;
