# Frontend Integration Guide: Psychiatrist Booking Flow

This guide outlines the exact sequence of API calls required to implement a robust "Date -> Time -> Specialist" booking experience.

## Overview
1.  **Date Selection**: User picks a date.
2.  **Pooled Availability**: Fetch all available times across all specialists of a specific role.
3.  **Time Selection**: User picks a specific time slot.
4.  **Available Specialists**: Fetch only the specialists who are free at that exact time.

---

## Step 1: User Selects a Date
The user chooses a calendar date (e.g., `2026-03-18`).

## Step 2: Fetch Pooled Slots
Call the Slots API with the `role` and `available=true`. This "pools" everyone's availability so the user sees all possible times.

**Request:**
```http
GET /api/v1/specialists/schedule/slots?role=psychiatrist&date=2026-03-18&available=true
```

**Data Handling:**
- Use the `data.slots` array to populate your time-picker UI.
- These slots are sorted by `startTime`.
- If a slot is in this list, at least one psychiatrist is available.

---

## Step 3: User Selects a Time
The user picks a specific `startTime` (e.g., `11:30`).

## Step 4: Fetch Available Specialists
Call the Directory API with the `role`, `date`, and the selected `time`.

**Request:**
```http
GET /api/v1/specialists/schedule/directory?role=psychiatrist&date=2026-03-18&time=11:30
```

**Data Handling:**
- The `data` array will contain only the specialists available at that exact moment.
- Each specialist object includes `firstName`, `lastName`, `profileImage`, and their `userId`.
- Use this list to let the user select a specific professional to complete the booking.

---

## Example Implementation (React Native / Axios)

```javascript
// 1. Fetch all possible times for the day
const fetchTimes = async (date) => {
  const res = await api.get('/specialists/schedule/slots', {
    params: { role: 'psychiatrist', date, available: true }
  });
  setAvailableTimes(res.data.data.slots);
};

// 2. Once a time is picked, fetch who is free then
const fetchAvailableDoctors = async (date, time) => {
  const res = await api.get('/specialists/schedule/directory', {
    params: { role: 'psychiatrist', date, time }
  });
  setDoctors(res.data.data);
};
```

> [!TIP]
> This flow ensures a "frictionless" experience where users never select a time only to find no one is available.
