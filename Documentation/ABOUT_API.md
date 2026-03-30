# ℹ️ About MindBalance API

The About API provides public information regarding the platform's mission, history, and core values. This content is managed dynamically via the Portal Content system.

---

## 🏗️ Content Logic
- **Slug**: `about_mindbalance`
- **Access**: Publicly accessible.
- **Management**: Super Admins can update this content via the portal management API.

---

## 📩 1. Get About Information
Retrieve the official description and mission statement of MindBalance.

- **URL**: `/api/v1/portal/content/about_mindbalance`
- **Method**: `GET`
- **Access**: Public
- **Response Sample (200 OK)**:
```json
{
  "code": 200,
  "message": "Portal content fetched successfully",
  "data": {
    "type": "about_mindbalance",
    "title": "About MindBalance",
    "content": "MindBalance is a comprehensive mental health platform designed to bridge the gap...",
    "updatedAt": "2024-03-30T10:00:00.000Z"
  }
}
```

---

## 🛠️ 2. Admin: Update About Text
Administrators can update the About section content.

- **URL**: `/api/v1/portal/content/about_mindbalance`
- **Method**: `PUT`
- **Access**: Private (Super Admin).
- **Body Example**:
```json
{
  "title": "About MindBalance Platform",
  "body": "Updated mission statement and vision text here..."
}
```

---

## 💡 Frontend Integration
- **Platform Overview**: Use this content for the "About Us" or "Introduction" screen in the user profile or footer.
- **Consistency**: Centralizing this content via API ensures that all client applications (web, mobile, tablet) display the same official information.
