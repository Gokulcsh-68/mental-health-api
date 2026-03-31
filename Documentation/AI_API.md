# AI Transcription API 🎙️

The AI Transcription service provides high-accuracy speech-to-text capabilities powered by OpenAI's Whisper model. It is optimized for clinical narratives,精神科 (psychiatry) intakes, and general neuropsychiatric documentation.

## Base URL
`{{URL}}/api/v1/ai`

---

## 1. Transcribe Audio
Converts a spoken audio file into a plain text string.

### Endpoint
| Method | Path | Auth | Description |
| :--- | :--- | :--- | :--- |
| `POST` | `/transcribe` | `Required` | Converts audio file to plain text |

### Security Requirements
- **JWT Authentication**: Must provide a valid `Bearer` token in the `Authorization` header.
- **Platform Integrity**: Must provide the platform `x-api-key` header.

### Headers
| Header | Value | Description |
| :--- | :--- | :--- |
| `Authorization` | `Bearer <token>` | Access token for the authenticated user |
| `x-api-key` | `<your-api-key>` | Platform global API Key |
| `Content-Type` | `multipart/form-data` | Required for binary file transmission |

### Request Body
The body must be transmitted as **multipart/form-data**.

| Field | Type | Required | Description |
| :--- | :--- | :--- | :--- |
| `audio` | `File / Buffer` | **Yes** | The audio file to transcribe (Supports: mp3, wav, m4a, ogg, webm) |

---

### Implementation Examples

#### Node.js (Axios)
```javascript
const axios = require('axios');
const FormData = require('form-data');
const fs = require('fs');

const form = new FormData();
// Note: When using Buffers in Node, explicitly provide filename and contentType
form.append('audio', audioBuffer, { 
    filename: 'capture.wav', 
    contentType: 'audio/wav' 
});

const { data } = await axios.post('/api/v1/ai/transcribe', form, {
    headers: {
        ...form.getHeaders(),
        'Authorization': `Bearer ${token}`,
        'x-api-key': process.env.API_KEY
    }
});

console.log('Transcription:', data.data.text);
```

#### React Native / Browser (Fetch)
```javascript
const formData = new FormData();
formData.append('audio', {
  uri: audioUri,
  name: 'test.wav',
  type: 'audio/wav',
});

const response = await fetch('/api/v1/ai/transcribe', {
  method: 'POST',
  body: formData,
  headers: {
    'Authorization': `Bearer ${token}`,
    'x-api-key': API_KEY,
  }
});
```

---

### Response Format

#### Success (200 OK)
```json
{
    "success": true,
    "message": "Audio transcribed successfully",
    "data": {
        "text": "The patient describes a sudden onset of low mood and significant insomnia starting last Tuesday."
    }
}
```

#### Common Errors
- **400 Bad Request**: "Audio file is required" (Multipart field `audio` was empty or missing).
- **401 Unauthorized**: "Invalid credentials" or "Not authorized to access this route".
- **413 Payload Too Large**: File exceeds the 25MB limit.
- **500 Internal Server Error**: Issues reaching OpenAI or processing the audio stream.
