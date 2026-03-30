# ⭐ App Rating API

The App Rating API allows users to provide qualitative reviews and numerical ratings (1-5 stars) for the application. This data is used to track user satisfaction and improve the platform experience.

---

## 🏗️ Rating Logic
- **User-Specific**: Ratings are linked to the authenticated user.
- **Single Entry**: While users can submit multiple ratings over time, the system typically retrieves the `latest-rating` for UI logic (e.g., hiding a prompt).
- **Automation**: Submitting a rating automatically triggers a "Thank You" notification to the user.

---

## 📩 1. Submit App Rating
Submit a numerical rating and an optional text review.

- **URL**: `/api/v1/feedback/rate`
- **Method**: `POST`
- **Access**: Private (Authenticated User).
- **Body Params**:
  | Parameter | Type | Required | Description |
  | :--- | :--- | :--- | :--- |
  | `rating` | `Number` | **Yes** | Integer from 1 to 5. |
  | `message` | `String` | No | Optional comment or review text. |

- **Request Example**:
```json
{
  "rating": 5,
  "message": "Excellent platform, very intuitive!"
}
```

- **Response Sample (201 Created)**:
```json
{
  "code": 201,
  "message": "Rating submitted successfully. Thank you!",
  "data": {
    "_id": "65f3...",
    "userId": "65e2...",
    "subject": "App Store Rating",
    "message": "Excellent platform, very intuitive!",
    "category": "app_rating",
    "rating": 5,
    "status": "open",
    "createdAt": "2024-03-22T10:00:00.000Z"
  }
}
```

---

## 🕒 2. Get Latest App Rating
Retrieve the most recent rating provided by the authenticated user. This is often used by the frontend to decide whether to show a "Rate Us" popup.

- **URL**: `/api/v1/feedback/latest-rating`
- **Method**: `GET`
- **Access**: Private (Authenticated User).
- **Response Sample (200 OK)**:
```json
{
  "code": 200,
  "message": "Latest rating fetched successfully",
  "data": {
    "_id": "65f3...",
    "rating": 5,
    "message": "Excellent platform, very intuitive!",
    "category": "app_rating",
    "createdAt": "2024-03-22T10:00:00.000Z"
  }
}
```

---

## 🛠️ 3. Admin: View Ratings
Administrators can view app ratings by filtering the main feedback list.

- **URL**: `/api/v1/feedback?category=app_rating`
- **Method**: `GET`
- **Access**: Private (Super Admin).
- **Query Params**: `page`, `limit`, `status`.

---

## 💡 Frontend Integration
- **Display Logic**: If `GET /latest-rating` returns data, the user has already rated the app. You may choose to hide the rating prompt.
- **Categories**: All ratings are internally stored with the category `app_rating`.
