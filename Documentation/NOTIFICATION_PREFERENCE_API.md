# ⚙️ Notification Preference API

The Notification Preference API allows users to control which communication channels the platform uses to deliver alerts, reminders, and clinical updates.

---

## 🏗️ How it Works

The platform uses a **Preference-First Delivery Engine**. Before sending any notification (Email, SMS, or Push), the system checks the user's `communicationPreferences`. If a channel is disabled, the delivery is skipped, but the record is **always** saved to the In-App Notification history.

---

## 🔍 1. Get Current Preferences

Retrieve your current notification settings.

- **URL**: `/api/v1/users/info`
- **Method**: `GET`
- **Access**: Private (Any Role).
- **Response Fragment**:
```json
{
  "communicationPreferences": {
    "email": true,
    "sms": true,
    "push": true
  }
}
```

---

## 📝 2. Update Notification Preferences

Enable or disable specific notification channels.

- **URL**: `/api/v1/users/update-me`
- **Method**: `PUT`
- **Body Example**:
```json
{
  "communicationPreferences": {
    "email": false,
    "sms": true,
    "push": true
  }
}
```

---

## 📲 3. Push Notification (FCM) Management

To receive Push Notifications, the client must register a device token.

- **URL**: `/api/v1/users/update-me`
- **Method**: `PUT`
- **Body Example**:
```json
{
  "fcmTokens": ["fcm_token_from_firebase_here"]
}
```
> [!NOTE]
> Sending a new token will append it to the `fcmTokens` array, allowing multiple devices (Phone, Tablet) to receive alerts simultaneously.

---

## 🛡️ Delivery Logic (Internal)
When a staff member or system triggers a notification:
1. **In-App**: Always created and sent via Socket.io.
2. **Email**: Only sent if `email` preference is `true` AND user has a valid email.
3. **SMS**: Only sent if `sms` preference is `true` AND user has a valid phone.
4. **Push**: Only sent if `push` preference is `true` AND user has active `fcmTokens`.
