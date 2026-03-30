# 🔔 Notification API

The Notification API manages multi-channel communications (In-App, Email, SMS, Push) for patients and staff, including automated AI engagement tips.

---

## 🏗️ Notification Flow

1. **GET Notifications**: Retrieve personalized notifications for the logged-in user.
2. **PUT Mark Read**: Update read status for single or all notifications.
3. **POST Send/Broadcast**: Staff-led or automated notification delivery across multiple channels.

---

## 🔍 1. Retrieve Notifications

Retrieve a paginated list of notifications for the authenticated user.

- **URL**: `/api/v1/notifications`
- **Method**: `GET`
- **Query Parameters**:
  - `page` (Number, Default: 1)
  - `limit` (Number, Default: 10)
  - `isRead` (Boolean, Optional) - Filter by read/unread status.

- **Response Sample**:
```json
{
  "code": 200,
  "message": "Notifications fetched successfully",
  "data": {
    "notifications": [
      {
        "_id": "65f1...",
        "title": "Upcoming Session",
        "message": "Your session with Dr. John starts in 30 minutes.",
        "type": "appointment",
        "isRead": false,
        "createdAt": "2024-03-28T11:45:00.000Z"
      }
    ],
    "unreadCount": 1,
    "pagination": { "page": 1, "limit": 10, "total": 1, "totalPages": 1 }
  }
}
```

---

## 📝 2. Management (Mark as Read)

- **Mark Single**: `PUT /api/v1/notifications/:id/read`
- **Mark All**: `PUT /api/v1/notifications/read-all`

---

## 🚀 3. Sending Notifications (Staff Only)

Send a direct notification to a specific user or broadcast to an entire role.

- **URL**: `/api/v1/notifications/send`
- **Method**: `POST`
- **Access**: Private (Admin, Psychiatrist, Staff).
- **Body Example**:
```json
{
  "userId": "65e2...",
  "title": "Welcome to MindBalance",
  "message": "Please complete your profile to get started.",
  "type": "welcome"
}
```

---

## 🤖 4. AI Engagement Broadcast

Manually trigger the daily AI-generated engagement tip broadcast for all patients.

- **URL**: `/api/v1/notifications/ai-engagement`
- **Method**: `POST`
- **Access**: Private (Super Admin Only).
- **Behavior**: Calls the `TimedNotificationService` to generate and send personalized clinical tips in the background.

---

## 🛡️ Multi-Channel Delivery Status
Every notification record tracks delivery status across all supported channels:
- `deliveryStatus.inApp`: `sent` | `failed`
- `deliveryStatus.email`: `sent` | `failed` | `skipped`
- `deliveryStatus.sms`: `sent` | `failed` | `skipped`
- `deliveryStatus.push`: `sent` | `failed` | `skipped`
