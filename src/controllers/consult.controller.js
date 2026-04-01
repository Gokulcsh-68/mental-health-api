const Consult = require('../models/Consult');
const User = require('../models/User');
const ChargeCode = require('../models/ChargeCode');
const TaxCode = require('../models/TaxCode');
const Invoice = require('../models/Invoice');
const scheduleService = require('../services/ScheduleService');
const { sendSuccess, sendPaginated, sendError } = require('../utils/responseHelper');
const TeleConsultApiService = require('../services/CureselectApis/TeleConsultApiService');
const logger = require('../config/logger');
const config = require('../config/config');
const openAIService = require('../services/OpenAIService');
const notificationService = require('../services/notificationService');

const teleConsultService = new TeleConsultApiService();

/**
 * @desc    Create a new consultation
 * @route   POST /api/v1/resource/consults
 * @access  Private
 */
exports.createConsult = async (req, res, next) => {
    try {
        const { scheduled_at, participants } = req.body || {};

        if (!scheduled_at || !participants) {
            return sendError(res, 400, 'scheduled_at and participants are required');
        }

        // 1. Identify Specialist
        const specialist = participants.find(p => p.participant_type.code === 'professional');
        if (!specialist) return sendError(res, 400, 'Specialist participant is required');

        const specialistId = parseInt(specialist.ref_number);
        const dateStr = new Date(scheduled_at).toISOString().split('T')[0];
        const timeStr = new Date(scheduled_at).toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit', hour12: false });

        // 2. Validate Slot Availability (Disabled as requested)
        /*
        const slots = await scheduleService.calculateSlots(specialistId, dateStr);
        const slot = slots.find(s => s.startTime === timeStr);
        
        if (!slot) {
            return sendError(res, 400, `No slot found at ${timeStr} for this specialist on ${dateStr}`);
        }
        if (!slot.available) {
            return sendError(res, 400, `Slot at ${timeStr} is not available: ${slot.reason}`);
        }
        */

        // 3. Prepare Payload for Remote Service
        const patientPart = participants.find(p => p.participant_type.code === 'patient');
        if (!patientPart) return sendError(res, 400, 'Patient participant is required');

        // Guard: specialist and patient cannot be the same person
        if (String(specialist.ref_number) === String(patientPart.ref_number)) {
            return sendError(res, 400, 'Professional and patient cannot have the same ref_number');
        }

        const [specialistUser, patientUser] = await Promise.all([
            User.findOne({ userId: specialistId }),
            User.findOne({ userId: parseInt(patientPart.ref_number) })
        ]);

        if (!specialistUser) return sendError(res, 400, `Professional with userId ${specialistId} not found`);
        if (!patientUser) return sendError(res, 400, `Patient with userId ${patientPart.ref_number} not found`);

        const consultAdditionalInfo = req.body.additional_info || {};
        consultAdditionalInfo.x_name = 'mental health'; // App identifier for service isolation

        if (req.body.payment) {
            consultAdditionalInfo.payment = {
                name: 'Consultation Charges',
                price: req.body.payment.amount,
                taxes: [],
            };
        }

        const remotePayload = {
            consult_date_time: scheduled_at,
            consult_reason: req.body.reason || 'General Consultation',
            consult_type: req.body.consult_type || 'virtual',
            provider: {
                id: specialistId,
                name: specialistUser?.name || specialist.participant_info?.name || specialist.name || 'Specialist',
                email: specialistUser?.email,
                phone: specialistUser?.phone,
                gender: specialistUser?.gender,
                additional_info: {
                    specialization: specialistUser?.specialization,
                    experience_years: specialistUser?.experienceYears,
                    about: specialistUser?.about,
                    x_name: 'mental health'
                }
            },
            patient: {
                id: parseInt(patientPart.ref_number),
                name: patientUser?.firstName ? `${patientUser.firstName} ${patientUser.lastName}` : (patientPart.participant_info?.name || 'Patient'),
                email: patientUser?.email,
                phone: patientUser?.phone,
                additional_info: {
                    x_name: 'mental health'
                }
            },
            service_provider: req.body.service_provider,
            additional_info: consultAdditionalInfo
        };

        /**
         * Patient Consult Creation
         * To send request to provider for approval if payment module is enabled
         */
        // if (process.env.IS_PAYMENT_ENABLED === 'true' && !req.body.payment) {
        //     remotePayload.consult_status = 'consult_approval_pending';
        // }

        const teleconsult_response = await teleConsultService.create(remotePayload);

        // 4. Save to local DB for slot validation and history
        await Consult.create({
            consult_code: `CONSULT-${teleconsult_response.consult_id}`,
            consult_id: teleconsult_response.consult_id,
            scheduled_at: new Date(scheduled_at),
            consult_type: remotePayload.consult_type,
            reason: remotePayload.consult_reason,
            participants: [
                {
                    ref_number: String(specialistId),
                    role: 'publisher',
                    participant_info: { name: remotePayload.provider.name }
                },
                {
                    ref_number: String(patientPart.ref_number),
                    role: 'subscriber',
                    participant_info: { name: remotePayload.patient.name }
                }
            ],
            hospital: specialistUser?.hospital || patientUser?.hospital || null,
            consult_status: { id: 1, name: 'Scheduled', slug: 'scheduled' }
        });

        // 5. Trigger Notifications (Non-blocking background process)
        const consultTime = new Date(scheduled_at).toLocaleString();
        
        // We fire-and-forget the notifications to keep the API response time low
        (async () => {
            try {
                await Promise.all([
                    // Notify Specialist
                    notificationService.notify({
                        userId: specialistUser._id,
                        title: 'New Consultation Booked',
                        message: `You have a new consultation with ${patientUser.firstName} ${patientUser.lastName} scheduled for ${consultTime}.`,
                        type: 'appointment',
                        createdBy: req.user._id,
                        data: { consult_id: teleconsult_response.consult_id }
                    }),
                    // Notify Patient
                    notificationService.notify({
                        userId: patientUser._id,
                        title: 'Consultation Scheduled',
                        message: `Your consultation with ${specialistUser.firstName} ${specialistUser.lastName} is scheduled for ${consultTime}.`,
                        type: 'appointment',
                        createdBy: req.user._id,
                        data: { consult_id: teleconsult_response.consult_id }
                    })
                ]);
            } catch (notifyErr) {
                logger.error(`Background notification failed: ${notifyErr.message}`);
            }
        })();

        sendSuccess(res, 201, 'Consultation booked successfully', {
            consult_id: teleconsult_response.consult_id
        });
    } catch (err) {
        next(err);
    }
};

// @desc    Get all consultations
// @route   GET /api/v1/resource/consults
// @access  Private
exports.getConsults = async (req, res, next) => {
    try {
        const {
            page = 1,
            limit = 10,
            from_date,
            to_date,
            consult_status,
            consult_id,
            participant_ref_number,
            sort_order = 'desc'
        } = req.query;

        // 1. Build Local Query (Source of Truth for "Mental Health")
        const query = {};

        // Handle Participants (Role-Based)
        let allowedRefNumbers = [];
        if (participant_ref_number) {
            allowedRefNumbers = Array.isArray(participant_ref_number) ? participant_ref_number : [participant_ref_number];
        } else if (req.user.role === 'hospital') {
            const specialists = await User.find({ hospital: req.user._id }).select('userId');
            allowedRefNumbers = specialists.map(s => String(s.userId));
            if (allowedRefNumbers.length === 0) {
                return sendPaginated(res, 200, 'No specialists found for this hospital', { consults: [] }, { page, limit, total: 0, totalPages: 0 });
            }
        } else {
            allowedRefNumbers = [String(req.user.userId)];
        }
        query['participants.ref_number'] = { $in: allowedRefNumbers };

        // Date Filtering
        if (from_date || to_date) {
            query.scheduled_at = {};
            if (from_date) query.scheduled_at.$gte = new Date(from_date);
            if (to_date) query.scheduled_at.$lte = new Date(new Date(to_date).setHours(23, 59, 59, 999));
        }

        // Status & ID
        if (consult_status) query['consult_status.slug'] = consult_status;
        if (consult_id) query.consult_id = parseInt(consult_id);

        // 2. Fetch from Local DB (for IDs and Total Count)
        const sortOptions = sort_order === 'asc' ? { scheduled_at: 1 } : { scheduled_at: -1 };
        const total = await Consult.countDocuments(query);
        const localConsults = await Consult.find(query)
            .sort(sortOptions)
            .skip((parseInt(page) - 1) * parseInt(limit))
            .limit(parseInt(limit));

        if (localConsults.length === 0) {
            return sendPaginated(res, 200, 'No consultations found in local records', { consults: [] }, {
                total, page: parseInt(page),
                limit: parseInt(limit),
                totalPages: Math.ceil(total / limit)
            });
        }

        // 3. Sync Logic: Identify stale records and trigger background sync
        const SYNC_THRESHOLD = 5 * 60 * 1000; // 5 minutes
        const now = new Date();

        localConsults.forEach(async (consult) => {
            const isStale = !consult.last_synced_at || (now - consult.last_synced_at > SYNC_THRESHOLD);

            if (isStale && consult.consult_id) {
                // Background update: Fire and forget
                (async () => {
                    try {
                        const res = await teleConsultService.fetchById(consult.consult_id);
                        const remoteData = res.data.consults;

                        const terminalStatuses = ['cancelled', 'completed', 'ended'];
                        const localStatus = consult.consult_status?.slug;

                        const updatePayload = {
                            ...remoteData,
                            last_synced_at: new Date()
                        };

                        if (terminalStatuses.includes(localStatus)) {
                            delete updatePayload.consult_status;
                            delete updatePayload.consult_current_status;
                            delete updatePayload.active;
                            delete updatePayload.participants;
                        }

                        await Consult.findByIdAndUpdate(consult._id, updatePayload);
                    } catch (err) {
                        logger.warn(`Background sync failed for ID ${consult.consult_id}: ${err.message}`);
                    }
                })();
            }
        });

        sendPaginated(res, 200, 'Consultations fetched successfully', {
            consults: localConsults,
        }, {
            total,
            page: parseInt(page),
            limit: parseInt(limit),
            totalPages: Math.ceil(total / limit)
        });
    } catch (err) {
        next(err);
    }
};

/**
 * @desc    Get single consultation by ID
 * @route   GET /api/v1/resource/consults/:id
 * @access  Private
 */
exports.getConsultById = async (req, res, next) => {
    try {
        if (req.params.id === 'undefined' || req.params.id === 'null') {
            return sendError(res, 400, 'Invalid consultId: string literal "undefined" or "null" provided');
        }
        
        // Robust parsing: extract numeric ID even if it has a prefix like "CONSULT-" or "CONS-"
        const consultIdMatch = String(req.params.id).match(/\d+/);
        const consultId = consultIdMatch ? parseInt(consultIdMatch[0]) : NaN;

        if (isNaN(consultId)) {
            return sendError(res, 400, `Invalid consultId: ${req.params.id}. Could not parse numeric ID.`);
        }

        // 1. Try Local Mirror First
        let consult = await Consult.findOne({ consult_id: consultId });

        const SYNC_THRESHOLD = 2 * 60 * 1000; // 2 minutes for single fetch
        const now = new Date();
        const isStale = !consult || !consult.last_synced_at || (now - consult.last_synced_at > SYNC_THRESHOLD);

        if (isStale) {
            // Background sync (Fire and forget) if we have local data
            const performSync = async () => {
                try {
                    const api_response = await teleConsultService.fetchById(req.params.id, req.query);
                    const remoteData = api_response.data.consults;

                    const terminalStatuses = ['cancelled', 'completed', 'ended'];
                    const localStatus = consult?.consult_status?.slug;

                    const updatePayload = {
                        ...remoteData,
                        consult_id: consultId,
                        last_synced_at: new Date()
                    };

                    if (consult && terminalStatuses.includes(localStatus)) {
                        delete updatePayload.consult_status;
                        delete updatePayload.consult_current_status;
                        delete updatePayload.active;
                        delete updatePayload.participants;
                    }

                    if (consult) {
                        await Consult.findByIdAndUpdate(consult._id, updatePayload);
                    } else {
                        await Consult.create(updatePayload);
                    }
                } catch (err) {
                    logger.warn(`Sync failed for consult ${consultId}: ${err.message}`);
                }
            };

            if (consult) {
                // Return local immediately, trigger sync in background
                performSync();
                return sendSuccess(res, 200, 'Consultation fetched successfully (Cached)', consult);
            } else {
                // If not in local, we MUST wait for the first fetch
                await performSync();
                consult = await Consult.findOne({ consult_id: consultId });
                if (!consult) {
                    return sendError(res, 404, 'Consultation not found');
                }
            }
        }

        sendSuccess(res, 200, 'Consultation fetched successfully', consult);
    } catch (err) {
        next(err);
    }
};

/**
 * @desc    Update consultation status (Patch)
 * @route   PATCH /api/v1/resource/consults/:id
 * @access  Private
 */
exports.patchConsult = async (req, res, next) => {
    try {
        const consultIdRaw = req.params.id;
        const consultIdMatch = String(consultIdRaw).match(/\d+/);
        const consultId = consultIdMatch ? parseInt(consultIdMatch[0]) : NaN;

        if (isNaN(consultId)) {
            return sendError(res, 400, `Invalid consultId: ${consultIdRaw}. Could not parse numeric ID.`);
        }

        const statusSlug = consult_status ? String(consult_status).toLowerCase().replace(/-/g, '_') : undefined;

        const teleconsult_response = await teleConsultService.patch({
            id: consultId,
            status: statusSlug,
            additional_info: (additional_info && Object.keys(additional_info).length > 0) ? additional_info : undefined
        });

        // Update local mirror for consistency
        const updateData = {};
        if (consult_status) {
            const statusSlug = consult_status.toLowerCase().replace('-', '_');
            const statusIdMap = {
                'scheduled': 1,
                'in_progress': 2,
                'confirmed': 3,
                'payment_pending': 4,
                'completed': 5,
                'cancelled': 6,
                'ended': 7
            };
            const statusId = statusIdMap[statusSlug] || 1;
            const statusName = consult_status.charAt(0).toUpperCase() + consult_status.slice(1).replace('_', ' ').replace('-', ' ');

            updateData.consult_status = { id: statusId, name: statusName, slug: statusSlug };
            updateData.consult_current_status = { id: statusId, name: statusName, slug: statusSlug };

            if (['completed', 'cancelled', 'ended'].includes(statusSlug)) {
                updateData.active = false;
                updateData.ended_at = new Date();
                updateData['participants.$[].participant_status'] = { id: statusId, name: statusName, slug: statusSlug };
            } else {
                updateData.active = true;
                updateData['participants.$[].participant_status'] = { id: statusId, name: statusName, slug: statusSlug };
            }
        }

        if (additional_info) {
            updateData.additional_info = additional_info;
        }

        if (Object.keys(updateData).length > 0) {
            updateData.last_synced_at = new Date();
            await Consult.findOneAndUpdate(
                { consult_id: parseInt(req.params.id) },
                updateData,
                { returnDocument: 'after' }
            );
        }

        // Send Notifications for status update
        if (consult_status) {
            const consult = await Consult.findOne({ consult_id: parseInt(req.params.id) });
            if (consult) {
                const [specialistRef, patientRef] = [
                    consult.participants.find(p => p.role === 'publisher'),
                    consult.participants.find(p => p.role === 'subscriber')
                ];

                const [specUser, patUser] = await Promise.all([
                    User.findOne({ userId: parseInt(specialistRef.ref_number) }),
                    User.findOne({ userId: parseInt(patientRef.ref_number) })
                ]);

                if (specUser && patUser) {
                    const statusMsg = `Consultation status updated to ${consult_status}`;
                    await Promise.all([
                        notificationService.notify({
                            userId: specUser._id,
                            title: 'Consultation Updated',
                            message: statusMsg,
                            type: 'appointment',
                            data: { consult_id: req.params.id }
                        }),
                        notificationService.notify({
                            userId: patUser._id,
                            title: 'Consultation Updated',
                            message: statusMsg,
                            type: 'appointment',
                            data: { consult_id: req.params.id }
                        })
                    ]).catch(e => logger.error(`Notification failure in patchConsult: ${e.message}`));
                } else {
                    logger.warn(`Could not find specUser or patUser for notifications in patchConsult: spec=${!!specUser}, pat=${!!patUser}`);
                }
            } else {
                logger.warn(`Consult participants not found for notifications in patchConsult: id=${req.params.id}`);
            }
        }

        sendSuccess(res, 200, 'Consultation updated successfully', {
            consult_id: teleconsult_response.consult_id
        });
    } catch (err) {
        next(err);
    }
};

/**
 * @desc    Token validation for consult details (Mock implementation)
 * @route   GET /api/v1/consults/token-validate
 * @access  Private
 */
exports.consultTokenValidate = async (req, res, next) => {
    try {
        const api_response = await teleConsultService.consultDetails(req.query);

        sendSuccess(res, 200, 'Token validated successfully', api_response.data);
    } catch (err) {
        next(err);
    }
};

/**
 * @desc    Reschedule consultation
 * @route   PATCH /api/v1/resource/consults/:id/reschedule
 * @access  Private
 */
exports.rescheduleConsult = async (req, res, next) => {
    try {
        const consultIdRaw = req.params.id;
        const { new_scheduled_at } = req.body || {};
        const consultIdMatch = String(consultIdRaw).match(/\d+/);
        const numericConsultId = consultIdMatch ? parseInt(consultIdMatch[0]) : NaN;

        if (isNaN(numericConsultId)) {
            return sendError(res, 400, `Invalid consultId: ${consultIdRaw}. Could not parse numeric ID.`);
        }

        if (!new_scheduled_at) return sendError(res, 400, 'new_scheduled_at is required');

        // 1. Fetch current consult to get specialist info
        const consultRes = await teleConsultService.fetchById(numericConsultId);
        const consultData = consultRes?.data?.consults;
        if (!consultData || !consultData.participants) {
            return sendError(res, 404, 'Consultation data or participants not found in remote service');
        }

        const specialist = consultData.participants.find(p => 
            (p.participant_type && p.participant_type.code === 'professional') || 
            (p.role === 'publisher')
        );

        if (!specialist || !specialist.ref_number) {
            return sendError(res, 400, 'Could not identify specialist for this consultation');
        }

        const specialistId = parseInt(specialist.ref_number);
        const dateStr = new Date(new_scheduled_at).toISOString().split('T')[0];
        const timeStr = new Date(new_scheduled_at).toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit', hour12: false });

        // 2. Validate New Slot (Disabled as requested)
        /*
        const slots = await scheduleService.calculateSlots(specialistId, dateStr);
        const slot = slots.find(s => s.startTime === timeStr);

        if (!slot || !slot.available) {
            return sendError(res, 400, `New slot ${timeStr} on ${dateStr} is not available`);
        }
        */

        // 3. Update remote service
        // Use a safe slug for status update
        const currentStatusSlug = consultData.consult_status?.slug || 'scheduled';
        logger.info(`Rescheduling consult ${numericConsultId} to ${new_scheduled_at} with status ${currentStatusSlug}`);
        
        await teleConsultService.patch({
            id: numericConsultId,
            status: currentStatusSlug,
            scheduled_at: new_scheduled_at
        });

        // 4. Update local mirror
        await Consult.findOneAndUpdate(
            { consult_id: numericConsultId },
            { scheduled_at: new Date(new_scheduled_at), last_synced_at: new Date() }
        );

        // Notify Reschedule
        const updatedConsult = await Consult.findOne({ consult_id: numericConsultId });
        if (updatedConsult) {
            const [specRef, patRef] = [
                updatedConsult.participants.find(p => p.role === 'publisher'),
                updatedConsult.participants.find(p => p.role === 'subscriber')
            ];

            if (specRef && patRef) {
                const [specUser, patUser] = await Promise.all([
                    User.findOne({ userId: parseInt(specRef.ref_number) }),
                    User.findOne({ userId: parseInt(patRef.ref_number) })
                ]);

                if (specUser && patUser) {
                    const newTime = new Date(new_scheduled_at).toLocaleString();
                    const msg = `Consultation has been rescheduled to ${newTime}`;
                    await Promise.all([
                        notificationService.notify({
                            userId: specUser._id,
                            title: 'Consultation Rescheduled',
                            message: msg,
                            type: 'appointment',
                            data: { consult_id: numericConsultId }
                        }),
                        notificationService.notify({
                            userId: patUser._id,
                            title: 'Consultation Rescheduled',
                            message: msg,
                            type: 'appointment',
                            data: { consult_id: numericConsultId }
                        })
                    ]).catch(e => logger.error(`Notification failure in rescheduleConsult: ${e.message}`));
                } else {
                    logger.warn(`Could not find specUser or patUser for reschedule: spec=${!!specUser}, pat=${!!patUser}`);
                }
            } else {
                logger.warn(`Could not find specRef or patRef for reschedule notif: id=${numericConsultId}`);
            }
        }

        sendSuccess(res, 200, 'Consultation rescheduled successfully', { consult_id: numericConsultId });
    } catch (err) {
        next(err);
    }
};

/**
 * @desc    Cancel consultation
 * @route   PATCH /api/v1/resource/consults/:id/cancel
 * @access  Private
 */
exports.cancelConsult = async (req, res, next) => {
    try {
        const consultIdRaw = req.params.id;
        const { reason } = req.body || {};

        if (consultIdRaw === 'undefined' || consultIdRaw === 'null') {
            return sendError(res, 400, 'Invalid consultId: string literal "undefined" or "null" provided');
        }

        const consultIdMatch = String(consultIdRaw).match(/\d+/);
        const consultId = consultIdMatch ? parseInt(consultIdMatch[0]) : NaN;

        if (isNaN(consultId)) {
            return sendError(res, 400, `Invalid consultId: ${consultIdRaw}. Could not parse numeric ID.`);
        }

        try {
            const patchPayload = {
                id: consultId,
                status: 'cancelled'
            };
            if (reason) {
                patchPayload.cancel_reason = reason;
            }
            await teleConsultService.patch(patchPayload);
        } catch (err) {
            logger.warn(`Remote cancellation failed for consult ${consultId}: ${err.message}`);
            // We continue to update the local mirror even if remote fails
        }

        // Update local mirror comprehensively
        const updateData = {
            consult_status: { id: 6, name: 'Cancelled', slug: 'cancelled' },
            consult_current_status: { id: 6, name: 'Cancelled', slug: 'cancelled' },
            active: false,
            ended_at: new Date(),
            'participants.$[].participant_status': { id: 6, name: 'Cancelled', slug: 'cancelled' },
            last_synced_at: new Date()
        };
        
        if (reason) {
            updateData.cancel_reason = reason;
        }

        await Consult.findOneAndUpdate(
            { consult_id: parseInt(consultId) },
            updateData
        );

        // Notify Cancellation
        const consult = await Consult.findOne({ consult_id: parseInt(consultId) });
        if (consult) {
            const [specRef, patRef] = [
                consult.participants.find(p => p.role === 'publisher'),
                consult.participants.find(p => p.role === 'subscriber')
            ];

            if (specRef && patRef) {
                const [specUser, patUser] = await Promise.all([
                    User.findOne({ userId: parseInt(specRef.ref_number) }),
                    User.findOne({ userId: parseInt(patRef.ref_number) })
                ]);

                if (specUser && patUser) {
                    const msg = `Consultation (ID: ${consultId}) has been cancelled.`;
                    await Promise.all([
                        notificationService.notify({
                            userId: specUser._id,
                            title: 'Consultation Cancelled',
                            message: msg,
                            type: 'appointment',
                            data: { consult_id: consultId }
                        }),
                        notificationService.notify({
                            userId: patUser._id,
                            title: 'Consultation Cancelled',
                            message: msg,
                            type: 'appointment',
                            data: { consult_id: consultId }
                        })
                    ]).catch(e => logger.error(`Notification failure in cancelConsult: ${e.message}`));
                } else {
                    logger.warn(`Users not found for cancel notification: spec=${!!specUser}, pat=${!!patUser}`);
                }
            } else {
                logger.warn(`Refs not found for cancel notification: id=${consultId}`);
            }
        }

        sendSuccess(res, 200, 'Consultation cancelled successfully');
    } catch (err) {
        next(err);
    }
};

/**
 * @desc    Add or update clinical/encounter documentation
 * @route   POST /api/v1/resource/consults/:id/notes
 * @access  Private (Specialist Only)
 */
exports.addClinicalNotes = async (req, res, next) => {
    try {
        const consultId = req.params.id;

        if (consultId === 'undefined' || consultId === 'null') {
            return sendError(res, 400, 'Invalid consultId: string literal "undefined" or "null" provided');
        }

        const { clinical_record, notes, summary, recommendations } = req.body || {};

        // Structured data to store in local DB
        const updateData = {};

        if (clinical_record) {
            updateData.clinical_record = clinical_record;
        }

        // Maintain backward compatibility for simple notes
        if (notes || summary || recommendations) {
            const legacyNotes = {
                notes,
                summary,
                recommendations,
                updated_at: new Date()
            };
            updateData['additional_info.clinical_notes'] = legacyNotes;
        }

        const consult = await Consult.findOneAndUpdate(
            { consult_code: `CONSULT-${consultId}` },
            { $set: updateData },
            { returnDocument: 'after', runValidators: true }
        );

        if (!consult) {
            return sendError(res, 404, 'Consultation not found');
        }

        // Sync with remote service if needed
        // For structured data, we send the clinical_record section to additional_info
        await teleConsultService.patch({
            id: consultId,
            additional_info: JSON.stringify({
                ...consult.additional_info,
                clinical_record: consult.clinical_record,
                clinical_notes: consult.additional_info?.clinical_notes
            })
        });

        // Notify Patient about clinical notes update
        const patRef = consult.participants.find(p => p.role === 'subscriber');
        const patUser = await User.findOne({ userId: parseInt(patRef.ref_number) });
        if (patUser) {
            await notificationService.notify({
                userId: patUser._id,
                title: 'Clinical Notes Updated',
                message: `Your clinical documentation for consultation ${consultId} has been updated by the specialist.`,
                type: 'general',
                data: { consult_id: consultId }
            });
        }

        sendSuccess(res, 200, 'Clinical documentation saved successfully', consult.clinical_record);
    } catch (err) {
        next(err);
    }
};

/**
 * @desc    Get clinical documentation for a consultation
 * @route   GET /api/v1/resource/consults/:id/clinical-record
 * @access  Private
 */
exports.getClinicalRecord = async (req, res, next) => {
    try {
        const consultId = req.params.id;
        const consult = await Consult.findOne({ consult_code: `CONSULT-${consultId}` });

        if (!consult) {
            return sendError(res, 404, 'Consultation not found');
        }

        sendSuccess(res, 200, 'Clinical record fetched successfully', {
            consult_id: consultId,
            clinical_record: consult.clinical_record,
            legacy_notes: consult.additional_info?.clinical_notes
        });
    } catch (err) {
        next(err);
    }
};

/**
 * @desc    Update chief complaints with AI extraction
 * @route   POST /api/v1/resource/consults/:id/chief-complaints
 * @access  Private (Specialist Only)
 */
exports.updateChiefComplaints = async (req, res, next) => {
    try {
        const consultId = req.params.id;

        if (consultId === 'undefined' || consultId === 'null') {
            return sendError(res, 400, 'Invalid consultId: string literal "undefined" or "null" provided');
        }

        const { narrative } = req.body || {};

        if (!narrative) {
            return sendError(res, 400, 'narrative is required for AI extraction');
        }

        // 1. Call OpenAI to extract structured info
        const ai = await openAIService.extractClinicalInfo(narrative);

        // 2. Map AI Data to Schema
        let onset_date = null;
        if (ai.onset_date) {
            const parsed = new Date(ai.onset_date);
            onset_date = isNaN(parsed.getTime()) ? null : parsed;
        }

        const complaintsData = {
            narrative: narrative,
            ai_summary: ai.ai_summary || null,
            structured: {
                duration: ai.duration || null,
                severity: ai.severity || null,
                onset_pattern: ai.onset_pattern || null,
                onset_date,
                triggers: ai.triggers || [],
                relieving_factors: ai.relieving_factors || [],
                aggravating_factors: ai.aggravating_factors || [],
                associated_symptoms: ai.associated_symptoms || [],
                affected_domains: ai.affected_domains || {},
                functional_impairment: ai.functional_impairment || null
            },
            risk_markers: {
                self_harm_detected: ai.risk_markers?.self_harm_detected ?? false,
                violence_detected: ai.risk_markers?.violence_detected ?? false,
                psychosis_detected: ai.risk_markers?.psychosis_detected ?? false,
                substance_use_detected: ai.risk_markers?.substance_use_detected ?? false,
                keywords_found: ai.risk_markers?.keywords_found || [],
                risk_level: ai.risk_markers?.risk_level || 'None'
            }
        };

        // 3. Save to local DB
        const consult = await Consult.findOneAndUpdate(
            { consult_code: `CONSULT-${consultId}` },
            { $set: { 'clinical_record.chief_complaints': complaintsData } },
            { returnDocument: 'after', runValidators: true }
        );

        if (!consult) {
            return sendError(res, 404, 'Consultation not found');
        }

        // 4. Sync with remote (Graceful failure)
        try {
            await teleConsultService.patch({
                id: consultId,
                additional_info: JSON.stringify({
                    ...consult.additional_info,
                    clinical_record: consult.clinical_record
                })
            });
        } catch (syncErr) {
            logger.error(`Remote sync failed for Chief Complaints (Consult ${consultId}): ${syncErr.message}`);
            // We continue as the local database update was successful
        }

        sendSuccess(res, 200, 'Chief complaints updated with AI extraction', consult.clinical_record.chief_complaints);
    } catch (err) {
        next(err);
    }
};

/**
 * @desc    Get chief complaints for a consultation
 * @route   GET /api/v1/resource/consults/:id/chief-complaints
 * @access  Private
 */
exports.getChiefComplaints = async (req, res, next) => {
    try {
        const consultId = req.params.id;
        const consult = await Consult.findOne({ consult_code: `CONSULT-${consultId}` });

        if (!consult || !consult.clinical_record?.chief_complaints) {
            return sendError(res, 404, 'Chief complaints not found for this consultation');
        }

        sendSuccess(res, 200, 'Chief complaints fetched successfully', consult.clinical_record.chief_complaints);
    } catch (err) {
        next(err);
    }
};

/**
 * @desc    Get billing preview for consultation
 * @route   GET /api/v1/resource/consults/:id/billing
 * @access  Private
 */
exports.getConsultBilling = async (req, res, next) => {
    try {
        const consultId = req.params.id;
        const consultRes = await teleConsultService.fetchById(consultId);
        logger.info(`DEBUG: consultRes keys: ${Object.keys(consultRes)}`);
        logger.info(`DEBUG: consultRes.data keys: ${consultRes.data ? Object.keys(consultRes.data) : 'null'}`);
        const consultData = consultRes.data.consults;
        logger.info(`DEBUG: consultData participants count: ${consultData.participants.length}`);
        if (consultData.participants.length > 0) {
            logger.info(`DEBUG: first participant keys: ${Object.keys(consultData.participants[0])}`);
            logger.info(`DEBUG: first participant type: ${JSON.stringify(consultData.participants[0].participant_type)}`);
        }

        const specialistRef = consultData.participants.find(p => p.participant_type?.code === 'professional' || p.role === 'publisher');
        const specialist = await User.findOne({ userId: parseInt(specialistRef.ref_number) });

        if (!specialist || !specialist.consultChargeCode) {
            return sendError(res, 404, 'Billing configuration (Charge Code) not found for this specialist');
        }

        const chargeCode = await ChargeCode.findOne({ chargeCodeId: specialist.consultChargeCode });
        if (!chargeCode) return sendError(res, 404, 'Charge code not found');

        let totalTax = 0;
        const taxDetails = [];

        if (chargeCode.tax_codes && chargeCode.tax_codes.length > 0) {
            const taxes = await TaxCode.find({ taxCodeId: { $in: chargeCode.tax_codes } });
            taxes.forEach(t => {
                const amount = (chargeCode.amount * t.rate) / 100;
                totalTax += amount;
                taxDetails.push({ name: t.name, rate: t.rate, amount });
            });
        }

        const totalAmount = chargeCode.amount + totalTax;

        sendSuccess(res, 200, 'Billing preview fetched', {
            consult_id: consultId,
            base_amount: chargeCode.amount,
            tax_amount: totalTax,
            total_amount: totalAmount,
            taxes: taxDetails,
            currency: 'INR'
        });
    } catch (err) {
        next(err);
    }
};

/**
 * @desc    Generate invoice for consultation
 * @route   POST /api/v1/resource/consults/:id/invoice
 * @access  Private
 */
exports.generateInvoice = async (req, res, next) => {
    try {
        const consultId = req.params.id;
        const consultRes = await teleConsultService.fetchById(consultId);
        const consultData = consultRes.data.consults;

        // 1. Get Billing Info
        const specialistRef = consultData.participants.find(p => p.participant_type?.code === 'professional' || p.role === 'publisher');
        const patientRef = consultData.participants.find(p => p.participant_type?.code === 'patient' || p.role === 'subscriber');
        const specialist = await User.findOne({ userId: parseInt(specialistRef.ref_number) });

        const chargeCode = await ChargeCode.findOne({ chargeCodeId: specialist.consultChargeCode });
        let totalTax = 0;
        if (chargeCode.tax_codes && chargeCode.tax_codes.length > 0) {
            const taxes = await TaxCode.find({ taxCodeId: { $in: chargeCode.tax_codes } });
            taxes.forEach(t => totalTax += (chargeCode.amount * t.rate) / 100);
        }

        // 2. Create Invoice Record
        const invoice = await Invoice.create({
            consult_id: parseInt(consultId),
            specialist_id: specialist.userId,
            patient_id: parseInt(patientRef.ref_number),
            base_amount: chargeCode.amount,
            tax_amount: totalTax,
            total_amount: chargeCode.amount + totalTax,
            status: 'unpaid'
        });

        sendSuccess(res, 201, 'Invoice generated successfully', invoice);
    } catch (err) {
        next(err);
    }
};

