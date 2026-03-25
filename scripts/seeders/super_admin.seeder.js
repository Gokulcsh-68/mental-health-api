const User = require('../../src/models/User');

const seedSuperAdmin = async () => {
    const password = 'Test12345!';
    
    // 1. Super Admin (Anbarasan Ramesh)
    const superAdmin = await User.create({
        firstName: 'Super', lastName: 'Admin', username: 'superadmin',
        email: 'syeshwanth@cureselecthealthcare.com', password,
        phone: '9840056700', role: 'super_admin', gender: 'male',
        dateOfBirth: new Date('1960-04-02')
    });
    console.log(`  ✅ Super Admin: Super Admin (userId: ${superAdmin.userId})`);
};

module.exports = seedSuperAdmin;