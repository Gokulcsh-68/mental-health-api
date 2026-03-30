# 🆘 Contact Support & Feedback API

The Contact Support API allows users (and guests) to submit support tickets, bug reports, feature requests, and complaints. These submissions are tracked as "Feedback" tickets for administrative management.

---

## 🏗️ Ticket Categories
When submitting a ticket, you can categorize it using one of the following slugs:
- `support` (Default): General assistance.
- `bug`: Reporting technical issues.
- `feature_request`: Suggestions for improvement.
- `complaint`: formal complaints.
- `other`: Anything else.

---

## 📩 1. Submit Support Ticket (Contact Support)
Submit a support request or feedback message. This endpoint is **Publicly Accessible**, but will automatically link to the user's profile if a valid authentication token is provided.

- **URL**: `/api/v1/feedback`
- **Method**: `POST`
- **Access**: Public / Optional Auth.
- **Body Params**:
  | Parameter | Type | Required | Description |
  | :--- | :--- | :--- | :--- |
  | `subject` | `String` | **Yes** | Brief title of the issue. |
  | `message` | `String` | **Yes** | Detailed description of the request. |
  | `category` | `String` | No | One of: `bug`, `feature_request`, `support`, `complaint`, `other`. |

- **Request Example**:
```json
{
  "subject": "Unable to book session",
  "message": "I receiving an error 500 when trying to book a slot with Dr. Smith.",
  "category": "bug"
}
```

- **Response Sample (201 Created)**:
```json
{
  "code": 201,
  "message": "Feedback submitted successfully",
  "data": {
    "_id": "65f3...",
    "status": "open",
    "category": "bug",
    "subject": "Unable to book session",
    "createdAt": "2024-03-22T10:00:00.000Z"
  }
}
```

---

## 📜 2. Get My Ticket History
Retrieve a list of tickets submitted by the authenticated user.

- **URL**: `/api/v1/feedback/my`
- **Method**: `GET`
- **Access**: Private (Authenticated User).
- **Response Sample**:
```json
{
  "code": 200,
  "message": "Your feedback history fetched successfully",
  "data": [
    {
      "_id": "65f3...",
      "subject": "Unable to book session",
      "status": "resolved",
      "adminNotes": "Issue was due to a server synchronization delay. Fixed.",
      "resolvedAt": "2024-03-23T15:00:00.000Z"
    }
  ]
}
```

---

## 🛠️ 3. Admin: Manage Support Tickets
Endpoints for administrators to view and respond to support tickets.

### List All Tickets
- **URL**: `/api/v1/feedback`
- **Method**: `GET`
- **Access**: Private (Super Admin).
- **Query Params**: `status`, `category`, `page`, `limit`.

### Update Ticket Status
- **URL**: `/api/v1/feedback/:id`
- **Method**: `PUT`
- **Access**: Private (Super Admin).
- **Body Params**:
  | Parameter | Type | Required | Description |
  | :--- | :--- | :--- | :--- |
  | `status` | `String` | No | One of: `open`, `in_progress`, `resolved`, `closed`. |
  | `adminNotes` | `String` | No | Internal response to be shown to the user. |

- **Body Example**:
```json
{
  "status": "resolved",
  "adminNotes": "Added troubleshooting steps to the Help Center."
}
```

- **Response Sample**:
```json
{
  "code": 200,
  "message": "Feedback ticket updated successfully",
  "data": {
    "_id": "65f3...",
    "status": "resolved",
    "adminNotes": "Added troubleshooting steps to the Help Center.",
    "resolvedAt": "2024-03-25T09:00:00.000Z"
  }
}
```

---

## 💡 Integration Tips
- **Pre-filtering**: Use the `category=bug` parameter for the "Report a Bug" screen in the app.
- **Feedback Loop**: When a ticket is marked as `resolved`, it's recommended to notify the user via the Notifications API.
- **Guest Support**: For non-logged-in users, the `userId` field will be `null`, allowing broad support access.
