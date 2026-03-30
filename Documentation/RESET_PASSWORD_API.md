# Reset & Change Password API Documentation

This document describes the APIs for resetting a forgotten password and changing an existing password while authenticated.

---

## 🔐 Authentication

- **Reset via OTP**: Required `x-api-key` header.
- **Change Password**: Required `x-api-key` header AND `Authorization: Bearer <JWT_TOKEN>`.

---

## 📋 Endpoints Overview

| Method | Endpoint | Description | Access |
| :--- | :--- | :--- | :--- |
| `PUT` | `/api/v1/auth/reset-password/:token` | Reset password using 6-digit OTP | Public |
| `PUT` | `/api/v1/auth/change-password` | Change password while logged in | Private |

---

## 1. Reset Password (Forgotten Password)
Used to finalize the password reset process after receiving a 6-digit OTP.

- **URL**: `/api/v1/auth/reset-password/:token`
- **Method**: `PUT`
- **Path Parameter**: `:token` (The 6-digit OTP)
- **Body**:
```json
{
  "password": "NewSecurePassword123!"
}
```

### Response (200 OK)
Successful reset returns a new set of JWT tokens and logs the user in.
```json
{
  "code": 200,
  "message": "Password reset successful",
  "data": {
    "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
    "refreshToken": "7e8f9a0b1c2d3e4f5g6h7i8j9k0l1m2n3o4p5q6r...",
    "user": { ... }
  }
}
```

---

## 2. Change Password (Authenticated)
Used by users who are already logged in and wish to update their password.

- **URL**: `/api/v1/auth/change-password`
- **Method**: `PUT`
- **Body**:
```json
{
  "currentPassword": "OldPassword123!",
  "newPassword": "NewSecurePassword456!",
  "confirmPassword": "NewSecurePassword456!"
}
```

### Response (200 OK)
```json
{
  "code": 200,
  "message": "Password changed successfully",
  "data": {
    "token": "...",
    "refreshToken": "...",
    "user": { ... }
  }
}
```

### Error Scenarios
- **400 Bad Request**: Missing fields or `newPassword` mismatch with `confirmPassword`.
- **401 Unauthorized**: `currentPassword` is incorrect or JWT is invalid.

---

## 🛡️ Security Notes
1. **Password Validation**: All new passwords must meet the [MindBalance Password Requirements](REGISTRATION_API.md#password-requirements).
2. **Session Persistence**: Successful password changes/resets return new JWT tokens; the old tokens should be discarded.
3. **Notification**: Users receive an email/app alert whenever their password is changed.
