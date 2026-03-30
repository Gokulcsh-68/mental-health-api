const axios = require('axios');
require('dotenv').config();

const API_KEY = process.env.API_KEY || 'ygXk1R15vil+RD9Ix5c4cUPqND5i7+M3NRsEmxByDL8=';
const BASE_URL = 'http://localhost:5000/api/v1';

async function testCreateHPI() {
  try {
    // 1. Login as Admin
    console.log('Logging in...');
    const loginRes = await axios.post(`${BASE_URL}/auth/login`, {
      username: 'kavitha',
      password: 'AdminPassword@123',
      role: 'admin'
    }, {
      headers: { 'x-api-key': API_KEY }
    });
    const token = loginRes.data.data.token;
    console.log('✅ Logged in');

    // 2. Create HPI
    console.log('Creating HPI...');
    const payload = {
      patient_id: 4, // Karthik
      consult_id: 1001,
      narrative: "Patient reports feeling very low and tired for the past month. He has difficulty falling asleep and has lost interest in his usual hobbies. He also mentions feeling hopeless about the future."
    };

    const createRes = await axios.post(`${BASE_URL}/hpis`, payload, {
      headers: {
        'x-api-key': API_KEY,
        'Authorization': `Bearer ${token}`
      }
    });

    console.log('✅ HPI Created:', JSON.stringify(createRes.data, null, 2));

  } catch (err) {
    console.error('❌ Error:', err.response ? JSON.stringify(err.response.data, null, 2) : err.message);
  }
}

testCreateHPI();
