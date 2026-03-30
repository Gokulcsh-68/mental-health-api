# Patient Comprehensive View API Documentation

The Patient View API provides a consolidated response containing the user's profile information, consultation history, and assessment records. This endpoint is designed for the Patient Portal dashboard.

---

## 🔐 Authentication

- **Type**: JWT (JSON Web Token)
- **Header**: `Authorization: Bearer <your_token>`

---

## 📋 Endpoint Details

- **Method**: `GET`
- **URL**: `/api/v1/users/patient-view`
- **Access**: `Patient`, `Super Admin`

### Description
Fetches a high-level overview of the patient's status, including their demographic profile, a list of past/scheduled consultations, and their latest self or professional assessment scores.

---

## 🔄 Response Structure

### Success (200 OK)
```json
{
  "success": true,
  "message": "Comprehensive patient view fetched",
  "data": {
    "profile": {
      "firstName": "John",
      "lastName": "Doe",
      "email": "john.doe@example.com",
      "phone": "9876543210",
      "gender": "male",
      "dateOfBirth": "1990-01-01T00:00:00.000Z",
      "profileImage": "https://...",
      "address": "...",
      "bloodGroup": "O+",
      "createdAt": "2024-03-27T10:00:00.000Z"
    },
    "history": {
      "consultations": [
        {
          "id": "65f...",
          "consultId": "CON-102",
          "date": "2024-03-27T14:30:00.000Z",
          "type": "video",
          "reason": "Anxiety consultation",
          "status": "scheduled",
          "provider": "Dr. Alan Smith",
          "hasPrescription": false
        }
      ],
      "assessments": [
        {
          "id": "65f...",
          "assessmentId": "ASS-1024",
          "category": "depression_phq9",
          "wellnessAspect": "depression_phq9",
          "isSelfAssessment": true,
          "recordedBy": "Patient",
          "date": "2024-03-26T10:00:00.000Z",
          "score": 12,
          "percentage": 44,
          "interpretation": "Moderate"
        }
      ]
    }
  }
}
```

---

## 🔌 WebSocket Integration

In addition to the REST API, the comprehensive patient view can be fetched via WebSockets for real-time applications.

### 1. Fetch Patient View
Request the data after connecting and joining.

- **Event**: `get_patient_view` (Incoming)
- **Payload**: None (Uses socket session authentication)

### 2. Receive Data
The server will emit this event with the comprehensive data.

- **Event**: `patient_view_data` (Outgoing)
- **Payload**: Same structure as the REST API `data` object.

### 3. Error Handling
- **Event**: `user_error` (Outgoing)
- **Payload**: `{ "message": "..." }`

---

## 🛡️ Security & RBAC
1. **Ownership Enforcement**: Patients can only access their own comprehensive view.
2. **Data Integration**: The response pulls data from `User`, `Consult`, and `Assessment` collections to provide a singular entry point for frontend dashboards.
