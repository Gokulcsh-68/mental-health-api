# 📜 Past History API (Clinical History)

The Past History API manages a patient's comprehensive clinical background (Medical, Psychiatric, Family, Social, etc.) using a two-step AI extraction flow and AES-256 narrative encryption.

---

## 🏗️ Dual-Mode Clinical History Flow

The system supports two primary entry modes to ensure flexibility for both voice-to-text and manual form clinicians.

### 🎤 Mode 1: Voice Extraction (Narrative to Structured)
1. **Endpoint**: `POST /api/v1/past-history/extract`
2. **Input**: Raw clinical narrative.
3. **Response**: AI-extracted structured clinical data (A-Z format).
4. **Purpose**: Allows clinicians to record a summary and let AI populate the assessment sections.

### 📝 Mode 2: Manual Analysis (Structured to Risk Flags)
1. **Endpoint**: `POST /api/v1/past-history/analyze`
2. **Input**: Fully or partially filled structured assessment sections.
3. **Response**: AI-generated **Risk Flags**, **Treatment Resistance Risk**, and **Genetic Risk Summary**.
4. **Purpose**: Provides a clinical preview and risk assessment for hand-entered structured data *before* final persistence.

### ✅ Final Step: Confirmation & Persistence
1. **Endpoint**: `POST /api/v1/past-history`
2. **Input**: Confirmed structured data (from either Mode 1 or Mode 2).
3. **Persistence**: Saves the record with AES-256 narrative encryption and final AI risk metrics.

---

## 🗂️ 1. Narrative Extraction (Voice Mode)

Submit a clinical narrative to receive a structured preview.

- **URL**: `/api/v1/past-history/extract`
- **Method**: `POST`
- **Body**: `{ "patient_id": 4, "narrative": "..." }`

---

## 🧪 2. Structured Analysis (Manual Mode Preview)

Submit structured fields to receive a clinical risk assessment preview.

- **URL**: `/api/v1/past-history/analyze`
- **Method**: `POST`
- **Body**: Same as the persistence endpoint (see below), but without saving.
- **Response**: Returns `risk_flags`, `treatment_resistance_risk`, `genetic_risk_summary`, and `color_code`.

---

## 🚀 3. Create/Save History Record

Submit finalized clinical findings for database persistence.

- **URL**: `/api/v1/past-history`
- **Method**: `POST`
- **Security**: The `narrative` field is automatically encrypted using AES-256 before storage.
- **AI Analysis**: Upon saving, the system performs a **Risk Analysis**, generating:
  - `risk_flags`: High-level clinical concerns (e.g., "Genetic predisposition for AUD").
  - `treatment_resistance_risk`: Likelihood of poor response to standard interventions.
  - `genetic_risk_summary`: Synthesis of family history risk factors.
  - `color_code`: Visual urgency marker (Green/Yellow/Orange/Red).

---

## 🔍 3. Retrieve & Search History

- **URL**: `/api/v1/past-history`
- **Method**: `GET`
- **Query Parameters**:
  - `patient_id` (Number/UUID): Filter by patient.
  - `consult_id`: Filter by specific consultation.

- **URL (Single)**: `/api/v1/past-history/:id`
- **Method**: `GET`
- **Security**: Returns the decrypted narrative for authorized users.

---

## 📝 4. Specialist Override

Specialists can manually correct or update any field in the history record.

- **URL**: `/api/v1/past-history/:id`
- **Method**: `PATCH`
- **Tracking**: The system automatically populates `doctor_override` with the specialist's ID and timestamp for audit purposes.

---

## 🚨 AI Extraction Metadata
Every record created via AI includes `ai_extraction_metadata`:
- `model_version`: e.g., `gpt-4-clinical-az`
- `confidence_score`: Probability of extraction accuracy.
- `extraction_date`: Timestamp of the AI process.
