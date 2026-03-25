const Role = require('../../src/models/Role');

const roles = [
    { id: 1, code: 'super_admin', name: 'Super Admin' },
    { id: 2, code: 'admin', name: 'Admin' },
    { id: 3, code: 'hospital', name: 'Hospital' },
    { id: 4, code: 'psychiatrist', name: 'Psychiatrist' },
    { id: 5, code: 'psychologist', name: 'Psychologist' },
    { id: 6, code: 'nurse', name: 'Mental Health Nurse' },
    { id: 7, code: 'social_worker', name: 'Social Worker' },
    { id: 8, code: 'counselor', name: 'Counselor' },
    { id: 9, code: 'patient', name: 'Patient' }
];

const seedRoles = async () => {
    await Role.deleteMany();
    await Role.insertMany(roles);
    console.log(`  ✅ Roles seeded (${roles.length} records)`);
};

module.exports = seedRoles;
