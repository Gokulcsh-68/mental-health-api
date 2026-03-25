const axios = require('axios');
require('dotenv').config();

const API_KEY = process.env.API_KEY || 'ygXk1R15vil+RD9Ix5c4cUPqND5i7+M3NRsEmxByDL8=';
const BASE_URL = 'http://localhost:5000/api/v1';

async function testCreateConsult() {
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

    // 2. Create Consult
    console.log('Creating consult...');
    const scheduled_at = new Date();
    scheduled_at.setHours(scheduled_at.getHours() + 24); // Tomorrow
    
    const payload = {
      scheduled_at: scheduled_at.toISOString(),
      participants: [
        {
          participant_type: { code: 'professional' },
          ref_number: 3, // drvenkatesh
          name: 'Dr. Venkatesh'
        },
        {
          participant_type: { code: 'patient' },
          ref_number: 4, // karthik
          name: 'Karthik'
        }
      ],
      reason: 'Test Consultation',
      consult_type: 'virtual'
    };

    const createRes = await axios.post(`${BASE_URL}/resource/consults`, payload, {
      headers: {
        'x-api-key': API_KEY,
        'Authorization': `Bearer ${token}`
      }
    });

    console.log('✅ Consult Created:', JSON.stringify(createRes.data, null, 2));
    
    const consultId = createRes.data.data.consult_id;
    console.log('✅ Successfully created consult with ID:', consultId);

  } catch (err) {
    console.error('❌ Error:', err.response ? JSON.stringify(err.response.data, null, 2) : err.message);
  }
}

testCreateConsult();
