# 📚 Help Center & Content API

The Help Center API provides access to support articles, FAQs, legal documents (Privacy/ToS), and clinical guides via a CMS-driven architecture.

---

## 🏗️ Content Architecture

Content is managed using a **Master Slab System** (`master_type_slug: 'portal_content'`). Each article has a unique `slug` (e.g., `faq`, `privacy_policy`, `depression_guide`) and a body containing HTML or Markdown content.

---

## 🔍 1. List Help Center Topics

Retrieve a list of all active help and support topics.

- **URL**: `/api/v1/portal/help-center`
- **Method**: `GET`
- **Access**: Public.
- **Response Sample**:
```json
{
  "code": 200,
  "message": "Help center topics fetched successfully",
  "data": [
    {
      "id": "65f2...",
      "title": "Frequently Asked Questions",
      "slug": "faq",
      "preview": "Common questions about session booking, billing, and...",
      "updatedAt": "2024-03-20T10:00:00.000Z"
    }
  ]
}
```

---

## 📖 2. Retrieve Specific Content

Get the full body of a specific article or legal document.

- **URL**: `/api/v1/portal/content/:type`
- **Method**: `GET`
- **Params**: `type` (Slug, e.g., `tos`, `privacy_policy`, `medication_safety`).
- **Access**: Public.

- **Response Sample**:
```json
{
  "code": 200,
  "message": "Portal content fetched successfully",
  "data": {
    "type": "privacy_policy",
    "title": "Privacy Policy",
    "content": "<h1>Our Commitment to Privacy</h1><p>...</p>",
    "updatedAt": "2024-03-22T14:30:00.000Z"
  }
}
```

---

## 🛠️ 3. Manage Portal Content (Admin)

Create or update help center articles and legal notices.

- **URL**: `/api/v1/portal/content/:type`
- **Method**: `PUT`
- **Access**: Private (Super Admin Only).
- **Body Example**:
```json
{
  "title": "Terms of Service",
  "body": "Full legal text here..."
}
```

---

## 💡 Use Cases
- **Legal Compliance**: Dynamically loading Terms of Service and Privacy Policy during registration.
- **Clinician Guides**: Providing specialists with instructional content via `type=specialist_guide`.
- **FAQ Dashboard**: Supporting the patient portal's searchable support center.

---

## 🔗 Related Resources
- [Contact Support API](file:///d:/OfficeProject/Mental%20Health/Api/Documentation/CONTACT_SUPPORT_API.md) - Submit tickets and feedback.
- [Notification API](file:///d:/OfficeProject/Mental%20Health/Api/Documentation/NOTIFICATION_API.md) - Track updates on support tickets.

