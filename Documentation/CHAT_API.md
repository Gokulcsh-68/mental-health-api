# Chat & Chatbot API Documentation

This document describes the real-time communication capabilities of the MindBalance platform, including unified chat (Private AI, Group, Consultation) and the specialized AI Chatbot.

## 🔌 WebSocket Connection

- **Protocol**: Socket.io
- **URL**: `{{BASE_URL}}` (Root namespace `/`)
- **Authentication**: JWT token required.

### Authentication Methods
You can provide the JWT token in two ways:
1. **Query Parameter**: `?auth=token_here`
2. **Auth Object**: `{ "token": "token_here" }` (Recommended)

---

## 💬 Unified Chat (chatHandler)

The unified chat handles private AI conversations, group chats, and consultation-specific rooms.

### 1. Connection & Setup
Once connected, users must join a specific room.

#### Event: `join_room` (Incoming)
- **Payload**:
```json
{
  "room_id": "unique_room_identifier",
  "room_type": "private_ai" | "group_chat" | "consultation"
}
```

#### Event: `user_joined` (Outgoing)
Broadcast to others in the room when a new user joins.
```json
{
  "user_id": "65f...",
  "name": "John Doe",
  "role": "patient"
}
```

---

### 2. Messaging Flow

#### Event: `send_message` (Incoming)
- **Payload**:
```json
{
  "room_id": "room_123",
  "room_type": "consultation",
  "content": "Hello, how are you?",
  "session_id": "main" // Optional, defaults to "main"
}
```

#### Event: `new_message` (Outgoing)
Emitted to all users in the room when a new message is saved.
```json
{
  "_id": "65f...",
  "room_id": "room_123",
  "room_type": "consultation",
  "sender_id": "65f...",
  "sender_role": "patient",
  "sender_name": "John Doe",
  "content": "Hello, how are you?",
  "timestamp": "2024-03-27T10:00:00.000Z"
}
```

---

### 3. Features & Utilities

| Event (Incoming) | Payload | Description |
| :--- | :--- | :--- |
| `typing` | `{ "room_id": "..." }` | Notifies others that user is typing. |
| `stop_typing` | `{ "room_id": "..." }` | Notifies others that user stopped typing. |
| `get_history`| `{ "room_id": "...", "session_id": "main" }` | Requests last 50 messages. Returns `chat_history`. |
| `clear_chat` | `{ "room_id": "...", "session_id": "main" }` | Deletes all messages for this session. |

---

## 🤖 AI Chatbot (chatbotHandler)

Specialized handler for direct patient-to-AI queries with clinical context awareness.

#### Event: `chat_query` (Incoming)
- **Payload**:
```json
{
  "patientId": "PAT-123" | "65f...",
  "messages": [
    { "role": "user", "content": "I'm feeling anxious today." }
  ]
}
```

#### Event: `chat_response` (Outgoing)
- **Payload**: The direct AI response object.

#### Event: `chat_error` (Outgoing)
```json
{
  "message": "Patient ID and messages are required"
}
```

---

## 🛡️ Security & AI Features
1. **AI Triggering**: 
   - In `private_ai` rooms, every message triggers an AI response.
   - In `group_chat` or `consultation`, mentioning `@mindbalance` triggers the AI.
2. **Clinical Awareness**: In `consultation` rooms, the AI automatically retrieves the patient's latest Chief Complaint, HPI, and MSE for context.
3. **Persistence**: All messages in the Unified Chat are persisted to the MongoDB `Message` collection.
