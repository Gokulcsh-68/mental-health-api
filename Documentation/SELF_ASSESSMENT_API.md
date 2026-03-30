# Self Assessment API Documentation

The Self Assessment API allows patients to perform clinical-grade self-evaluations and track their mental wellness progress over time. This API is used by the MindBalance mobile and web applications.

---

## 🔐 Authentication

- **Type**: JWT (JSON Web Token)
- **Header**: `Authorization: Bearer <your_token>`
- **Refresh Flow**: Standard JWT refresh mechanism applies.

---

## 📋 Endpoints Overview

| Method | Endpoint | Description | Access |
| :--- | :--- | :--- | :--- |
| `GET` | `/api/v1/self-assessments/questions` | Fetch personalized assessment questions | Patient/Staff |
| `POST` | `/api/v1/self-assessments/submit` | Submit assessment responses | Patient |
| `GET` | `/api/v1/self-assessments/history` | View assessment history | Patient/Staff |
| `GET` | `/api/v1/self-assessments/:id` | Get details of a specific assessment | Patient/Staff |

---

## 1. Get Questions
Fetches a list of active self-assessment questions tailored to the user's age and gender.

- **URL**: `/api/v1/self-assessments/questions`
- **Method**: `GET`
- **Query Params**:
    - `patientId` (Optional): Used by staff to fetch questions for a specific patient.

### Response
```json
{
  "success": true,
  "message": "Self-assessment questions fetched",
  "data": {
    "count": 10,
    "questions": [
      {
        "questionId": 1,
        "text": "How often have you felt anxious in the past week?",
        "category": "anxiety",
        "type": "multiple_choice",
        "uiType": "radio",
        "options": [
          { "_id": "65f...", "text": "Not at all", "score": 0 },
          { "_id": "65f...", "text": "Several days", "score": 1 },
          { "_id": "65f...", "text": "More than half the days", "score": 2 },
          { "_id": "65f...", "text": "Nearly every day", "score": 3 }
        ]
      }
    ]
  }
}
```

---

## 2. Submit Assessment
Submits the patient's responses for evaluation and scoring.

- **URL**: `/api/v1/self-assessments/submit`
- **Method**: `POST`
- **Body**:
```json
{
  "responses": [
    {
      "questionId": 1,
      "optionId": "65f..."
    }
  ],
  "notes": "Feeling a bit better today.",
  "professionalRequestId": 123 // Optional: Link to a specific request from a provider
}
```

### Response
```json
{
  "success": true,
  "message": "Self-assessment submitted successfully",
  "data": {
    "assessmentId": "ASS-1024",
    "totalScore": 15,
    "percentage": 75.5
  }
}
```

---

## 3. Get History
Retrieves a list of all completed self-assessments for the authenticated user (or a specific patient if requested by staff).

- **URL**: `/api/v1/self-assessments/history`
- **Method**: `GET`
- **Query Params**:
    - `patientId` (Optional): ID of the patient (Staff only).

### Response
```json
{
  "success": true,
  "message": "Self-assessment history fetched",
  "data": [
    {
      "assessmentId": "ASS-1024",
      "totalScore": 15,
      "percentage": 75.5,
      "status": "completed",
      "createdAt": "2024-03-27T10:00:00.000Z",
      "responses": [
        {
          "questionId": 1,
          "questionText": "...",
          "answerText": "...",
          "score": 3
        }
      ]
    }
  ]
}
```

---

## 4. Get Single Assessment
Retrieves detailed results for a specific assessment.

- **URL**: `/api/v1/self-assessments/:id`
- **Method**: `GET`

### Response
Similar to a single item in the History response, but includes full user details and expanded response data.

---

## 🛡️ Security & Constraints
1. **Ownership**: Patients can only access their own assessments.
2. **Staff Access**: Doctors and clinical staff can view patient history if authorized.
3. **Age/Gender Matching**: The system automatically filters questions based on the patient's profile to ensure clinical relevance.
