# 👤 User Profile API

The Profile API allows users (Patients, Psychiatrists, Psychologists, etc.) to manage their personal information, professional credentials, and account settings.

---

## 🏗️ Profile Management Flow

1. **GET Profile**: Retrieve the current user's full information and role-specific metadata.
2. **PUT Update Profile**: Update personal or professional fields, including profile image upload to S3.

---

## 🔍 1. Get Current Profile Info

Retrieve the logged-in user's account details.

- **URL**: `/api/v1/users/info`
- **Method**: `GET`
- **Access**: Private (Any Role).
- **Key Fields Returned**: Full User object (including role, nested associations like `hospital` or `reportingTo`).

---

## 📝 2. Update My Profile

Update personal or professional details for the authenticated user.

- **URL**: `/api/v1/users/update-me`
- **Method**: `PUT`
- **Content-Type**: `multipart/form-data` (if uploading image) or `application/json`.
- **Fields (Common)**:
  - `firstName`, `lastName` (String)
  - `phone`, `gender` (male/female/other)
  - `dateOfBirth` (ISO Date)
  - `address`, `city` (String)
  - `bloodGroup` (String)
  - `profileImage` (File or URL string)
  - `communicationPreferences`: `{ "email": Boolean, "sms": Boolean, "push": Boolean }`

### 🩺 Role-Specific Fields

#### **Patients**
  - `emergencyContact` (String)

#### **Psychiatrists / Psychologists (Specialists)**
  - `specialization` (String) - e.g., "Child & Adolescent Psychiatry"
  - `about` (String) - Professional bio.
  - `experienceYears` (Number)
  - `qualifications` (Array of Strings) - e.g., ["MD", "PhD"]
  - `languages` (Array of Strings)
  - `consultationFee` (Number)
  - `skills` (Array of Strings) - e.g., ["CBT", "EMDR"]

---

## 🖼️ Profile Image Upload
The API supports two methods for updating the `profileImage`:
1. **Multipart Upload**: Send a file in the `profileImage` field. The system automatically uploads it to AWS S3 and provides a permanent CDR/CDN link.
2. **URL Upload**: Send a public image URL. The system will download the image and re-host it on S3 for persistence.

---

## 🛡️ Access Control & Validation
- **Protected Fields**: Users **cannot** update their own `role`, `userId`, `resetPasswordToken`, or `isActive` status via this endpoint.
- **Validation**: Passwords must be updated via the specific Auth reset flow, not the general profile update.

---

## 📦 3. Sample Response: Specialist Profile (Psychiatrist)

- **URL**: `GET /api/v1/users/info`
- **JSON Structure**:
```json
{
  "code": 200,
  "message": "User fetched successfully",
  "data": {
    "userId": 4235641099,
    "firstName": "John",
    "lastName": "Doe",
    "role": "psychiatrist",
    "email": "dr.john@mindbalance.com",
    "phone": "+1234567890",
    "profileImage": "https://s3.amazonaws.com/mindbalance/profiles/dr_john.jpg",
    "specialization": "Clinical Psychiatry",
    "about": "Dr. John has over 15 years of experience in managing complex mood disorders.",
    "experienceYears": 15,
    "qualifications": ["MD", "Board Certified Psychiatry"],
    "languages": ["English", "Spanish"],
    "consultationFee": 200,
    "skills": ["CBT", "Psychopharmacology"],
    "isVerified": true,
    "address": "123 Medical Plaza",
    "city": "New York",
    "isActive": true
  }
}
```

---

## 📦 4. Sample Response: Patient Profile

- **URL**: `GET /api/v1/users/info`
- **JSON Structure**:
```json
{
  "code": 200,
  "message": "User fetched successfully",
  "data": {
    "userId": 1099,
    "firstName": "Sarah",
    "lastName": "Parker",
    "role": "patient",
    "gender": "female",
    "dateOfBirth": "1992-05-15T00:00:00.000Z",
    "phone": "+1987654321",
    "emergencyContact": "Mr. Parker (+1987554321)",
    "profileImage": "https://s3.amazonaws.com/mindbalance/profiles/sarah.jpg",
    "address": "456 Oak Avenue",
    "city": "Chicago",
    "bloodGroup": "O+",
    "isActive": true
  }
}
```
