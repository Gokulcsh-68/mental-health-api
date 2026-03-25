const ApiAccess = require('../../src/models/ApiAccess');

const accessList = [
    // Super Admin & Admin: Full Access
    { role_code: 'super_admin', resource: 'all', permissions: ['create', 'read', 'update', 'delete'] },
    { role_code: 'admin', resource: 'all', permissions: ['create', 'read', 'update', 'delete'] },

    // Patient Access
    { role_code: 'patient', resource: 'mood', permissions: ['create', 'read', 'update', 'delete'] },
    { role_code: 'patient', resource: 'user', permissions: ['read'] },

    // Mental Health Professionals
    ...['psychiatrist', 'psychologist', 'counselor', 'nurse', 'social_worker'].flatMap(role => [
        { role_code: role, resource: 'mood', permissions: ['read'] },
        { role_code: role, resource: 'user', permissions: ['read'] }
    ]),

    // Hospital (Institution) Access
    { role_code: 'hospital', resource: 'user', permissions: ['read'] },
    { role_code: 'hospital', resource: 'mood', permissions: ['read'] }
];

const seedApiAccess = async () => {
    await ApiAccess.deleteMany();
    await ApiAccess.insertMany(accessList);
    console.log(`  ✅ API Access seeded (${accessList.length} records)`);
};

module.exports = seedApiAccess;
