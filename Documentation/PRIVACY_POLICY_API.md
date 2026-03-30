# 📜 Privacy Policy API

The Privacy Policy endpoint provides the legally required static content explaining how user data is collected and processed. This content is managed dynamically via the Portal Content system.

---

## 🔒 1. Get Privacy Policy
Retrieve the current privacy policy for the platform.

- **URL**: `/api/v1/portal/content/privacy_policy`
- **Method**: `GET`
- **Access**: Public
- **Response Sample (200 OK)**:
```json
{
  "code": 200,
  "message": "Portal content fetched successfully",
  "data": {
    "type": "privacy_policy",
    "title": "Privacy Policy",
    "content": "Your privacy is important to us. This policy explains how we collect, use, and safeguard your data...",
    "updatedAt": "2024-03-30T10:00:00.000Z"
  }
}
```

---

## 🛠️ 2. Admin: Update Policy
Administrators can update the privacy policy via the portal management API.

- **URL**: `/api/v1/portal/content/privacy_policy`
- **Method**: `PUT`
- **Access**: Private (Super Admin).
- **Body Example**:
```json
{
  "title": "Privacy Policy v2.1",
  "body": "Updated legal text here..."
}
```

---

## 💡 Integration Tips
- **Pre-fetching**: It is recommended to fetch the Privacy Policy during app cold-start or when opening the "Legal" section of the app.
- **Caching**: These documents change infrequently; consider local caching on the device for better performance.
