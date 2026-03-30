# 📊 Patient Statistics API

The Patient Statistics API provides granular clinical and engagement metrics for both individual patients (self-monitoring) and clinical specialists (population management).

---

## 🏗️ Statistics Pathways

1. **Patient Own Stats**: Personal progress tracking (Mood trends, Activity streaks, Consult stats).
2. **Specialist - Patient Analytics**: Aggregate clinical and demographic insights for all assigned patients.
3. **Admin Statistics**: Infrastructure and high-level platform health metrics.

---

## 🏠 1. Patient: Personal Progress Statistics

Retrieve a detailed breakdown of your own clinical engagement and health trends.

- **URL**: `/api/v1/dashboard/patient/statistics`
- **Method**: `GET`
- **Access**: Private (Patient Only).
- **Key Metrics Returned**:
  - `activity`: Current streak (days recording mood), total mood logs.
  - `moodAnalytics`: Distribution of moods (e.g., Happy, Anxious, Depressed) over the last 30 days.
  - `consultations`: Summary of Attended, Upcoming, and Cancelled sessions via TeleConsult service.
  - `assessments`: Completion rate (%), count of completed vs. pending assessments.

- **Response Sample**:
```json
{
  "code": 200,
  "message": "Your progress statistics fetched successfully",
  "data": {
    "activity": { "currentStreak": 5, "totalMoodLogs": 12 },
    "moodAnalytics": {
      "period": "30_days",
      "distribution": { "Happy": 8, "Anxious": 3, "Neutral": 10, "Depressed": 2 }
    },
    "consultations": { "total": 6, "attended": 4, "upcoming": 1, "cancelled": 1 },
    "assessments": { "completionRate": 75, "completed": 3, "pending": 1 }
  }
}
```

---

## 👨‍⚕️ 2. Specialist: Aggregate Patient Analytics

Retrieve clinical and demographic statistics for all patients assigned to you (`reportingTo`).

- **URL**: `/api/v1/dashboard/specialist/patient-statistics`
- **Method**: `GET`
- **Access**: Private (Staff Only).
- **Key Metrics Returned**:
  - `summary`: Total patients assigned, pending assessments count.
  - `demographics`: Gender distribution and Age Groups (0-18, 19-35, 36-50, 51+).
  - `clinical`: Mood distribution (aggregated latest findings across the patient base).
  - `engagement`: Enrollment trends (New patients per month for the last 6 months).

- **Response Sample**:
```json
{
  "code": 200,
  "message": "Patient statistics fetched successfully",
  "data": {
    "summary": { "totalPatients": 45, "pendingAssessments": 12 },
    "demographics": {
      "gender": { "male": 20, "female": 24, "other": 1 },
      "ageGroups": { "0-18": 5, "19-35": 25, "36-50": 10, "51+": 5 }
    },
    "clinical": {
      "moodDistribution": { "Euthymic": 15, "Anxious": 10, "Depressed": 5, "Manic": 2 }
    },
    "assessments": { "total": 120, "completed": 108, "pending": 12 },
    "engagement": {
      "enrollmentTrend": [
        { "month": "2024-01", "count": 4 },
        { "month": "2024-02", "count": 6 },
        { "month": "2024-03", "count": 8 }
      ]
    }
  }
}
```

---

## 🏛️ 3. Super Admin: Platform Statistics

High-level overview of system usage and infrastructure.

- **URL**: `/api/v1/dashboard/super-admin`
- **Method**: `GET`
- **Access**: Private (Super Admin Only).
- **Key Metrics Returned**:
  - `users`: Distribution by role (Patient, Specialist, Hospital, etc.).
  - `consultations`: Total vs. Active sessions.
  - `financials`: Total revenue aggregated from paid invoices.
  - `infrastructure`: Total counts of Hospitals and Specialists.

- **Response Sample**:
```json
{
  "code": 200,
  "message": "Super Admin summary fetched successfully",
  "data": {
    "users": [
      { "_id": "patient", "count": 1250 },
      { "_id": "psychiatrist", "count": 45 },
      { "_id": "hospital", "count": 12 }
    ],
    "consultations": { "total": 4500, "active": 85 },
    "financials": { "totalRevenue": 125000.50 },
    "infrastructure": { "hospitals": 12, "specialists": 156 }
  }
}
```

---

## 🛡️ Security & Filtering
- **RBAC**: Endpoints are strictly guarded by role-based access control.
- **Data Isolation**: Patients can never access other patients' statistics.
- **Scope**: Specialist statistics only include patients directly assigned to the requesting professional.
