const SystemSetting = require('../../src/models/SystemSetting');

const systemSettings = [
    {
        key: "android_version",
        value: "1.0.5",
        description: "Latest available version for Android app",
        category: "general"
    },
    {
        key: "ios_version",
        value: "1.0.3",
        description: "Latest available version for iOS app",
        category: "general"
    },
    {
        key: "web_version",
        value: "2.4.1",
        description: "Current production version of the web portal",
        category: "general"
    },
    {
        key: "force_update",
        value: false,
        description: "Whether to force users to update to the latest version",
        category: "maintenance"
    },
    {
        key: "maintenance_mode",
        value: false,
        description: "Global maintenance mode toggle",
        category: "maintenance"
    }
];

const seedSystemSettings = async () => {
    try {
        for (const setting of systemSettings) {
            await SystemSetting.findOneAndUpdate(
                { key: setting.key },
                setting,
                { upsert: true, new: true }
            );
        }
        console.log(`  ✅ ${systemSettings.length} System Settings seeded/updated`);
    } catch (err) {
        console.error('  ❌ System Setting Seeder Error:', err.message);
        throw err;
    }
};

module.exports = seedSystemSettings;
