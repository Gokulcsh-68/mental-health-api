const axios = require('axios');
require('dotenv').config();

const API_KEY = process.env.API_KEY || 'ygXk1R15vil+RD9Ix5c4cUPqND5i7+M3NRsEmxByDL8=';
const BASE_URL = 'http://localhost:5000/api/v1';

async function testTwoStepHPI() {
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

    // 2. Step 1: Extract Preview
    console.log('Step 1: Extracting HPI Preview...');
    const extractRes = await axios.post(`${BASE_URL}/hpis/extract`, {
      patient_id: 4, // Karthik
      narrative: "Patient reports persistent low mood for 2 months, worsening over the last 2 weeks. Includes severe insomnia and loss of appetite."
    }, {
      headers: {
        'x-api-key': API_KEY,
        'Authorization': `Bearer ${token}`
      }
    });

    const previewData = extractRes.data.data;
    console.log('✅ Extraction Complete. Preview:', JSON.stringify(previewData, null, 2));

    // 3. Step 2: Confirm & Save
    console.log('Step 2: Confirming and Saving HPI...');
    // We enrich the preview data with consult_id and potentially manual edits (here we just save it)
    const confirmPayload = {
      patient_id: 4,
      consult_id: 2002,
      ...previewData
    };

    const confirmRes = await axios.post(`${BASE_URL}/hpis`, confirmPayload, {
      headers: {
        'x-api-key': API_KEY,
        'Authorization': `Bearer ${token}`
      }
    });

    console.log('✅ HPI Saved Successfully:', JSON.stringify(confirmRes.data, null, 2));

  } catch (err) {
    console.error('❌ Error:', err.response ? JSON.stringify(err.response.data, null, 2) : err.message);
  }
}

testTwoStepHPI();
