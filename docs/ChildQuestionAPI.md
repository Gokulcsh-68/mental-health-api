# Child Question API – Detailed Documentation

## Overview

We added a **child‑friendly assessment** feature to the Mental‑Health API. It exposes two new endpoints that are only accessible to patients **12 years old or younger**.

| Endpoint | Method | Purpose |
|----------|--------|---------|
| `/api/v1/questions/children` | **GET** | Retrieve a static list of child‑friendly questions. |
| `/api/v1/questions/children/:id/answer` | **POST** | Submit an answer for a specific question and receive an AI‑generated supportive response. |

Both endpoints are secured by the existing `protect` middleware (JWT auth) and the `authorize('patient')` role guard.

---

## 1. Backend Implementation

### Controllers (`question.controller.js`)
- **`getChildQuestions`** – Calculates the authenticated patient’s age via `calculateAge(req.user.dateOfBirth)`. If the age is > 12, a **403** error is returned. Otherwise the static `CHILD_QUESTIONS` array is returned via `sendSuccess`.
- **`submitChildAnswer`** – Validates age again, finds the requested question in `CHILD_QUESTIONS`, builds a prompt and forwards it to `OpenAIService.chatWithPatient`. The OpenAI response (or a mock response if the API key is missing) is wrapped in a success payload.

Relevant file links:
- Controller: [question.controller.js](file:///d:/OfficeProject/Mental%20Health/Api/src/controllers/question.controller.js)
- OpenAI service usage: see `openAIService.chatWithPatient` in [OpenAIService.js](file:///d:/OfficeProject/Mental%20Health/Api/src/services/OpenAIService.js)

### Routes (`question.routes.js`)
```js
router.get('/children', authorize('patient'), getChildQuestions);
router.post('/children/:id/answer', authorize('patient'), submitChildAnswer);
```
The routes are added after the existing admin routes.

File link: [question.routes.js](file:///d:/OfficeProject/Mental%20Health/Api/src/routes/question.routes.js)

---

## 2. Request / Response Specification

### 2.1 GET `/api/v1/questions/children`
**Headers**
- `Authorization: Bearer <JWT>`

**Success (200)**
```json
{
  "status": "success",
  "code": 200,
  "message": "Child questions fetched",
  "data": [
    {
      "id": 1,
      "emoji": "😊",
      "category": "Emotional Awareness",
      "question": "How often do you feel happy at school?",
      "options": ["Always","Sometimes","Rarely","Never"],
      "correct_index": 0,
      "feedback": "Feeling happy at school is a sign of good emotional health..."
    },
    // … other items up to id 10
  ]
}
```

**Error Responses**
- **401** – Missing or invalid token (handled by `protect`).
- **403** – Authenticated user is older than 12 years.
- **500** – Unexpected server error.

### 2.2 POST `/api/v1/questions/children/:id/answer`
**Headers**
- `Authorization: Bearer <JWT>`
- `Content-Type: application/json`

**Body**
```json
{ "answer": "Happy" }
```
`answer` can be either the **option text** or the **index** (number) of the selected option.

**Success (200)**
```json
{
  "status": "success",
  "code": 200,
  "message": "AI response generated",
  "data": {
    "questionId": 1,
    "answer": "Happy",
    "aiResponse": {
      "role": "assistant",
      "content": "Great job! Feeling happy at school shows you are comfortable..."
    }
  }
}
```
The shape of `aiResponse` mirrors the raw OpenAI response (`role`/`content`). If the OpenAI key is not configured, a mock response is returned (see `OpenAIService` mock logic).

**Error Responses**
- **401** – Missing/invalid token.
- **403** – Age > 12.
- **404** – Question ID not found in the static list.
- **400** – Missing `answer` field.
- **500** – OpenAI integration failure.

---

## 3. Test Script (`test/childAnswer.test.js`)
A tiny Node script is provided to verify the flow end‑to‑end.

```js
const axios = require('axios');

(async () => {
  try {
    // 1️⃣ Get the child question list
    const questionsRes = await axios.get('http://localhost:5000/api/v1/questions/children');
    const questions = questionsRes.data?.data || [];
    if (!questions.length) throw new Error('No child questions returned');
    const { id, options } = questions[0];
    // 2️⃣ Submit the first option as the answer
    const answerRes = await axios.post(`http://localhost:5000/api/v1/questions/children/${id}/answer`, {
      answer: options[0],
    });
    console.log('Answer response:', answerRes.data);
  } catch (err) {
    console.error('Error during test:', err.response?.data || err.message);
  }
})();
```
Run it from the API root:
```bash
node test/childAnswer.test.js
```
Make sure the dev server (`npm run dev`) is running and you have a valid **patient JWT** set in the `Authorization` header. You can add the header globally via an Axios interceptor or modify the script to include:
```js
axios.defaults.headers.common['Authorization'] = 'Bearer <YOUR_JWT>'; 
```

---

## 4. Front‑end Integration (React‑Native)

### 4.1 Navigation
- Added route param type `ChildWellnessDetail` in `src/navigation/types.ts`.
- `ChildWellnessCard` now navigates to `ChildWellnessDetail` when tapped.

File links:
- Types: [types.ts](file:///d:/g/Mental-Heath-App/src/navigation/types.ts)
- Card component: [ChildWellnessCard.tsx](file:///d:/g/Mental-Heath-App/src/components/dashboard/ChildWellnessCard.tsx)

### 4.2 Detail Screen
`ChildWellnessDetailScreen.tsx` implements the full assessment flow:
- **Fetch** questions from `/questions/children` using the shared `api` Axios instance.
- **Render** one question at a time with option selection, optional doctor notes, and progress bar.
- **Submit** each answer locally; when the last question is answered, a *completion* view appears with a *Save Assessment* button (currently placeholder – you can hook it to a real endpoint later).
- Uses the theme context for consistent styling.

File link: [ChildWellnessDetailScreen.tsx](file:///d:/g/Mental-Heath-App/src/screens/patient/ChildWellnessDetailScreen.tsx)

### 4.3 Dashboard Conditional Rendering
In `DashboardScreen.tsx` we compute the patient’s age via `useMemo` and render the `ChildWellnessCard` only when `age <= 12`.

File link: [DashboardScreen.tsx](file:///d:/g/Mental-Heath-App/src/screens/patient/DashboardScreen.tsx)

---

## 5. Security & Permissions
- **JWT authentication** must be active; the `protect` middleware extracts `req.user`.
- **Role guard** (`authorize('patient')`) ensures only patient accounts can call the child endpoints.
- Age verification is performed **both** in the controller (server‑side) and on the client (UI gating) – server‑side check is the authority.

---

## 6. Future Enhancements
1. **Persist answers** – store submitted answers in MongoDB for later analytics.
2. **Batch submit** – allow the mobile client to send all answers in one payload after the assessment.
3. **Internationalisation** – move `CHILD_QUESTIONS` to a JSON file per locale.
4. **OpenAI prompt refinement** – include the patient’s age/gender context for richer feedback.

---

## 7. Quick Reference
```bash
# Start API (if not already running)
npm run dev

# Test the flow (replace <JWT>)
export JWT="<your_patient_token>"
node -e "process.env.AUTH_TOKEN=process.env.JWT" test/childAnswer.test.js
```
Replace `<your_patient_token>` with a valid token for a patient aged ≤ 12.

---

*Documentation generated on 2026‑05‑29.*
