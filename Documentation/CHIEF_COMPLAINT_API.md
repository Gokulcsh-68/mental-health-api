# Chief Complaints API Documentation

The Chief Complaints API integrates AI-driven clinical extraction with structured medical records to capture patient narratives efficiently.

---

## 🤖 AI Extraction Workflow

### 1. Extract Preview (Narrative → AI)
Submits a raw patient narrative for AI processing. Returns a preview of structured data but **does not save to the database**.

- **URL**: `/api/v1/chief-complaints/extract`
- **Method**: `POST`
- **Body**:
```json
{
  "patient_id": 1024,
  "narrative": "I have been feeling very low and tired for the past 2 weeks. I can't sleep well and I have lost my appetite."
}
```
- **Response**: Returns a `preview` object with structured fields like `severity`, `duration`, `risk_markers`, etc.

---

## 💾 Persistence & Confirmation

### 2. Save Chief Complaint
Saves the reviewed/confirmed clinical information to the database.

- **URL**: `/api/v1/chief-complaints`
- **Method**: `POST`
- **Body**: Uses the `preview` object from Step 1, enriched with `consult_id` and any manual adjustments.
- **Security**: Narrative is stored encrypted (AES-256).

---

## 📋 Management Endpoints

| Action | Method | Endpoint | Description |
| :--- | :--- | :--- | :--- |
| **List** | `GET` | `/api/v1/chief-complaints` | Filter by `patient_id`, `consult_id`, `severity`, or `risk_level`. |
| **Detail** | `GET` | `/api/v1/chief-complaints/:ccId` | Fetch a single record by its incremental ID. |
| **Override** | `PATCH` | `/api/v1/chief-complaints/:ccId` | Manual correction of AI fields by a Specialist. |
| **Delete** | `DELETE` | `/api/v1/chief-complaints/:ccId` | Soft delete functionality for admins/specialists. |

---

## 🛡️ Risk & Alerts
The API automatically identifies **Red Flags**:
- **Risk Levels**: `None`, `Low`, `Moderate`, `High`.
- **Markers**: Self-harm, Violence, Psychosis, Substance Use.
- **Alerts**: If a high-risk marker is detected, a `Red Flag Alert` is automatically triggered to the assigned supervisor/clinician.

---

## 📊 Data Structure (Structured Object)
```json
{
  "duration": "2 weeks",
  "severity": "Moderate",
  "onset_pattern": "Gradual",
  "affected_domains": {
    "sleep": true,
    "appetite": true,
    "work": false
  },
  "risk_markers": {
    "risk_level": "None",
    "self_harm_detected": false
  }
}
```
