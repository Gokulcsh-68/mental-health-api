# 📱 Mobile App Version API

This API provides the current official versions for the MindBalance Android and iOS applications, along with maintenance flags.

---

## 🏗️ Version Logic
- **Endpoint**: `/api/v1/system-settings/:key`
- **Access**: Public
- **Keys**: `android_version`, `ios_version`, `force_update`.

---

## 📩 1. Get Android Version
Retrieve the latest available version on the Google Play Store.

- **URL**: `/api/v1/system-settings/android_version`
- **Method**: `GET`
- **Access**: Public
- **Response Sample (200 OK)**:
```json
{
  "code": 200,
  "message": "System setting fetched successfully",
  "data": {
    "key": "android_version",
    "value": "1.0.5",
    "updatedAt": "2024-03-30T12:00:00.000Z"
  }
}
```

---

## 📩 2. Get iOS Version
Retrieve the latest available version on the Apple App Store.

- **URL**: `/api/v1/system-settings/ios_version`
- **Method**: `GET`
- **Access**: Public
- **Response Sample (200 OK)**:
```json
{
  "code": 200,
  "message": "System setting fetched successfully",
  "data": {
    "key": "ios_version",
    "value": "1.0.3",
    "updatedAt": "2024-03-30T12:00:00.000Z"
  }
}
```

---

## 📩 3. Check Force Update Status
Retrieve whether the current version should be forcibly updated by the user.

- **URL**: `/api/v1/system-settings/force_update`
- **Method**: `GET`
- **Access**: Public
- **Response Sample (200 OK)**:
```json
{
  "code": 200,
  "message": "System setting fetched successfully",
  "data": {
    "key": "force_update",
    "value": false,
    "updatedAt": "2024-03-30T12:00:00.000Z"
  }
}
```

---

## 🛠️ 4. Admin: Update Versions
Administrators can update version strings via the settings API.

- **URL**: `/api/v1/system-settings`
- **Method**: `POST`
- **Access**: Private (Super Admin).
- **Body Example**:
```json
{
  "key": "android_version",
  "value": "1.1.0",
  "description": "Beta launch version"
}
```

---

## 💡 Integration Tips
- **Cold Boot**: Fetch these values during app initialization.
- **Update Prompt**: If `force_update` is `true` AND the local app version is less than the fetched version, the app should prevent further navigation until updated.
