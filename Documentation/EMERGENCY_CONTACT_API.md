# 🚑 Emergency Contact API

The Emergency Contact API allows patients to store critical contact information for use during clinical crises or safety incidents.

---

## 🏗️ Technical Overview

The `emergencyContact` field is part of the core **Patient Profile**. It is designed to be highly flexible, supporting either a simple string or a JSON-formatted contact object for advanced frontend rendering.

---

## 🔍 1. Retrieve Emergency Contact

Emergency contact details are returned as part of the standard profile lookup.

- **URL**: `/api/v1/users/info`
- **Method**: `GET`
- **Access**: Private (Patient & Assigned Specialists).
- **Response Fragment**:
```json
{
  "emergencyContact": "Jane Doe (Spouse) - +1-555-0199"
}
```

---

## 📝 2. Set/Update Emergency Contact

Patients can update their emergency contact at any time.

- **URL**: `/api/v1/users/update-me`
- **Method**: `PUT`
- **Body Example (Simple String)**:
```json
{
  "emergencyContact": "Alice Smith (Mother) - +1-202-555-0143"
}
```

- **Body Example (Structured JSON)**:
The API also supports structured data if the frontend requires separate fields for Name, Relation, and Phone.
```json
{
  "emergencyContact": {
    "name": "Alice Smith",
    "relation": "Mother",
    "phone": "+1-202-555-0143",
    "is_authorized_for_clinical_info": true
  }
}
```

---

## 🛡️ Clinical Visibility
- **Specialist Access**: Psychiatrists and Psychologists can view a patient's emergency contact via the **Specialist Dashboard** or the **Patient Comprehensive View** (`GET /api/v1/users/patient-view`).
- **AI Integration**: If an AI extraction (e.g., HPI or MSE) detects high-risk markers (SI/HI), the system highlights the emergency contact on the clinician's "Crisis Action" panel.
