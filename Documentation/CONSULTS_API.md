# Consultations API Documentation

The Consultations API manages the end-to-end lifecycle of clinical encounters, from scheduling and virtual room allocation to clinical documentation and billing.

---

## 🔐 Authentication

- **Type**: JWT (JSON Web Token)
- **Header**: `Authorization: Bearer <your_token>`

---

## 📋 Core Management Endpoints

### 1. Book a Consultation
Creates a new consultation record and notifies both the patient and the specialist.

- **URL**: `/api/v1/resource/consults`
- **Method**: `POST`
- **Body**:
```json
{
  "scheduled_at": "2024-03-27T14:30:00.000Z",
  "reason": "Follow-up for anxiety",
  "consult_type": "virtual", // 'virtual', 'home', 'clinic'
  "participants": [
    {
      "ref_number": "1025",
      "participant_type": { "code": "professional" },
      "participant_info": { "name": "Dr. Alan Smith" }
    },
    {
      "ref_number": "1024",
      "participant_type": { "code": "patient" },
      "participant_info": { "name": "John Doe" }
    }
  ]
}
```

### 2. List Consultations
Retrieves a paginated list of consultations for the authenticated user.

- **URL**: `/api/v1/resource/consults`
- **Method**: `GET`
- **Query Params**:
    - `page`, `limit`: Pagination controls.
    - `from_date`, `to_date`: Filter by schedule range (`YYYY-MM-DD`).
    - `consult_status`: Filter by status (`scheduled`, `completed`, `cancelled`).
    - `sort_order`: `asc` or `desc` (default: `desc`).

---

### 3. Get Consultation Details
Fetches the full details of a specific consultation, including participant tokens and clinical metadata.

- **URL**: `/api/v1/resource/consults/:id`
- **Method**: `GET`

---

## 🩺 Clinical Documentation

### 4. Save Clinical Notes
Allows specialists to record structured encounter data (HPI, MSE, Diagnosis, etc.).

- **URL**: `/api/v1/resource/consults/:id/notes`
- **Method**: `POST`
- **Access**: Specialist Only
- **Body Example**:
```json
{
  "clinical_record": {
    "hpi": { "ai_summary": "Patient reports improved sleep..." },
    "mse": { "mood": "Stable", "affect": "Full" },
    "diagnosis": { "primary": "GAD (F41.1)" }
  }
}
```

---

## 🛠️ Lifecycle Actions

| Action | Method | Endpoint | Payload |
| :--- | :--- | :--- | :--- |
| **Reschedule** | `PATCH` | `/consults/:id/reschedule` | `{ "new_scheduled_at": "..." }` |
| **Cancel** | `PATCH` | `/consults/:id/cancel` | `{ "reason": "User requested" }` |
| **Update Status**| `PATCH` | `/consults/:id` | `{ "status": "in_progress" }` |

---

## 🧪 Testing Consultation Creation

To verify the consultation booking flow, use the following payload. 

> [!IMPORTANT]
> **Remote Service Note**: If the `services-api.a2zhealth.in` (Cureselect) endpoint returns a **502 Bad Gateway**, it indicates a temporary outage of the remote tele-consult service. The local API will still attempt to notify but cannot persist the remote `consult_id` until the service is restored.

### 1. Request Payload
- **URL**: `POST /api/v1/resource/consults`
- **Headers**:
    - `x-api-key`: `{{apiKey}}`
    - `Authorization`: `Bearer {{token}}`
- **Body**:
```json
{
  "scheduled_at": "2026-03-28T14:30:00.000Z",
  "participants": [
    {
      "participant_type": { "code": "professional" },
      "ref_number": 3,
      "name": "Dr. Venkatesh"
    },
    {
      "participant_type": { "code": "patient" },
      "ref_number": 4,
      "name": "Karthik"
    }
  ],
  "reason": "Routine Mental Health Checkup",
  "consult_type": "virtual"
}
```

### 2. Expected Result (Success)
```json
{
  "success": true,
  "statusCode": 201,
  "message": "Consultation booked successfully",
  "data": {
    "consult_id": 12345
  }
}
```

---

## 🛡️ Integration Notes
1. **Telehealth Integration**: Virtual consultations automatically allocate a Tokbox/Zoom room.
2. **Auto-Sync**: The local `Consult` model automatically synchronizes with remote teleconsult services every 5 minutes.
3. **Clinical Governance**: Deleting clinical notes is restricted once a consultation is marked as `completed`.
