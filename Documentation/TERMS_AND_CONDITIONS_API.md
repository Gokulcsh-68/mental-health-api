# 📄 Terms and Conditions API

The Terms and Conditions (ToS) API provides the legal agreement that users must accept to use the platform. This content is managed dynamically via the Portal Content system.

---

## 🏗️ Legal Logic
- **Endpoint**: The content is fetched using the `terms_of_service` slug.
- **Access**: Publicly accessible to allow users to read them before registration.
- **Management**: Super Admins can update the text via the Admin Portal.

---

## 📜 1. Get Terms of Service
Retrieve the current terms and conditions for the platform.

- **URL**: `/api/v1/portal/content/terms_of_service`
- **Method**: `GET`
- **Access**: Public
- **Response Sample (200 OK)**:
```json
{
  "code": 200,
  "message": "Portal content fetched successfully",
  "data": {
    "type": "terms_of_service",
    "title": "Terms of Service",
    "content": "By using this platform, you agree to our terms. This platform provides mental health support...",
    "updatedAt": "2024-03-30T10:00:00.000Z"
  }
}
```

---

## 🛠️ 2. Admin: Update Terms
Administrators can update the legal terms via the portal management API.

- **URL**: `/api/v1/portal/content/terms_of_service`
- **Method**: `PUT`
- **Access**: Private (Super Admin).
- **Body Example**:
```json
{
  "title": "Terms of Service v3.0",
  "body": "Updated legal text here..."
}
```

---

## 💡 Integration Tips
- **Registration Flow**: Always display or link to these terms during the user registration process.
- **Versioning**: When terms are significantly updated, use the `updatedAt` field to prompt existing users to re-accept the terms.
