# 🧠 Mental Status Examination (MSE) API

The MSE API provides a structured way to capture, analyze, and retrieve a patient's behavioral and cognitive state using a clinically-aligned questionnaire and AI-powered analysis.

---

## 🏗️ Mental Status Examination Flow

1. **GET Questionnaire**: Retrieve the clinically-filtered questions based on patient demographics.
2. **POST MSE**: Submit the clinician's/patient's findings for AI analysis and database persistence.
3. **Red Flag Alerts**: System automatically triggers alerts for findings like Suicidal Ideation or Psychosis.

---

## 🗂️ 1. Retrieve Questionnaire

Fetch the appropriate MSE questions tailored to the patient.

- **URL**: `/api/v1/mse/questions`
- **Method**: `GET`
- **Query Parameters**:
  - `age` (Number): Optional. Filters questions by clinical age group.
  - `gender` (String): Optional. Filters questions by gender.
  - `view` (professional | patient): Default `professional`. `patient` view provides simplified terminology.

- **Response**: Returns a structured list of sections (Appearance, Behavior, Speech, etc.) and their respective questions.

---

## 🚀 2. Create MSE Record (with AI Analysis)

Submit structured findings to create a comprehensive MSE record. The system will automatically perform an AI-driven clinical analysis.

- **URL**: `/api/v1/mse`
- **Method**: `POST`
- **Body Structure**:
```json
{
  "patient_id": 4,
  "consult_id": 1001,
  "appearance": {
    "grooming": "Well groomed",
    "dress": "Appropriate",
    "hygiene": "Good",
    "eye_contact": "Normal",
    "notes": "Patient was attentive."
  },
  "behavior": {
    "attitude": "Cooperative",
    "psychomotor": "Normal",
    "mannerisms": ["None"]
  },
  "speech": {
    "rate": "Normal",
    "volume": "Normal",
    "articulation": "Clear"
  },
  "mood": {
    "subjective": "I feel a bit better today",
    "clinician_observed": "Euthymic"
  },
  "affect": {
    "quality": "Euthymic",
    "range": "Full",
    "appropriateness": "Congruent"
  },
  "thought_form": { "process": "Logical/Goal-directed" },
  "thought_content": {
    "delusions": false,
    "suicidal_ideation": "None",
    "homicidal_ideation": "None"
  },
  "perception": { "hallucinations": false },
  "insight": { "level": "Good" },
  "judgment": { "level": "Intact" },
  "cognition": {
    "orientation": { "person": true, "place": true, "time": true },
    "memory": "Intact",
    "concentration": "Intact"
  }
}
```

- **AI-Generated Outputs**: The response includes `ai_analysis`:
  - `affect_recognition`: Inferred emotional state.
  - `clinical_formulation`: Cross-component psychiatric summary.
  - `diagnostic_impressions`: Top likely clinical considerations.
  - `color_code`: Visual urgency marker (Green/Yellow/Orange/Red).

---

## 🔍 3. Retrieve & Filter MSE Records

- **URL**: `/api/v1/mse`
- **Method**: `GET`
- **Query Parameters**:
  - `patient_id` (Number/UUID): Filter by patient.
  - `startDate`/`endDate` (ISO Date): Filter by record date.
  - `color_code` (Hex): Filter by severity (e.g., `#E53935` for Red Flags).
  - `insight_level`: Filter by insight (e.g., "Poor").
  - `memory`: Filter by cognitive findings (e.g., "Impaired").

- **URL (Single)**: `/api/v1/mse/:id`
- **Method**: `GET`
- **Description**: Returns the full MSE record, including nested AI analysis and patient details.

---

## 🚨 Automated Red Flag Alerts

When a record is created via `POST /api/v1/mse`, the system checks for high-risk markers:
1. **Suicidal/Homicidal Ideation** (Not "None")
2. **Delusions/Hallucinations** (Present)

If detected, a **Red Flag Alert** is instantly dispatched to the assigned Psychiatrist via `AlertService`, and the `redFlagNotified` marker is set on the record.
