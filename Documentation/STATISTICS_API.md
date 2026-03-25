# Statistics & Analytics API Documentation

The Statistics API provides aggregated insights for both specialists (population-level) and patients (personal-level) to track mental health progress and engagement.

## Base URL
`{{baseUrl}}/api/v1/dashboards`

## Authentication
All requests require:
- `x-api-key`: API key in headers.
- `Authorization`: `Bearer <JWT_TOKEN>`

---

## 1. Specialist: Patient Population Statistics
Provides a high-level overview of all patients assigned to a specialist.

**Endpoint:** `GET /specialist/patient-statistics`
**Access:** Private (Specialists only)

### Key Metrics
- **Summary**: Total patient count and pending assessment tasks.
- **Demographics**: Gender and age-group (0-18, 19-35, 36-50, 51+) distribution.
- **Clinical Snapshot**: Mood distribution across the active patient base.
- **Engagement**: Monthly enrollment trends for the last 6 months.

### Response Snapshot (200 OK)
```json
{
  "code": 200,
  "data": {
    "summary": { "totalPatients": 12, "pendingAssessments": 5 },
    "demographics": { "gender": { "male": 8, "female": 4, "other": 0 }, ... },
    "clinical": { "moodDistribution": { "Happy": 5, "Anxious": 2, ... } }
  }
}
```

---

## 2. Patient: Personal Progress Statistics
Allows an individual patient to track their own metrics and engagement.

**Endpoint:** `GET /patient/statistics`
**Access:** Private (Patient owner only)

### Key Metrics
- **Activity Streak**: Current consecutive days of mood logging.
- **Mood Analytics**: 30-day distribution of subjective mood logs.
- **Consultation Stats**: Total sessions attended, upcoming, and cancelled.
- **Assessment Progress**: Overall completion rate and item counts.

### Response Snapshot (200 OK)
```json
{
  "code": 200,
  "data": {
    "activity": { "currentStreak": 5, "totalMoodLogs": 12 },
    "moodAnalytics": { "period": "30_days", "distribution": { "Happy": 4, "Neutral": 6, ... } },
    "consultations": { "attended": 8, "upcoming": 2, "cancelled": 1 },
    "assessments": { "completionRate": 75, "completed": 3, "pending": 1 }
  }
}
```

---

## Integration Notes
- **Mood Data**: Derived from Mental Status Exam (MSE) logs.
- **Consultation Data**: Synced with the external Cureselect API.
- **Role Enforcement**: `specialist/patient-statistics` is restricted to professional roles via RBAC middleware.
