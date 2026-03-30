# 📋 Review of Systems (ROS) API

The ROS API facilitates a comprehensive screening of psychiatric and medical symptoms, combining structured clinician/patient inputs with AI-powered risk analysis (e.g., organic rule-outs and medication-induced risks).

---

## 🏗️ Review of Systems Flow

1. **GET Questionnaire**: Retrieve the structured list of ROS questions (Psychiatric and Medical sections).
2. **POST ROS**: Submit detailed answers for AI analysis and persistence.
3. **Organic Rule-Outs**: AI automatically identifies medical conditions that could explain psychiatric symptoms.

---

## 🗂️ 1. Retrieve ROS Questionnaire

Fetch the structured form definition for ROS, including nested follow-up questions.

- **URL**: `/api/v1/ros/questions`
- **Method**: `GET`
- **Query Parameters**:
  - `age` (Number): Optional. Filters questions by clinical age group.
  - `gender` (String): Optional. Filters questions by gender.
  - `view` (professional | patient): Default `professional`. `patient` view provides simplified terminology.

- **Response**: Returns a structured list of sections (Psychiatric, Medical) with question keys, labels, and types.

---

## 🚀 2. Create ROS Record (with AI Analysis)

Submit detailed clinical observations to create a persistent ROS record. The system performs real-time AI analysis on the findings.

- **URL**: `/api/v1/ros`
- **Method**: `POST`
- **Body Example**:
```json
{
  "patient_id": 9,
  "consult_id": 1005,
  "psychiatric": {
    "depressed_mood": true,
    "depressed_mood_duration": "2 months",
    "depressed_mood_severity": 8,
    "anxiety": true,
    "anxiety_type": "Panic attacks",
    "panic_attacks": true,
    "psychosis": false,
    "substance_use": true,
    "substance_types": ["Cannabis"],
    "substance_frequency": "Daily"
  },
  "medical": {
    "thyroid_symptoms": true,
    "thyroid_type": "Hypothyroidism",
    "thyroid_diagnosed": true,
    "medication_history": true,
    "medications_list": "Prednisolone 30mg"
  },
  "extra_notes": "Patient is on corticosteroids for autoimmune condition."
}
```

- **AI-Generated Analysis**: The saved record will include:
  - `organic_red_flags`: Medical conditions to rule out (e.g., "Steroid-induced psychosis", "Hypothyroidism contributing to mood").
  - `medication_induced_risk`: Risks identified from the medication list.
  - `substance_induced_probability`: Likelihood of symptoms being substance-driven (None/Low/Moderate/High).
  - `color_code`: Visual urgency marker based on organic/substance risk.

---

## 🔍 3. Retrieve & Filter ROS Records

- **URL**: `/api/v1/ros`
- **Method**: `GET`
- **Query Parameters**:
  - `patient_id` (Number/UUID): Filter by patient.
  - `substance_induced_probability`: Filter by drug risk level (e.g., "High").
  - `color_code` (Hex): Filter by severity.
  - `red_flag` (String): Keyword search within Organic Red Flags (e.g., "Thyroid").
  - `startDate`/`endDate` (ISO Date): Filter by record date.

- **URL (Single)**: `/api/v1/ros/:id`
- **Method**: `GET`
- **Description**: Returns the full ROS record with all detailed answers and AI findings.

---

## 🚨 Automated Alerts

If **Organic Red Flags** are identified by the AI (e.g., serious medical complications masking as psychiatric symptoms), the system triggers an immediate **Red Flag Alert** to the assigned Psychiatrist or Supervisor, and marks `redFlagNotified: true`.
