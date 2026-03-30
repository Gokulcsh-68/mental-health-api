# 🌐 Web App Version API

This API provides the current production version of the MindBalance Web Portal.

---

## 🏗️ Version Logic
- **Endpoint**: `/api/v1/system-settings/web_version`
- **Access**: Public
- **Key**: `web_version`

---

## 📩 1. Get Web Version
Retrieve the version currently deployed to the web production environment.

- **URL**: `/api/v1/system-settings/web_version`
- **Method**: `GET`
- **Access**: Public
- **Response Sample (200 OK)**:
```json
{
  "code": 200,
  "message": "System setting fetched successfully",
  "data": {
    "key": "web_version",
    "value": "2.4.1",
    "updatedAt": "2024-03-30T12:00:00.000Z"
  }
}
```

---

## 🛠️ 2. Admin: Update Web Version
Administrators can update the web version string.

- **URL**: `/api/v1/system-settings`
- **Method**: `POST`
- **Access**: Private (Super Admin).
- **Body Example**:
```json
{
  "key": "web_version",
  "value": "2.5.0"
}
```

---

## 💡 Frontend Integration
- **Footer**: Display the `web_version` in the footer of the portal for troubleshooting.
- **Cache Invalidation**: Use the version string as a query parameter for static assets (e.g., `main.js?v=2.4.1`) to ensure users always have the latest code.
