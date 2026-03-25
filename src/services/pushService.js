const admin = require('firebase-admin');
const path = require('path');
const fs = require('fs');
const config = require('../config/config');

let firebaseInitialized = false;

/**
 * Initialize Firebase Admin SDK (once)
 */
const initFirebase = () => {
    if (firebaseInitialized) return true;

    // Option 1: Use a service account JSON file
    const serviceAccountPath = path.join(__dirname, '../../firebase-service-account.json');
    if (fs.existsSync(serviceAccountPath)) {
        const serviceAccount = require(serviceAccountPath);
        admin.initializeApp({
            credential: admin.credential.cert(serviceAccount)
        });
        firebaseInitialized = true;
        console.log('  🔥 Firebase Admin SDK initialized (Service Account)');
        return true;
    }

    // Option 2: Use GOOGLE_APPLICATION_CREDENTIALS env var
    if (process.env.GOOGLE_APPLICATION_CREDENTIALS) {
        admin.initializeApp({
            credential: admin.credential.applicationDefault()
        });
        firebaseInitialized = true;
        console.log('  🔥 Firebase Admin SDK initialized (Application Default)');
        return true;
    }
    
    // Option 3: Use FIREBASE_CONFIG env var (JSON string)
    if (process.env.FIREBASE_CONFIG) {
        try {
            const serviceAccount = JSON.parse(process.env.FIREBASE_CONFIG);
            admin.initializeApp({
                credential: admin.credential.cert(serviceAccount)
            });
            firebaseInitialized = true;
            console.log('  🔥 Firebase Admin SDK initialized (Environment Variable)');
            return true;
        } catch (err) {
            console.error('  ❌ Failed to parse FIREBASE_CONFIG:', err.message);
        }
    }

    console.log('  ⚠️  Firebase not configured. Push notifications run in LOG MODE.');
    return false;
};


const sendPushNotification = async ({ tokens, title, body, data, imageUrl }) => {
    if (!tokens || tokens.length === 0) {
        console.log(`  🔔 [SKIP] No FCM tokens for push notification`);
        return { success: true, skipped: true };
    }

    if (initFirebase()) {
        try {
            const results = await Promise.all(
                tokens.map(async (token) => {
                    const message = {
                        token: token,
                        data: data ? Object.fromEntries(
                            Object.entries(data).map(([k, v]) => [k, String(v)])
                        ) : {},
                        android: {
                            priority: 'high'
                        },
                        apns: {
                            payload: {
                                aps: {
                                    contentAvailable: true,
                                    badge: 1
                                }
                            }
                        }
                    };

                    // Only add notification object if title and body are provided
                    if (title || body) {
                        message.notification = {
                            title: title || '',
                            body: body || ''
                        };
                        
                        // Only add image if provided
                        if (imageUrl) {
                            message.notification.image = imageUrl;
                        }

                        message.android.notification = {
                            sound: 'default',
                            channelId: 'mental_health_alerts'
                        };
                        
                        if (imageUrl) {
                            message.android.notification.image = imageUrl;
                        }

                        message.apns.payload.aps.sound = 'default';
                        message.apns.payload.aps.mutableContent = true; // Required for iOS images
                        
                        if (imageUrl) {
                            message.apns.fcmOptions = {
                                image: imageUrl
                            };
                        }
                    }

                    try {
                        const response = await admin.messaging().send(message);
                        return { token, success: true, messageId: response };
                    } catch (err) {
                        console.error(`  ❌ Push failed for token ${token.substring(0, 10)}...: ${err.message}`);
                        return { token, success: false, error: err.message };
                    }
                })
            );

            const successCount = results.filter(r => r.success).length;
            console.log(`  🔔 Push sent: ${successCount}/${tokens.length} device(s)`);
            return { success: successCount > 0, results };
        } catch (err) {
            console.error(`  ❌ Push service error:`, err.message);
            return { success: false, error: err.message };
        }
    } else {
        // LOG MODE — no Firebase credentials
        console.log(`  🔔 [LOG MODE] Push → ${tokens.length} device(s)`);
        console.log(`     Title: ${title}`);
        console.log(`     Body: ${body}`);
        return { success: true, mode: 'log' };
    }
};

module.exports = { sendPushNotification };
