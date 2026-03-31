const axios = require('axios');
const FormData = require('form-data');
const fs = require('fs');
const path = require('path');
require('dotenv').config();

const API_BASE_URL = `http://127.0.0.1:5000/api/v1`;
const API_KEY = process.env.API_KEY || 'ygXk1R15vil+RD9Ix5c4cUPqND5i7+M3NRsEmxByDL8=';

/**
 * @desc    Dedicated test for AI Transcription endpoint
 *          Requirements: Local server must be running on port 5000
 */
async function runTranscriptionTest() {
    console.log('🚀 Starting AI Transcribe Test...');
    const tempAudioPath = path.join(__dirname, 'test_audio.wav');

    try {
        // 1. Generate a tiny valid silent WAV buffer (minimal header + 1kb silence)
        const wavHeader = Buffer.from([
            0x52, 0x49, 0x46, 0x46, 0x24, 0x00, 0x00, 0x00, 0x57, 0x41, 0x56, 0x45, 0x66, 0x6d, 0x74, 0x20,
            0x10, 0x00, 0x00, 0x00, 0x01, 0x00, 0x01, 0x00, 0x40, 0x1f, 0x00, 0x00, 0x40, 0x1f, 0x00, 0x00,
            0x01, 0x00, 0x08, 0x00, 0x64, 0x61, 0x74, 0x61, 0x00, 0x04, 0x00, 0x00
        ]);
        const silence = Buffer.alloc(1024, 128);
        const audioBuffer = Buffer.concat([wavHeader, silence]);

        // 2. Login to get token
        console.log('🔑 Authenticating as gokulgv...');
        const loginRes = await axios.post(`${API_BASE_URL}/auth/login`, {
            username: 'gokulgv',
            password: 'Gokul@123',
            role: 'super_admin'
        }, { headers: { 'x-api-key': API_KEY } });

        const token = loginRes.data.data.token;
        console.log('✅ Auth success. Token obtained.');

        // 3. Send to AI transcribe
        const form = new FormData();
        form.append('audio', audioBuffer, { 
            filename: 'test_audio.wav', 
            contentType: 'audio/wav' 
        });

        console.log('📡 Sending audio for transcription...');
        const transcribeRes = await axios.post(`${API_BASE_URL}/ai/transcribe`, form, {
            headers: {
                ...form.getHeaders(),
                'Authorization': `Bearer ${token}`,
                'x-api-key': API_KEY
            }
        });

        console.log('\n✨ AI Transcription result:', transcribeRes.data.data.text || '(Empty response)');

    } catch (err) {
        if (err.response) {
            console.error('❌ API Error:', JSON.stringify(err.response.data, null, 2));
        } else {
            console.error('❌ Test Failed:', err.message);
        }
    } finally {
        process.exit(0);
    }
}

runTranscriptionTest();
