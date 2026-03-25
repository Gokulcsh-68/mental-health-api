# Mental Health Platform - User Profile Integration Guide

This document details how to retrieve the profile information for the currently authenticated user.

## 🔗 Endpoints

The platform provides two identical endpoints for fetching the logged-in user's profile:

1.  **`GET /api/v1/auth/me`** (Standard Auth endpoint)
2.  **`GET /api/v1/users/info`** (User Module alias)

---

## 🔐 Authorization

Both endpoints are **Private** and require the following headers:

| Header | Value | Description |
| :--- | :--- | :--- |
| `x-api-key` | `{{YOUR_API_KEY}}` | Mandatory API identification key |
| `Authorization` | `Bearer {{JWT_TOKEN}}` | Valid JWT token received during login/registration |

---

## 📥 Request

-   **Method**: `GET`
-   **Body**: None

---

## 📤 Response (200 OK)

Returns the full user profile object.

### Example Response
```json
{
  "success": true,
  "message": "User details fetched successfully",
  "data": {
    "userId": 10,
    "firstName": "John",
    "lastName": "Doe",
    "username": "johndoe",
    "email": "john.doe@example.com",
    "phone": "9876543210",
    "role": "patient",
    "gender": "male",
    "dateOfBirth": "1990-05-15T00:00:00.000Z",
    "profileImage": "https://example.com/profiles/johndoe.jpg",
    "isActive": true,
    "is2fa": false,
    "communicationPreferences": {
      "email": true,
      "sms": true,
      "push": true
    },
    "bloodGroup": "O+",
    "countryIso": "US",
    "timezoneId": 1,
    "createdAt": "2026-02-20T10:00:00.000Z"
  }
}
```

---

## 📋 Field Reference

| Field | Type | Description |
| :--- | :--- | :--- |
| `userId` | Number | Auto-incremented platform-wide numeric ID |
| `firstName` | String | User's first name |
| `lastName` | String | User's last name |
| `username` | String | Unique login identifier |
| `email` | String | User's registered email |
| `role` | String | One of: `patient`, `psychiatrist`, `psychologist`, `nurse`, `social_worker`, `counselor`, `hospital`, `admin`, `super_admin` |
| `phone` | String | Primary contact number |
| `gender` | String | `male`, `female`, or `other` |
| `isActive` | Boolean | Whether the account is currently enabled |
| `communicationPreferences` | Object | Map of notification settings: `{ email, sms, push }` |
| `hospital` | ID/Ref | (Optional) Reference to the parent hospital |
| `professional` | ID/Ref | (Optional) Reference to the assigned professional |
| `reportingTo` | ID/Ref | (Optional) Reference to the logical supervisor |

---

## 💡 Integration Tips

-   **Initial Load**: Call this endpoint immediately after app launch (if a token is stored) to verify the session and populate the global state.
-   **Caching**: You can cache the `data` object locally in `AsyncStorage`, but refresh it periodically or whenever the user navigates to the "Settings" or "Profile" screen.
-   **Role-Based UI**: Use the `role` field to determine which navigation tabs and features to show in the UI.
