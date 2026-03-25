# Mental Health Platform - Registration Integration Guide

This document provides detailed information for integrating the registration flows into the mobile or web client. There are two primary ways to register users depending on their role and the access level required.

## 🔑 Global Configuration

- **Base URL**: `/api/v1`
- **Required Headers**:
  - `x-api-key`: `{{YOUR_API_KEY}}` (Required for all requests)
  - `Authorization`: `Bearer {{JWT_TOKEN}}` (Required for Private routes)

---

## 🛡️ Role-Based Registration Logic

The platform distinguishes between **Administrative Registration** (Public) and **Staff/Patient Creation** (Private).

| Flow Type | Roles | Endpoint | Access |
|-----------|-------|----------|--------|
| **Public Self-Registration** | All Roles* | `[POST] /auth/register` | Public |
| **Administrative Creation** | All Staff/Patient Roles | `[POST] /users/:role` | Private |

---

## 1. Public Self-Registration
Used by anyone to create an account (Hospitals, Staff, or Patients).

### [POST] `/auth/register`
- **Description**: Registers a new user account.
- **Payload**:
```json
{
  "firstName": "John",
  "lastName": "Smith",
  "username": "johnsmith",
  "email": "john@example.com",
  "password": "SecurePassword123!",
  "phone": "9876543210",
  "role": "patient" 
}
```
> [!NOTE]
> *Allowed Roles for self-registration: `super_admin`, `admin`, `hospital`, `patient`, `psychiatrist`, `psychologist`, `nurse`, `social_worker`, `counselor`.

---

## 2. Administrative User Creation
Used by a logged-in Hospital or Administrator to add staff (Professionals) or Patients.

### [POST] `/users/:role`
- **Description**: Creates a user and automatically maps them to the creator's hierarchy.
- **Path Variable**: `:role` (e.g., `patient`, `psychiatrist`)
- **Headers**: Requires `Authorization` token.
- **Payload**:
```json
{
  "firstName": "John",
  "lastName": "Doe",
  "username": "johndoe_patient",
  "email": "john.doe@email.com",
  "password": "Password123",
  "phone": "1234567890",
  "gender": "male",
  "dateOfBirth": "1990-01-01"
}
```

### 🌳 Automatic Hierarchy Mapping
When a user is created via this endpoint, the system automatically assigns:
- **Hospital**: The hospital the creator belongs to.
- **Professional**: If the creator is a Doctor/Psychologist, they are assigned as the patient's primary professional.
- **Reporting To**: Sets the logical superior in the tree.

---

## 📋 Full Field Reference

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| `firstName` | String | Yes | User's first name |
| `lastName` | String | Yes | User's last name |
| `username` | String | Yes | Unique login identifier |
| `email` | String | Yes | Unique email address |
| `password` | String | Yes | Min 6 characters |
| `phone` | String | Yes | Contact number |
| `role` | String | Yes* | Required in `/auth/register` body |
| `gender` | Enum | No | `male`, `female`, `other` |
| `dateOfBirth` | Date | No | Format: `YYYY-MM-DD` |
| `address` | String | No | Physical address |
| `emergencyContact`| String | No | Name/Phone of emergency contact |
| `profileImage` | String | No | URL to image |
| `fcmTokens` | Array | No | Firebase Cloud Messaging tokens |

---

## 🔄 Response Format

### Success (201 Created)
```json
{
  "success": true,
  "message": "User registered successfully",
  "data": {
    "token": "eyJhbG...",
    "refreshToken": "8f3...a9"
  }
}
```

### Error (400 Bad Request)
```json
{
  "success": false,
  "message": "Please add an email"
}
```
