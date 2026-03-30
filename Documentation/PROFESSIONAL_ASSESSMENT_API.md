# 🩺 Professional Assessment API

The Professional Assessment API allows clinical staff (Psychiatrists, Psychologists, Nurses, etc.) to conduct structured, topic-based clinical assessments aligned with DSM-5 criteria.

---

## 🏗️ Assessment Flow

1. **GET Questions**: Retrieve topic-grouped questions filtered by patient age and gender.
2. **POST Submit**: Staff submits patient responses, notes, and the specific clinical category.
3. **GET History**: Retrieve a patient's historical professional assessments for clinical progress monitoring.

---

## 🗂️ 1. Fetch Assessment Questions

Retrieve questions from the professional question bank, tailored to the patient's demographics.

- **URL**: `/api/v1/professional-assessments/questions`
- **Method**: `GET`
- **Query Parameters**:
  - `patientId` (Number): Required. The internal `userId` of the patient.
- **Access**: Private (Staff & Admin). Patients can only fetch their own assigned questions.

- **Response Structure**:
```json
{
  "code": 200,
  "data": {
    "patient": { "userId": 4, "firstName": "Karthik", "age": 28, "gender": "male" },
    "topics": {
      "Depression": [
        { "questionId": 101, "text": "Over the last 2 weeks, how often have you been bothered by little interest or pleasure in doing things?", "type": "choice", "options": [...] }
      ],
      "Anxiety": [...]
    }
  }
}
```

---

## 🚀 2. Submit Assessment

Save the results of a clinical assessment conducted by a professional.

- **URL**: `/api/v1/professional-assessments/submit`
- **Method**: `POST`
- **Access**: Private (Staff Only).
- **Body Example**:
```json
{
  "patientId": 4,
  "consultId": 1001,
  "category": "DSM-5 Mood Screening",
  "notes": "Patient showed significant improvement in affect today.",
  "responses": [
    { "questionId": 101, "optionId": "65b8f..." },
    { "questionId": 102, "optionId": "65b8f..." }
  ]
}
```

- **Calculations**: The system automatically calculates `totalScore` and `maxPossibleScore` based on the predefined scoring logic in the `Question` model.

---

## 🔍 3. Retrieve Patient Assessment History

Fetch all historical professional assessments for a specific patient.

- **URL**: `/api/v1/professional-assessments/patient/:patientId`
- **Alternative URL**: `/api/v1/professional-assessments/history/:patientId`
- **Method**: `GET`
- **Access**: Private (Staff & Admin). Patients can only see their own clinical history.

- **Response**: Returns a sorted list (most recent first) of assessment records, including:
  - `conductedBy`: ID of the staff member who performed the assessment.
  - `totalScore`: Aggregated clinical score.
  - `responses`: Detailed list of question/answer pairs with full text and scores.
  - `notes`: Clinical observations from the evaluator.

---

## 🛡️ Security & Roles
- **Submission**: Restricted to staff roles (`psychiatrist`, `psychologist`, `nurse`, `counselor`, etc.).
- **Privacy**: Patient data is strictly scoped. Patients cannot access professional assessments belonged to others.
