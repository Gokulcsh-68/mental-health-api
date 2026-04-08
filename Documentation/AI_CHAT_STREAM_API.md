# AI Chat Streaming API 🤖

The AI Chat Streaming service provides real-time, word-by-word AI responses using **Server-Sent Events (SSE)**. This is optimized for clinical empathy and interactive "typing" effects without the overhead of WebSockets.

---

## Base URL
`{{URL}}/ai`

---

## 1. Chat Stream
Streams a real-time AI response for a conversation.

### Endpoint
| Method | Path | Auth | Description |
| :--- | :--- | :--- | :--- |
| `POST` | `/chat-stream` | `Required` | Returns a word-by-word SSE stream |

### Security Requirements
- **JWT Authentication**: Must provide a valid `Bearer` token.
- **Platform Integrity**: Must provide the platform `x-api-key`.

### Headers
| Header | Value | Description |
| :--- | :--- | :--- |
| `Authorization` | `Bearer <token>` | Individual user access token |
| `x-api-key` | `<your-api-key>` | Platform global API Key |
| `Content-Type` | `application/json` | Required for JSON request body |

### Request Body
| Field | Type | Required | Description |
| :--- | :--- | :--- | :--- |
| `messages` | `Array` | **Yes** | History of messages `[{role, content}]` |
| `clinicalContext`| `Object` | No | `{ chief_complaint, hpi }` for smart assistant context |

> [!TIP]
> **Automatic Welcome Message**: If you send an empty `messages: []` array, the AI will automatically generate and stream a warm, personalized welcome message using the authenticated user's name.

---

### Response Format (SSE)
The response is a stream of events (`text/event-stream`).

**Continuous Data Events:**
```text
data: {"content": "Hello"}

data: {"content": " there"}

data: {"content": "!"}
```

**Completion Event:**
When the AI finishes, a final `[DONE]` signal is sent.
```text
data: [DONE]
```

---

### Implementation Example (JavaScript)

```javascript
const response = await fetch('{{URL}}/api/v1/ai/chat-stream', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Authorization': `Bearer ${token}`,
    'x-api-key': API_KEY
  },
  body: JSON.stringify({
    messages: [] // Send empty for welcome, or context history for chat
  })
});

const reader = response.body.getReader();
const decoder = new TextDecoder();

while (true) {
  const { done, value } = await reader.read();
  if (done) break;
  
  const chunk = decoder.decode(value);
  const lines = chunk.split('\n');
  
  lines.forEach(line => {
    if (line.startsWith('data: ')) {
      const dataStr = line.replace('data: ', '');
      if (dataStr === '[DONE]') return;
      
      const { content } = JSON.parse(dataStr);
      process.stdout.write(content); // Display word-by-word
    }
  });
}
```

---

### Error Codes
- **401 Unauthorized**: Invalid JWT or missing API Key.
- **400 Bad Request**: "Messages array is required".
- **500 Internal Server Error**: OpenAI service disruption or stream parsing error.
 