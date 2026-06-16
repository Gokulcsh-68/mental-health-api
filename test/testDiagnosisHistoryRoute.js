require('dotenv').config();
const request = require('supertest');
const mongoose = require('mongoose');
const app = require('../src/app');
const Diagnosis = require('../src/models/Diagnosis');

const API_KEY = process.env.API_KEY || 'test_api_key';

async function testRoute() {
  console.log('Connecting to DB to setup test user...');
  await mongoose.connect(process.env.MONGO_URI);

  const testUser = {
    firstName: 'Test',
    lastName: 'DiagnosisUser',
    username: 'testdiaguser' + Date.now(),
    email: 'testdiag' + Date.now() + '@example.com',
    password: 'Password123!',
    phone: '1234567890',
    role: 'patient'
  };

  try {
    // 1. Register a test user
    console.log('Registering test user...');
    await request(app)
      .post('/api/v1/auth/register')
      .set('x-api-key', API_KEY)
      .send(testUser);

    // 2. Login to get token
    console.log('Logging in to get token...');
    const loginRes = await request(app)
      .post('/api/v1/auth/login')
      .set('x-api-key', API_KEY)
      .send({
        username: testUser.username,
        password: testUser.password,
        role: testUser.role
      });

    const token = loginRes.body?.data?.token;
    const userId = loginRes.body?.data?.user?.userId;
    const userObjectId = loginRes.body?.data?.user?.id || loginRes.body?.data?.user?._id;

    if (!token) {
      console.error('Failed to get login token:', loginRes.body);
      return;
    }

    console.log(`Successfully logged in. Numeric User ID: ${userId}, Object ID: ${userObjectId}`);

    // 3. Create a mock diagnosis directly in the database so the GET request finds it!
    console.log('\n--- Inserting Mock Diagnosis into Database ---');
    await Diagnosis.create({
      patientId: userObjectId, // Mongoose ObjectId
      patient_id: userId, // Numeric ID
      createdBy: userObjectId, // Matches user
      diagnosis: {
        primary: 'Test Anxiety',
        severity: 'Mild',
        details: 'Mock diagnosis for testing'
      },
      prescription: [],
      aiGenerated: true
    });
    console.log('Successfully inserted mock diagnosis.');

    console.log('\n--- Testing GET /api/v1/diagnosis/ai Route ---');

    // 4. Test without user_id query parameter
    const res1 = await request(app)
      .get('/api/v1/diagnosis/ai')
      .set('x-api-key', API_KEY)
      .set('Authorization', `Bearer ${token}`)
      .send();
      
    console.log(`\n1. GET /api/v1/diagnosis/ai (No user_id) -> Status: ${res1.status}`);

    // 5. Test with user_id query parameter
    const res2 = await request(app)
      .get(`/api/v1/diagnosis/ai?user_id=${userId}`)
      .set('x-api-key', API_KEY)
      .set('Authorization', `Bearer ${token}`)
      .send();
      
    console.log(`\n2. GET /api/v1/diagnosis/ai?user_id=${userId} -> Status: ${res2.status}`);
    console.log('Response length:', res2.body.data ? res2.body.data.length : 0);
    if (res2.body.data && res2.body.data.length > 0) {
      console.log('Data correctly retrieved! Here is the first item:');
      console.log(JSON.stringify(res2.body.data[0], null, 2));
    } else {
      console.log('Response:', res2.body);
    }

  } catch (err) {
    console.error('Test failed:', err);
  } finally {
    await mongoose.disconnect();
  }
}

testRoute().catch(console.error);

