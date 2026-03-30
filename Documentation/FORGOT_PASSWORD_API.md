# Forgot & Reset Password API Documentation

This document describes the two-step process for resetting a forgotten password on the MindBalance platform.

## Process Overview
1. **Request OTP**: User provides their registered email. The system sends a 6-digit OTP (via Email/SMS) and returns the OTP in the response for development/testing convenience.
2. **Reset Password**: User provides the received OTP and their new password to finalize the reset.

---

## Step 1: Request Password Reset (Forgot Password)

- **HTTP Method**: `POST`
- **URL**: `{{BASE_URL}}/api/v1/auth/forgot-password`
- **Authentication**: Required `x-api-key` header.

### Request Payload
| Field | Type | Required | Description |
| :--- | :--- | :--- | :--- |
| `email` | `String` | **Yes** | Registered email address of the user. |

#### Example Request
```json
{
  "email": "user@example.com"
}
```

### Response Details

#### Success (200 OK)
```json
{
  "code": 200,
  "message": "Password reset OTP generated and sent",
  "data": {
    "resetToken": "123456",
    "expiresIn": "10 minutes"
  }
}
```

#### Error: Email Not Provided (400 Bad Request)
```json
{
  "code": 400,
  "message": "Please provide an email address",
  "data": null
}
```

#### Error: Account Not Found (404 Not Found)
```json
{
  "code": 404,
  "message": "No account found with that email address",
  "data": null
}
```

---

## Step 2: Reset Password (Using OTP)

> [!NOTE]
> For full details on resetting and changing passwords, please refer to the [Reset & Change Password API Documentation](RESET_PASSWORD_API.md).

- **HTTP Method**: `PUT`
- **URL**: `{{BASE_URL}}/api/v1/auth/reset-password/:token`
- **Authentication**: Required `x-api-key` header.
- **Path Parameter**: `:token` (The 6-digit OTP received in Step 1).

### Request Payload
| Field | Type | Required | Description |
| :--- | :--- | :--- | :--- |
| `password` | `String` | **Yes** | The new password for the account. |

#### Example Request
`PUT {{BASE_URL}}/api/v1/auth/reset-password/123456`
```json
{
  "password": "NewSecurePassword123!"
}
```

### Response Details

#### Success (200 OK)
Returns a new set of JWT tokens (Access and Refresh) upon successful reset, automatically logging the user in.
```json
{
  "code": 200,
  "message": "Password reset successful",
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

#### Error: Password Not Provided (400 Bad Request)
```json
{
  "code": 400,
  "message": "Please provide a new password",
  "data": null
}
```

#### Error: Invalid or Expired OTP (400 Bad Request)
```json
{
  "code": 400,
  "message": "Invalid or expired OTP",
  "data": null
}
```

---

## Security Notes
1. **OTP Expiry**: The generated OTP is valid for **10 minutes** only.
2. **One-Time Use**: Once an OTP is used to reset the password, it becomes invalid.
3. **Hashing**: The `resetToken` is stored as a SHA-256 hash in the database for security.
