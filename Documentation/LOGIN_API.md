# Login API Documentation

This document provides detailed information about the Login API for the MindBalance platform.

## Endpoint Overview

- **HTTP Method**: `POST`
- **URL**: `{{BASE_URL}}/api/v1/auth/login`
- **Authentication**: Required `x-api-key` header for all requests.

> [!IMPORTANT]
> All requests must include the `x-api-key` header. If missing or invalid, the API will return a `401 Unauthorized` error.

### Base URL
- **Local**: `http://localhost:5000`
- **Staging/Production**: *To be provided by the DevOps team.*

---

## Request Details

### Headers
| Header | Value | Description |
| :--- | :--- | :--- |
| `Content-Type` | `application/json` | Required for sending JSON payload. |
| `x-api-key` | `YOUR_API_KEY` | Required for all API requests. |

### Payload (JSON Body)
| Field | Type | Required | Description |
| :--- | :--- | :--- | :--- |
| `username` | `String` | **Yes** | The username of the account. |
| `password` | `String` | **Yes** | The account password. |
| `role` | `String` | **Yes** | The user role (e.g., `super_admin`, `patient`, `psychiatrist`, `nurse`). |
| `fcmToken` | `String` | No | Firebase Cloud Messaging token for push notifications. |

#### Example Request Body
```json
{
  "username": "jdoe_patient",
  "password": "Password123!",
  "role": "patient",
  "fcmToken": "fcm_token_example_123..."
}
```

---

## Response Details

### 1. Success (200 OK)
Returned when credentials are valid and the account is active.

#### Success Response Structure
| Field | Type | Description |
| :--- | :--- | :--- |
| `code` | `Number` | HTTP status code (200). |
| `message` | `String` | "Login successful". |
| `data` | `Object` | Contains tokens and user profile information. |

#### Example Success Response
```json
{
  "code": 200,
  "message": "Login successful",
  "data": {
    "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
    "refreshToken": "7e8f9a0b1c2d3e4f5g6h7i8j9k0l1m2n3o4p5q6r...",
    "user": {
      "_id": "65f1a2b3c4d5e6f7a8b9c0d1",
      "userId": "PAT-1024",
      "firstName": "John",
      "lastName": "Doe",
      "email": "john.doe@example.com",
      "role": "patient"
    }
  }
}
```

### 2. Error Responses

#### A. Missing Required Fields (400 Bad Request)
Returned if `username`, `password`, or `role` is missing from the request body.
```json
{
  "code": 400,
  "message": "Please provide username, password and role",
  "data": null
}
```

#### B. Invalid Credentials (401 Unauthorized)
Returned if the username/password/role combination is incorrect.
```json
{
  "code": 401,
  "message": "Invalid credentials",
  "data": null
}
```

#### C. Account Locked (403 Forbidden)
Returned if there are 5 or more consecutive failed login attempts. The account is locked for 1 hour.
```json
{
  "code": 403,
  "message": "Account is temporarily locked due to multiple failed attempts. Please try again in 60 minutes",
  "data": null
}
```

#### D. Missing/Invalid API Key (401 Unauthorized)
Returned if the `x-api-key` header is missing or incorrect.
```json
{
  "code": 401,
  "message": "API key is required. Provide x-api-key header",
  "data": null
}
```

#### E. Server Error (500 Internal Server Error)
Returned in case of unexpected database or system failures.
```json
{
  "code": 500,
  "message": "Server Error",
  "data": null
}
```

---

## Security Notes
1. **JWT Expiration**: The `token` provided in the success response is a short-lived bearer token (check `JWT_EXPIRE` in `.env`).
2. **Refresh Token**: Use the `refreshToken` to obtain a new access token via the `/api/v1/auth/refresh-token` endpoint when the main token expires.
3. **Password Security**: All passwords should be sent over HTTPS.
