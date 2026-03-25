# Symptom Tracker API Documentation

The Symptom Tracker API allows recording and monitoring of patient symptom severity over time using a standardized 0-10 scale.

## Base URL
`{{baseUrl}}/api/v1/symptoms`

## Authentication
All requests require:
- `x-api-key`: Provided in the request headers.
- `Authorization`: `Bearer <JWT_TOKEN>` (Professional or Patient token).

---

## 1. Save Symptom Scores
Record a new set of symptom ratings for a patient.

**Endpoint:** `POST /`
**Access:** Private (Professional/Self)

### Request Body
```json
{
  "patientId": "userId or ObjectId",
  "consult_id": 123, // Optional: link to a specific consult session
  "scores": {
    "mood": 5,           // 0 (Depressed) to 10 (Elevated)
    "anxiety": 3,        // 0 (None) to 10 (Panic)
    "sleep": 6,          // 0 (Poor) to 10 (Restful)
    "appetite": 5,       // 0 (Reduced) to 10 (Increased)
    "energy": 4,         // 0 (Fatigued) to 10 (Hyper)
    "concentration": 5   // 0 (Distracted) to 10 (Focused)
  },
  "notes": "Patient reporting better sleep since last session."
}
```

### Response (201 Created)
```json
{
  "code": 201,
  "message": "Symptom scores saved successfully",
  "data": {
    "symptomId": 1,
    "patient": "6531f...",
    "scores": { ... },
    "color_code": "#4CAF50",
    "createdAt": "2026-03-18T12:00:00Z"
  }
}
```

---

## 2. Get Symptom History
Retrieve a paginated list of symptom records for a specific patient.

**Endpoint:** `GET /patient/:patientId`
**Access:** Professional or Owner

### Query Parameters
- `limit`: Number of records (default: 10)
- `page`: Page number (default: 1)

### Response (200 OK)
```json
{
  "code": 200,
  "data": {
    "symptoms": [ ... ],
    "pagination": {
      "total": 15,
      "page": 1,
      "limit": 10,
      "pages": 2
    }
  }
}
```

---

## 3. Get Symptom Detail
Retrieve details of a specific symptom record.

**Endpoint:** `GET /:id`
**Access:** Professional or Owner

### Response (200 OK)
```json
{
  "code": 200,
  "data": {
    "symptomId": 1,
    "scores": { ... },
    "notes": "...",
    "color_code": "#FB8C00"
  }
}
```

---

## Clinical Summary Integration
Symptoms are automatically included in the patient's holistic clinical view:
`GET /api/v1/patients/:patientId/clinical-summary`

The `summary.modules_status.symptoms` field will be `completed` if at least one symptom set is recorded.
