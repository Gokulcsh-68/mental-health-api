# HPI API Documentation (History of Present Illness)

The HPI API facilitates the capturing of detailed clinical history through AI-powered extraction, ensuring alignment with DSM-5 criteria.

---

## 🚀 Two-Step HPI Flow (Extract & Confirm)

The HPI creation process is split into two steps to allow clinicians to review and adjust AI-extracted data before saving.

### 1. Extract Preview (Narrative → AI)
Submits a raw patient/clinician narrative for AI processing. Returns a preview of structured data but **does not save to the database**.

- **URL**: `/api/v1/hpis/extract`
- **Method**: `POST`
- **Body**:
```json
{
  "patient_id": 1024,
  "narrative": "Patient reports severe anxiety for 3 months. Symptoms are progressive and include insomnia and fatigue."
}
```
- **Response**: Returns a `data` object with structured clinical fields (Onset, Duration, Severity, etc.).

### 2. Confirm & Save (Database Persistence)
Saves the reviewed/confirmed clinical information to the database.

- **URL**: `/api/v1/hpis`
- **Method**: `POST`
- **Body**: Use the `data` object from Step 1, enriched with `consult_id` if available.
```json
{
  "patient_id": 1024,
  "consult_id": 505,
  "narrative": "...",
  "structured": { "onset": "Gradual", "duration": "3 months", ... },
  "dsm5_mapping": ["..."],
  "severity_index": 70,
  "recommendations": ["..."],
  "color_code": "#FF9800"
}
```

---

## 📊 Data Structure (Structured Object)
The extracted HPI data is categorized into clinical domains:

| Domain | Possible Values | Description |
| :--- | :--- | :--- |
| **Onset** | `Gradual`, `Acute` | Speed of symptom development. |
| **Course** | `Episodic`, `Continuous`, `Progressive` | Progression pattern over time. |
| **Sleep** | `Insomnia`, `Hypersomnia`, `Normal` | Impact on sleep patterns. |
| **Safety** | `Passive`, `Active`, `Plan`, `None` | Suicidal ideation assessment. |

---

## 📋 Retrieval & Filtering

| Action | Method | Endpoint | Description |
| :--- | :--- | :--- | :--- |
| **List** | `GET` | `/api/v1/hpis` | Supports advanced filtering by severity, onset, and DSM-5 keywords. |
| **Detail** | `GET` | `/api/v1/hpis/:id` | Fetch detailed HPI records for a specific encounter. |

### 🔍 Advanced Filtering Examples
- **By Severity**: `GET /api/v1/hpis?severity_min=70`
- **By Color Code**: `GET /api/v1/hpis?color_code=#FF0000` (Urgent/Red Flag)
- **By DSM-5 Keyword**: `GET /api/v1/hpis?dsm5_keyword=Depression`

---

## 🛡️ Clinical Alerts
The HPI API automatically triggers **Red Flag Notifications** if:
1. **Suicidal Ideation** is detected (Active/Plan).
2. **Severity Index** exceeds 80/100.
3. **Red Flag Alerts** are routed directly to the patient's assigned clinician via the `AlertService`.
