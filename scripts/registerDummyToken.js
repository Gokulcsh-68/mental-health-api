const axios = require('axios');

const BASE_URL = 'https://mental-health-api-rke4.onrender.com/api/v1';
const API_KEY = 'ygXk1R15vil+RD9Ix5c4cUPqND5i7+M3NRsEmxByDL8=';

async function register() {
  const headers = {
    'Content-Type': 'application/json',
    'x-api-key': API_KEY
  };

  try {
    // 1. Login as Patient
    console.log('Logging in as patient...');
    const patLogin = await axios.post(`${BASE_URL}/auth/login`, {
      username: 'karthik',
      password: 'AdminPassword@123',
      role: 'patient'
    }, { headers });
    const patToken = patLogin.data.data.token;
    console.log('✅ Patient logged in');

    // 2. Register Dummy FCM Token
    console.log('Registering dummy FCM token...');
    const updateHeaders = { ...headers, Authorization: `Bearer ${patToken}` };
    const updateRes = await axios.put(`${BASE_URL}/users/update-me`, {
      fcmTokens: ['dummy_token_' + Date.now()]
    }, { headers: updateHeaders });

    if (updateRes.data.data.fcmTokens.length > 0) {
      console.log('✅ Dummy token registered:', updateRes.data.data.fcmTokens);
    } else {
      console.log('❌ Failed to register token');
    }

  } catch (error) {
    console.error('❌ Registration failed:', error.response ? error.response.data : error.message);
  }
}

register();
