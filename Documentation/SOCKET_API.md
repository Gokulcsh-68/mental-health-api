# Web Socket API Documentation 🔌

The MindBalance platform uses **Socket.io** for real-time communication, including clinical chat, AI-assisted consultations, and live data synchronization.

## 1. Connection Details

### Base URL
- **Production**: `https://services-api.a2zhealth.in/v1/mental-health/socket.io`
- **Development**: `ws://localhost:5000`

### Authentication
Authentication is enforced at the handshake level using JWT tokens.

#### Option A: Handshake Auth (Preferred)
```javascript
const socket = io("BASE_URL", {
  auth: {
    token: "YOUR_JWT_TOKEN"
  }
});
```

#### Option B: Query String
```javascript
const socket = io("BASE_URL?auth=YOUR_JWT_TOKEN");
```

---

## 2. Unified Chat Handler (`chat.socket`)
Handles clinical conversations, AI-assisted consultations, and group mental health discussions.

### 📥 Client Emits (Requests)

#### `join_room`
Joins a specific communication channel.
- **Payload**:
  ```json
  {
    "room_id": "string (ConsultId or UserId)",
    "room_type": "private_ai" | "group_chat" | "consultation"
  }
  ```

#### `send_message`
Sends a message to a room. Triggers AI response if `room_type` is `private_ai` or if the message contains `@mindbalance`.
- **Payload**:
  ```json
  {
    "room_id": "string",
    "room_type": "string",
    "content": "string",
    "session_id": "string (optional, default: 'main')"
  }
  ```

#### `get_history`
Retrieves the last 50 messages for a room/session.
- **Payload**:
  ```json
  {
    "room_id": "string",
    "session_id": "string (optional)"
  }
  ```

#### `typing` / `stop_typing`
Broadcasts typing status to other participants.
- **Payload**: `{ "room_id": "string" }`

---

### 📤 Server Emits (Responses)

#### `new_message`
Triggered when a new message (user or AI) is added to the room.
- **Payload**: `Message Object` (contains `_id`, `content`, `sender_name`, `sender_role`, `timestamp`)

#### `chat_history`
Response to `get_history`.
- **Payload**: `Array<Message Object>`

#### `user_typing` / `user_stop_typing`
Notifies participants of other users' typing status.
- **Payload**: `{ "user_id": "string", "name": "string" }`

---

## 3. Clinical Chatbot Handler (`chatbot.socket`)
A specialized diagnostic assistant that uses patient history (Chief Complaints, HPI) as context.

### 📥 Client Emits

#### `chat_query`
Sends a query to the diagnostic chatbot.
- **Payload**:
  ```json
  {
    "patientId": "string (MongoId or Custom UserId)",
    "messages": [
      { "role": "user", "content": "I'm feeling very anxious today." }
    ]
  }
  ```

### 📤 Server Emits

#### `chat_response`
Returns the AI's clinical insight.
- **Payload**: `{ "role": "assistant", "content": "string" }`

---

## 4. User Data Handler (`user.socket`)
Handles real-time synchronization of patient profile and history.

### 📥 Client Emits

#### `get_patient_view`
Requests a comprehensive view of the patient's own data.
- **Payload**: `none`

### 📤 Server Emits

#### `patient_view_data`
Returns profile, consultations, and assessment history.
- **Payload**:
  ```json
  {
    "profile": { "firstName": "...", "lastName": "...", "email": "..." },
    "history": {
      "consultations": [ { "id": "...", "date": "...", "provider": "..." } ],
      "assessments": [ { "category": "...", "score": "...", "date": "..." } ]
    }
  }
  ```

---

## 5. Global & System Events

### 📥 Client Emits
- **`ping_test`**: Simple heartbeat check.

### 📤 Server Emits
- **`pong_test`**: Heartbeat response.
  - **Payload**: `{ "time": "ISO_DATE" }`
- **`error` / `chat_error` / `user_error`**: Standardized error notifications.
  - **Payload**: `{ "message": "Description of error" }`
