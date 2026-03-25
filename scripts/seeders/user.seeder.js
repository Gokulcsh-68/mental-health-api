const User = require('../../src/models/User');

const seedUsers = async () => {
    console.log('  -> inside seedUsers');
    const password = 'AdminPassword@123';
    
    // 2. Admin (Kavitha Selvam)
    const admin = await User.create({
        firstName: 'Kavitha', lastName: 'Selvam', username: 'kavitha',
        email: 'kavitha@example.com', password,
        phone: '9876543210', role: 'admin', gender: 'female',
        dateOfBirth: new Date('1990-05-15')
    });
    console.log(`  ✅ Admin: Kavitha Selvam (userId: ${admin.userId})`);

    // 3. Psychiatrist (Dr. Venkatesh)
    const psychiatrist = await User.create({
        firstName: 'Venkatesh', lastName: 'Kumar', username: 'drvenkatesh',
        email: 'venkatesh@example.com', password,
        phone: '9876543211', role: 'psychiatrist', gender: 'male',
        dateOfBirth: new Date('1980-04-10'),
        specialization: 'Clinical Psychiatry', qualifications: ['MD', 'Board Certified']
    });
    console.log(`  ✅ Psychiatrist: Dr. Venkatesh (userId: ${psychiatrist.userId})`);

    // 4. Psychologist (Dr. Priya)
    const psychologist = await User.create({
        firstName: 'Priya', lastName: 'Natarajan', username: 'drpriya',
        email: 'priya@example.com', password,
        phone: '9876543212', role: 'psychologist', gender: 'female',
        dateOfBirth: new Date('1985-08-20'),
        specialization: 'Cognitive Behavioral Therapy', qualifications: ['PhD', 'Licensed Psychologist']
    });
    console.log(`  ✅ Psychologist: Dr. Priya (userId: ${psychologist.userId})`);

    // 5. Patient (Karthik)
    const patient = await User.create({
        firstName: 'Karthik', lastName: 'Raja', username: 'karthik',
        email: 'karthik@example.com', password,
        phone: '9876543213', role: 'patient', gender: 'male',
        dateOfBirth: new Date('1995-11-25')
    });
    console.log(`  ✅ Patient: Karthik (userId: ${patient.userId})`);

    // 6. Counselor (Suresh)
    const counselor = await User.create({
        firstName: 'Suresh', lastName: 'Babu', username: 'suresh',
        email: 'suresh@example.com', password,
        phone: '9876543214', role: 'counselor', gender: 'male',
        dateOfBirth: new Date('1988-02-14')
    });
    console.log(`  ✅ Counselor: Suresh (userId: ${counselor.userId})`);

    // 7. Nurse (Lakshmi)
    const nurse = await User.create({
        firstName: 'Lakshmi', lastName: 'Devi', username: 'lakshmi',
        email: 'lakshmi@example.com', password,
        phone: '9876543215', role: 'nurse', gender: 'female',
        dateOfBirth: new Date('1992-06-30')
    });
    console.log(`  ✅ Nurse: Lakshmi (userId: ${nurse.userId})`);
};

module.exports = seedUsers;
