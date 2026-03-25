const SpecialistSchedule = require('../models/SpecialistSchedule');
const User = require('../models/User');
const Consult = require('../models/Consult');
const scheduleService = require('../services/ScheduleService');
const { sendSuccess, sendError } = require('../utils/responseHelper');

// @desc    Get all schedule entries (availability/unavailability)
// @route   GET /api/v1/specialists/schedule
// @access  Private
exports.getSchedules = async (req, res, next) => {
    try {
        const {
            specialist_id,
            type,
            isRecurring,
            isActive
        } = req.query;

        const query = {};

        if (specialist_id) query.specialist_id = parseInt(specialist_id);
        if (type) query.type = type;
        if (isRecurring !== undefined) query.isRecurring = isRecurring === 'true';
        if (isActive !== undefined) query.isActive = isActive === 'true';

        const schedules = await SpecialistSchedule.find(query).sort({ specialist_id: 1, type: 1 });

        sendSuccess(res, 200, 'Schedules fetched successfully', {
            count: schedules.length,
            schedules
        });
    } catch (err) {
        next(err);
    }
};

// @desc    Get single schedule entry by ID
// @route   GET /api/v1/specialists/schedule/:id
// @access  Private
exports.getScheduleById = async (req, res, next) => {
    try {
        const scheduleId = parseInt(req.params.id);
        if (isNaN(scheduleId)) {
            return sendError(res, 400, 'Invalid schedule ID format. Expected a number.');
        }

        const schedule = await SpecialistSchedule.findOne({ scheduleId });

        if (!schedule) {
            return sendError(res, 404, 'Schedule entry not found');
        }

        sendSuccess(res, 200, 'Schedule entry fetched successfully', schedule);
    } catch (err) {
        next(err);
    }
};

// @desc    Create a schedule entry
// @route   POST /api/v1/specialists/schedule
// @access  Private
exports.createSchedule = async (req, res, next) => {
    try {
        const {
            specialist_id,
            type,
            dayOfWeek,
            specificDate,
            specificEndDate,
            startTime,
            endTime,
            isRecurring,
            description,
            slotDuration,
            bufferTime,
            maxAppointments,
            locationTypes,
            breaks,
            recurrenceRule
        } = req.body;

        if (!specialist_id || !type || !startTime || !endTime) {
            return sendError(res, 400, 'specialist_id, type, startTime, and endTime are required');
        }

        // Validate specialist exists
        const specialist = await User.findOne({ userId: parseInt(specialist_id) });
        if (!specialist) {
            return sendError(res, 404, `Specialist with userId ${specialist_id} not found`);
        }

        // Check for basic overlaps if type is 'availability'
        if (type === 'availability') {
            const overlapQuery = {
                specialist_id: parseInt(specialist_id),
                type: 'availability',
                isActive: true
            };

            if (isRecurring) {
                overlapQuery.isRecurring = true;
                overlapQuery.dayOfWeek = dayOfWeek;
            } else {
                overlapQuery.isRecurring = false;
                overlapQuery.specificDate = {
                    $gte: new Date(specificDate + 'T00:00:00.000Z'),
                    $lte: new Date(specificDate + 'T23:59:59.999Z')
                };
            }

            const existingAvailabilities = await SpecialistSchedule.find(overlapQuery);
            for (const existing of existingAvailabilities) {
                if (isOverlap(startTime, endTime, existing.startTime, existing.endTime)) {
                    return sendError(res, 400, `New availability ${startTime}-${endTime} overlaps with existing entry ${existing.startTime}-${existing.endTime}`);
                }
            }
        }

        // Validate breaks
        if (breaks && breaks.length > 0) {
            for (const b of breaks) {
                if (!isTimeInRange(b.startTime, startTime, endTime) || !isTimeInRange(b.endTime, startTime, endTime)) {
                    return sendError(res, 400, `Break ${b.startTime}-${b.endTime} must be within session hours ${startTime}-${endTime}`);
                }
                if (compareTime(b.startTime, b.endTime) >= 0) {
                    return sendError(res, 400, `Break start time ${b.startTime} must be before end time ${b.endTime}`);
                }
            }
        }

        const schedule = await SpecialistSchedule.create({
            specialist_id: parseInt(specialist_id),
            type,
            dayOfWeek,
            specificDate,
            specificEndDate,
            startTime,
            endTime,
            isRecurring,
            description,
            slotDuration,
            bufferTime,
            maxAppointments,
            locationTypes,
            breaks,
            recurrenceRule
        });

        sendSuccess(res, 201, 'Schedule entry created successfully', schedule);
    } catch (err) {
        next(err);
    }
};

// @desc    Get current user's schedules
// @route   GET /api/v1/specialists/schedule/me
// @access  Private
exports.getMySchedules = async (req, res, next) => {
    try {
        const specialistId = req.user.userId;
        const { type, isActive } = req.query;

        const query = { specialist_id: specialistId };
        if (type) query.type = type;
        if (isActive !== undefined) query.isActive = isActive === 'true';

        const schedules = await SpecialistSchedule.find(query).sort({ type: 1, dayOfWeek: 1, specificDate: 1 });

        sendSuccess(res, 200, 'Your schedules fetched successfully', {
            count: schedules.length,
            schedules
        });
    } catch (err) {
        next(err);
    }
};

// @desc    Create a schedule entry for current user
// @route   POST /api/v1/specialists/schedule/me
// @access  Private
exports.createMySchedule = async (req, res, next) => {
    req.body.specialist_id = req.user.userId;
    return exports.createSchedule(req, res, next);
};

// @desc    Upsert weekly recurring availability for current user
// @route   PUT /api/v1/specialists/schedule/me/weekly
// @access  Private (Professional roles only)
exports.upsertMyWeeklySchedule = async (req, res, next) => {
    try {
        const specialist_id = req.user.userId;
        const schedulesData = Array.isArray(req.body) ? req.body : [req.body];
        const results = [];

        for (const data of schedulesData) {
            const {
                dayOfWeek,
                startTime,
                endTime,
                description,
                slotDuration,
                bufferTime,
                maxAppointments,
                locationTypes,
                breaks,
                isActive
            } = data;

            if (dayOfWeek === undefined || !startTime || !endTime) {
                return sendError(res, 400, `dayOfWeek, startTime, and endTime are required for all entries. Failed at day: ${dayOfWeek}`);
            }

            const dayNum = parseInt(dayOfWeek);
            if (isNaN(dayNum) || dayNum < 0 || dayNum > 6) {
                return sendError(res, 400, `dayOfWeek must be a number between 0 (Sun) and 6 (Sat). Failed at: ${dayOfWeek}`);
            }

            // Validate breaks within session hours
            const validatedBreaks = breaks || [];
            if (validatedBreaks.length > 0) {
                for (const b of validatedBreaks) {
                    if (!isTimeInRange(b.startTime, startTime, endTime) || !isTimeInRange(b.endTime, startTime, endTime)) {
                        return sendError(res, 400, `Break ${b.startTime}-${b.endTime} must be within session hours ${startTime}-${endTime} for day ${dayNum}`);
                    }
                    if (compareTime(b.startTime, b.endTime) >= 0) {
                        return sendError(res, 400, `Break start time ${b.startTime} must be before end time ${b.endTime} for day ${dayNum}`);
                    }
                }
            }

            // Find existing recurring schedule for this day
            let schedule = await SpecialistSchedule.findOne({
                specialist_id,
                type: 'availability',
                isRecurring: true,
                dayOfWeek: dayNum
            });

            const updateData = {
                specialist_id,
                type: 'availability',
                dayOfWeek: dayNum,
                startTime,
                endTime,
                isRecurring: true,
                description,
                slotDuration: slotDuration || 30,
                bufferTime: bufferTime || 0,
                maxAppointments,
                locationTypes: locationTypes || ['virtual'],
                breaks: validatedBreaks,
                isActive: isActive !== undefined ? isActive : true
            };

            if (schedule) {
                // Update existing
                Object.assign(schedule, updateData);
                await schedule.save();
                results.push({ dayOfWeek: dayNum, status: 'updated', scheduleId: schedule.scheduleId });
            } else {
                // Create new
                schedule = await SpecialistSchedule.create(updateData);
                results.push({ dayOfWeek: dayNum, status: 'created', scheduleId: schedule.scheduleId });
            }
        }

        sendSuccess(res, results.length > 1 ? 200 : (results[0].status === 'created' ? 201 : 200), 
            `${results.length} weekly schedule(s) processed successfully`, results);
    } catch (err) {
        next(err);
    }
};

// @desc    Update a schedule entry
// @route   PUT /api/v1/specialists/schedule/:id
// @access  Private
exports.updateSchedule = async (req, res, next) => {
    try {
        const {
            dayOfWeek,
            specificDate,
            specificEndDate,
            startTime,
            endTime,
            isRecurring,
            description,
            isActive,
            slotDuration,
            bufferTime,
            maxAppointments,
            locationTypes,
            breaks,
            recurrenceRule
        } = req.body;

        const schedule = await SpecialistSchedule.findOne({ scheduleId: parseInt(req.params.id) });

        if (!schedule) {
            return sendError(res, 404, 'Schedule entry not found');
        }

        // Ownership check: If not admin, must be own schedule
        const adminRoles = ['admin', 'super_admin'];
        if (!adminRoles.includes(req.user.role) && schedule.specialist_id !== req.user.userId) {
            return sendError(res, 403, 'You are not authorized to update this schedule');
        }


        if (dayOfWeek !== undefined) schedule.dayOfWeek = dayOfWeek;
        if (specificDate !== undefined) schedule.specificDate = specificDate;
        if (specificEndDate !== undefined) schedule.specificEndDate = specificEndDate;
        if (startTime !== undefined) schedule.startTime = startTime;
        if (endTime !== undefined) schedule.endTime = endTime;
        if (isRecurring !== undefined) schedule.isRecurring = isRecurring;
        if (description !== undefined) schedule.description = description;
        if (isActive !== undefined) schedule.isActive = isActive;
        if (slotDuration !== undefined) schedule.slotDuration = slotDuration;
        if (bufferTime !== undefined) schedule.bufferTime = bufferTime;
        if (maxAppointments !== undefined) schedule.maxAppointments = maxAppointments;
        if (locationTypes !== undefined) schedule.locationTypes = locationTypes;
        if (breaks !== undefined) {
            // Validate breaks against (updated) session hours
            const sTime = startTime || schedule.startTime;
            const eTime = endTime || schedule.endTime;
            for (const b of breaks) {
                if (!isTimeInRange(b.startTime, sTime, eTime) || !isTimeInRange(b.endTime, sTime, eTime)) {
                    return sendError(res, 400, `Break ${b.startTime}-${b.endTime} must be within session hours ${sTime}-${eTime}`);
                }
            }
            schedule.breaks = breaks;
        }
        if (recurrenceRule !== undefined) schedule.recurrenceRule = recurrenceRule;

        await schedule.save();

        sendSuccess(res, 200, 'Schedule entry updated successfully', schedule);
    } catch (err) {
        next(err);
    }
};

// @desc    Get specialist directory with next available slot info
// @route   GET /api/v1/specialists/directory
// @access  Public/Private
exports.getSpecialistDirectory = async (req, res, next) => {
    try {
        const professionalRoles = ['psychiatrist', 'psychologist', 'nurse', 'social_worker', 'counselor'];
        const { role, search, date, time } = req.query;

        const query = { role: { $in: professionalRoles }, isActive: true };
        if (role) query.role = role;
        if (search) {
            query.$or = [
                { firstName: { $regex: search, $options: 'i' } },
                { lastName: { $regex: search, $options: 'i' } }
            ];
        }

        const specialists = await User.find(query)
            .select('userId firstName lastName role profileImage')
            .lean();

        // Use provided date/time or default search window
        const targetSearchDate = normalizeDate(date) || new Date().toISOString().split('T')[0];
        const targetTime = normalizeTime(time);
        
        const directory = [];

        for (const s of specialists) {
            const sId = s.userId;
            if (!sId) continue; // Skip if userId is somehow missing

            let availableForFilter = true;
            let nextSlot = null;

            if (targetTime) {
                // If specific time is requested, check if the specialist is available then
                const slots = await scheduleService.calculateSlots(sId, targetSearchDate, targetTime);
                const isAvailable = slots.length > 0 && slots[0].startTime === targetTime && slots[0].available;
                
                if (!isAvailable) {
                    availableForFilter = false;
                } else {
                    nextSlot = { date: targetSearchDate, ...slots[0] };
                }
            } else {
                // Find "next available" logic (default behavior)
                for (let i = 0; i < 7; i++) {
                    const d = new Date(targetSearchDate);
                    d.setDate(d.getDate() + i);
                    const dStr = d.toISOString().split('T')[0];

                    const slots = await scheduleService.calculateSlots(sId, dStr);
                    const availableSlot = slots.find(slot => slot.available);

                    if (availableSlot) {
                        nextSlot = { date: dStr, ...availableSlot };
                        break;
                    }
                }
            }

            if (availableForFilter) {
                directory.push({
                    ...s,
                    nextAvailableSlot: nextSlot
                });
            }
        }

        sendSuccess(res, 200, 'Specialist directory fetched successfully', directory);
    } catch (err) {
        next(err);
    }
};

// @desc    Get available slots for a specialist on a specific date or range
// @route   GET /api/v1/specialists/schedule/slots
// @access  Public/Private
exports.getAvailableSlots = async (req, res, next) => {
    try {
        const { specialist_id, role, date, startDate, endDate, startTime, endTime, available, time } = req.query;

        if (!specialist_id && !role) {
            return sendError(res, 400, 'specialist_id or role is required');
        }

        const isAvailableOnly = available === 'true';
        const results = {};

        // Find specialists if role is provided
        let targetSpecialistIds = [];
        if (specialist_id) {
            targetSpecialistIds = [specialist_id];
        } else {
            const specialists = await User.find({ role, isActive: true }).select('userId').lean();
            targetSpecialistIds = specialists.map(s => s.userId);
            if (targetSpecialistIds.length === 0) {
                return sendSuccess(res, 200, 'No specialists found for this role', { 
                    results: {}, 
                    role,
                    slots: [],
                    date: date || startDate
                });
            }
        }

        // Handle specific time point filter
        let filterStart = startTime;
        let filterEnd = endTime;

        if (time) {
            const normalizedTime = normalizeTime(time);
            if (!normalizedTime) {
                return sendError(res, 400, `Invalid time format: ${time}. Use HH:mm or HH.mm AM/PM`);
            }
            filterStart = normalizedTime;
            filterEnd = normalizedTime;
        }

        // Handle Range vs Single Date
        const datesToProcess = [];
        if (startDate && endDate) {
            let current = new Date(normalizeDate(startDate));
            const end = new Date(normalizeDate(endDate));
            while (current <= end) {
                datesToProcess.push(current.toISOString().split('T')[0]);
                current.setDate(current.getDate() + 1);
            }
        } else if (date) {
            const normalizedDate = normalizeDate(date);
            if (!normalizedDate) {
                return sendError(res, 400, `Invalid date format: ${date}. Use YYYY-MM-DD or DD-MM-YYYY`);
            }
            datesToProcess.push(normalizedDate);
        } else {
            return sendError(res, 400, 'Either date, or startDate and endDate are required');
        }

        for (const d of datesToProcess) {
            let pooledSlots = [];

            for (const sId of targetSpecialistIds) {
                // Get slots with time-window filtering
                // If point-in-time 'time' filter is used, we look for slots starting at that time
                let slots = await scheduleService.calculateSlots(sId, d, filterStart, time ? undefined : filterEnd);
                
                if (time) {
                    const normalizedTime = normalizeTime(time);
                    slots = slots.filter(s => s.startTime === normalizedTime);
                }
                
                if (isAvailableOnly) {
                    slots = slots.filter(s => s.available);
                }

                // Merge into pooledSlots
                for (const slot of slots) {
                    const existingIdx = pooledSlots.findIndex(p => p.startTime === slot.startTime && p.endTime === slot.endTime);
                    if (existingIdx !== -1) {
                        // If any specialist is available for this slot, it's available in the pool
                        if (slot.available) pooledSlots[existingIdx].available = true;
                    } else {
                        pooledSlots.push({ ...slot });
                    }
                }
            }
            
            results[d] = pooledSlots.sort((a, b) => compareTime(a.startTime, b.startTime));
        }

        const responseData = {
            results: datesToProcess.length > 1 ? results : undefined,
            date: datesToProcess.length === 1 ? datesToProcess[0] : undefined,
            slots: datesToProcess.length === 1 ? results[datesToProcess[0]] : undefined
        };

        if (specialist_id) responseData.specialist_id = parseInt(specialist_id);
        if (role) responseData.role = role;

        sendSuccess(res, 200, 'Available slots fetched successfully', responseData);
    } catch (err) {
        next(err);
    }
};

// --- Helpers ---
function compareTime(t1, t2) {
    const [h1, m1] = t1.split(':').map(Number);
    const [h2, m2] = t2.split(':').map(Number);
    if (h1 !== h2) return h1 - h2;
    return m1 - m2;
}

function isTimeInRange(t, start, end) {
    return compareTime(t, start) >= 0 && compareTime(t, end) <= 0;
}

function isOverlap(s1, e1, s2, e2) {
    return compareTime(s1, e2) < 0 && compareTime(e1, s2) > 0;
}

/**
 * Normalizes date from DD-MM-YYYY to YYYY-MM-DD
 */
function normalizeDate(dateStr) {
    if (!dateStr) return null;
    
    // Check for DD-MM-YYYY
    const dmyMatch = dateStr.match(/^(\d{1,2})-(\d{1,2})-(\d{4})$/);
    if (dmyMatch) {
        const [_, d, m, y] = dmyMatch;
        return `${y}-${m.padStart(2, '0')}-${d.padStart(2, '0')}`;
    }
    
    // Check for YYYY-MM-DD
    const ymdMatch = dateStr.match(/^(\d{4})-(\d{1,2})-(\d{1,2})$/);
    if (ymdMatch) return dateStr;
    
    return null;
}

/**
 * Normalizes time like 12.30pm or 12:30 PM to HH:mm
 */
function normalizeTime(timeStr) {
    if (!timeStr) return null;
    
    let time = timeStr.toLowerCase().replace(/\s/g, '');
    
    // Handle 12.30pm or 12:30pm
    const match = time.match(/^(\d{1,2})[:.](\d{2})(am|pm)?$/);
    if (!match) {
        // Handle just 12pm or 1pm
        const simpleMatch = time.match(/^(\d{1,2})(am|pm)?$/);
        if (simpleMatch) {
            let [_, h, ampm] = simpleMatch;
            let hour = parseInt(h);
            if (ampm === 'pm' && hour < 12) hour += 12;
            if (ampm === 'am' && hour === 12) hour = 0;
            return `${String(hour).padStart(2, '0')}:00`;
        }
        return null;
    }
    
    let [_, h, m, ampm] = match;
    let hour = parseInt(h);
    let minute = parseInt(m);
    
    if (ampm === 'pm' && hour < 12) hour += 12;
    if (ampm === 'am' && hour === 12) hour = 0;
    
    return `${String(hour).padStart(2, '0')}:${String(minute).padStart(2, '0')}`;
}


// @desc    Delete a schedule entry
// @route   DELETE /api/v1/specialists/schedule/:id
// @access  Private
exports.deleteSchedule = async (req, res, next) => {
    try {
        const schedule = await SpecialistSchedule.findOne({ scheduleId: parseInt(req.params.id) });

        if (!schedule) {
            return sendError(res, 404, 'Schedule entry not found');
        }

        // Ownership check: If not admin, must be own schedule
        const adminRoles = ['admin', 'super_admin'];
        if (!adminRoles.includes(req.user.role) && schedule.specialist_id !== req.user.userId) {
            return sendError(res, 403, 'You are not authorized to delete this schedule');
        }

        await SpecialistSchedule.deleteOne({ scheduleId: parseInt(req.params.id) });

        sendSuccess(res, 200, 'Schedule entry deleted successfully', null);
    } catch (err) {
        next(err);
    }
};
