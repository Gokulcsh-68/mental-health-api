# Registration API Documentation

This document provides detailed information for registering users with specific roles: **Patient**, **Psychiatrist**, and **Psychologist**.

## Endpoint Overview

- **HTTP Method**: `POST`
- **URL**: `{{BASE_URL}}/api/v1/auth/register`
- **Authentication**: Required `x-api-key` header.

---

## 📋 Common Fields (All Roles)

These fields are mandatory or common across all registration types.

| Field | Type | Required | Description |
| :--- | :--- | :--- | :--- |
| `firstName` | `String` | **Yes** | User's first name. |
| `lastName` | `String` | **Yes** | User's last name. |
| `username` | `String` | **Yes** | Unique username for login. |
| `email` | `String` | **Yes** | Unique and valid email address. |
| `password` | `String` | **Yes** | See [Password Requirements](#password-requirements). |
| `phone` | `String` | **Yes** | Primary contact number. |
| `role` | `String` | **Yes** | `patient`, `psychiatrist`, or `psychologist`. |
| `gender` | `String` | No | `male`, `female`, or `other`. |
| `dateOfBirth` | `Date`| No | Format: `YYYY-MM-DD`. |

### Password Requirements
- Minimum **8 characters**.
- At least **one uppercase** letter.
- At least **one lowercase** letter.
- At least **one number**.
- At least **one special character** (e.g., `@`, `$`, `!`, `%`, `*`, `?`, `&`).

---

## 👤 Role-Specific Details

### 1. Patient Registration
Patients typically only require the common fields.

#### Example Payload (Patient)
```json
{
  "firstName": "Jane",
  "lastName": "Doe",
  "username": "janedoe_p",
  "email": "jane.doe@example.com",
  "password": "Password123!",
  "phone": "9876543210",
  "role": "patient",
  "gender": "female",
  "dateOfBirth": "1992-05-15"
}
```

---

### 2. Professional Registration (Psychiatrist & Psychologist)
Professionals require additional fields to complete their profiles.

| Field | Type | Required | Description |
| :--- | :--- | :--- | :--- |
| `specialization` | `String` | No | Area of expertise (e.g., "Child Psychology"). |
| `about` | `String` | No | Short professional bio. |
| `experienceYears`| `Number` | No | Total years of professional experience. |
| `qualifications` | `Array` | No | List of degrees (e.g., `["MBBS", "MD"]`). |
| `languages` | `Array` | No | List of spoken languages (e.g., `["English", "Spanish"]`). |
| `consultationFee`| `Number` | No | Fee per session. |
| `skills` | `Array` | No | List of professional skills. |

#### Example Payload (Psychiatrist)
```json
{
  "firstName": "Dr. Alan",
  "lastName": "Smith",
  "username": "dralan_psych",
  "email": "alan.smith@clinic.com",
  "password": "SecureDoc789!",
  "phone": "1234567890",
  "role": "psychiatrist",
  "specialization": "Clinical Psychiatry",
  "experienceYears": 12,
  "qualifications": ["MBBS", "MD Psychiatry"],
  "languages": ["English", "French"],
  "consultationFee": 150,
  "about": "Expert in adult clinical psychiatry with over 10 years of experience."
}
```

---

## 🔄 Response Details

### 1. Success (201 Created)
```json
{
  "code": 201,
  "message": "User registered successfully",
  "data": {
    "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
    "refreshToken": "7e8f9a0b1c2d3e4f5g6h7i8j9k0l1m2n3o4p5q6r...",
    "user": {
      "_id": "65f1a2b3c4d5e6f7a8b9c0d1",
      "userId": 1025,
      "firstName": "Jane",
      "lastName": "Doe",
      "email": "jane.doe@example.com",
      "role": "patient"
    }
  }
}
```

### 2. Error Responses

#### A. Validation Error (400 Bad Request)
Returned if mandatory fields are missing or if the password doesn't meet requirements.
```json
{
  "code": 400,
  "message": "User validation failed: password: Password must be at least 8 characters long...",
  "data": null
}
```

#### B. Duplicate Email/Username (409 Conflict)
Returned if the `email` or `username` is already in use.
```json
{
  "code": 409,
  "message": "The email 'jane.doe@example.com' is already in use. Please choose a different one",
  "data": null
}
```

#### C. Invalid Role (403 Forbidden)
Returned if the provided `role` is not allowed for self-registration.
```json
{
  "code": 403,
  "message": "Role 'admin' cannot self-register. Please contact an administrator",
  "data": null
}
```

---

## Security Notes
1. **Verification**: Professionals (`psychiatrist`, `psychologist`) are registered with `isVerified: false` by default. Administrator approval may be required before they can provide services.
2. **Auto-Login**: Successful registration automatically returns a JWT token, allowing for immediate session start.
