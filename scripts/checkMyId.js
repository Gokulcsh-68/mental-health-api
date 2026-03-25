const axios = require('axios');

const BASE_URL = 'https://mental-health-api-rke4.onrender.com/api/v1';
const API_KEY = 'ygXk1R15vil+RD9Ix5c4cUPqND5i7+M3NRsEmxByDL8=';

async function check() {
  const headers = {
    'Content-Type': 'application/json',
    'x-api-key': API_KEY
  };

  try {
    // 1. Login as Therapist
    console.log('Logging in as therapist...');
    const profLogin = await axios.post(`${BASE_URL}/auth/login`, {
      username: 'drvenkatesh',
      password: 'AdminPassword@123',
      role: 'psychiatrist'
    }, { headers });
    
    const user = profLogin.data.data.user;
    console.log('✅ Logged in user profile:');
    console.log('   _id:', user._id || user.id);
    console.log('   userId:', user.userId);
    console.log('   role:', user.role);

  } catch (error) {
    console.error('❌ Check failed:', error.response ? error.response.data : error.message);
  }
}

check();
