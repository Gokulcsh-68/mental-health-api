const SpecialistSchedule = require('../models/SpecialistSchedule');
const Consult = require('../models/Consult');

class ScheduleService {
    /**
     * Calculate available slots for a specialist on a date.
     */
    async calculateSlots(specialistId, date, filterStartTime = null, filterEndTime = null) {
        const targetDate = new Date(date + 'T00:00:00.000Z');
        const dayOfWeek = targetDate.getUTCDay();

        // 1. Find all Availabilities for this date
        // Priority: Specific Date > Recurring
        let availabilities = await SpecialistSchedule.find({
            specialist_id: parseInt(specialistId),
            type: 'availability',
            isActive: true,
            specificDate: {
                $gte: new Date(date + 'T00:00:00.000Z'),
                $lte: new Date(date + 'T23:59:59.999Z')
            }
        });

        if (availabilities.length === 0) {
            availabilities = await SpecialistSchedule.find({
                specialist_id: parseInt(specialistId),
                type: 'availability',
                isActive: true,
                isRecurring: true,
                dayOfWeek: dayOfWeek
            });
        }

        if (availabilities.length === 0) return [];

        // 2. Find Unavailabilities
        const unavailabilities = await SpecialistSchedule.find({
            specialist_id: parseInt(specialistId),
            type: 'unavailability',
            isActive: true,
            $or: [
                {
                    specificDate: {
                        $gte: new Date(date + 'T00:00:00.000Z'),
                        $lte: new Date(date + 'T23:59:59.999Z')
                    }
                },
                {
                    specificDate: { $lte: new Date(date + 'T23:59:59.999Z') },
                    specificEndDate: { $gte: new Date(date + 'T00:00:00.000Z') }
                },
                {
                    isRecurring: true,
                    dayOfWeek: dayOfWeek
                }
            ]
        });

        // 3. Find Existing Consultations
        const startOfDay = new Date(date + 'T00:00:00.000Z');
        const endOfDay = new Date(date + 'T23:59:59.999Z');
        const consultations = await Consult.find({
            'participants.ref_number': String(specialistId),
            'consult_status.slug': { $ne: 'cancelled' },
            scheduled_at: { $gte: startOfDay, $lte: endOfDay }
        });

        let allSlots = [];
        // Current time and date check (anchored to Asia/Kolkata)
        const now = new Date();
        const nowTz = this.getTzDate(now);
        const isToday = date === nowTz.date;
        
        // 30-minute buffer logic (anchored to Asia/Kolkata)
        const bufferWindow = 30; // 30 minutes
        const nowWithBuffer = new Date(now.getTime() + bufferWindow * 60000);
        const bufferTz = this.getTzDate(nowWithBuffer);
        
        const bufferDateStr = bufferTz.date;
        const bufferTimeStr = bufferTz.time;

        // Process each availability block
        for (const availability of availabilities) {
            let currentStartTime = availability.startTime;
            const endTime = availability.endTime;
            const duration = availability.slotDuration || 30;
            const buffer = availability.bufferTime || 0;

            while (this.compareTime(this.addMinutes(currentStartTime, duration), endTime) <= 0) {
                const slotEndTime = this.addMinutes(currentStartTime, duration);

                // Filter by requested time window
                let outOfWindow = false;
                if (filterStartTime && this.compareTime(currentStartTime, filterStartTime) < 0) outOfWindow = true;
                if (filterEndTime && this.compareTime(slotEndTime, filterEndTime) > 0) outOfWindow = true;

                if (!outOfWindow) {
                    let available = true;
                    let reason = null;

                    // Past check including 30-minute buffer
                    if (isToday) {
                        // If the buffer window pushes us into the next day, all slots today are "past"
                        if (date !== bufferDateStr) {
                            available = false;
                            reason = 'past';
                        } else if (this.compareTime(currentStartTime, bufferTimeStr) < 0) {
                            available = false;
                            reason = 'past';
                        }
                    }

                    // Break check
                    if (available && availability.breaks) {
                        for (const b of availability.breaks) {
                            if (this.isOverlap(currentStartTime, slotEndTime, b.startTime, b.endTime)) {
                                available = false;
                                reason = 'break';
                                break;
                            }
                        }
                    }

                    // Unavailability check
                    if (available) {
                        for (const u of unavailabilities) {
                            if (this.isOverlap(currentStartTime, slotEndTime, u.startTime, u.endTime)) {
                                available = false;
                                reason = 'blocked';
                                break;
                            }
                        }
                    }

                    // Consultation check
                    if (available) {
                        for (const c of consultations) {
                            const cStartTime = this.formatTime(c.scheduled_at);
                            const cDuration = c.duration || 30;
                            const cEndTime = this.addMinutes(cStartTime, cDuration);
                            if (this.isOverlap(currentStartTime, slotEndTime, cStartTime, cEndTime)) {
                                available = false;
                                reason = 'booked';
                                break;
                            }
                        }
                    }

                    allSlots.push({ startTime: currentStartTime, endTime: slotEndTime, available, reason });
                }
                currentStartTime = this.addMinutes(slotEndTime, buffer);
            }
        }

        return allSlots.sort((a, b) => this.compareTime(a.startTime, b.startTime));
    }

    // --- Helpers ---
    addMinutes(timeStr, minutes) {
        const [h, m] = timeStr.split(':').map(Number);
        const date = new Date();
        date.setHours(h, m + minutes, 0, 0);
        return this.formatTime(date);
    }

    formatTime(date) {
        return new Intl.DateTimeFormat('en-GB', {
            timeZone: 'Asia/Kolkata',
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        }).format(date);
    }

    getTzDate(date, timeZone = 'Asia/Kolkata') {
        const parts = new Intl.DateTimeFormat('en-GB', {
            timeZone,
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        }).formatToParts(date);

        const map = {};
        parts.forEach(p => map[p.type] = p.value);
        return {
            date: `${map.year}-${map.month}-${map.day}`,
            time: `${map.hour}:${map.minute}`
        };
    }

    isOverlap(s1, e1, s2, e2) {
        return this.compareTime(s1, e2) < 0 && this.compareTime(e1, s2) > 0;
    }

    compareTime(t1, t2) {
        const [h1, m1] = t1.split(':').map(Number);
        const [h2, m2] = t2.split(':').map(Number);
        if (h1 !== h2) return h1 - h2;
        return m1 - m2;
    }
}

module.exports = new ScheduleService();
