const TeleConsultApiService = require('./src/services/CureselectApis/TeleConsultApiService');
const dotenv = require('dotenv');
dotenv.config();

async function test() {
    const service = new TeleConsultApiService();
    const params = {
        x_name: 'mental health',
        participant_ref_number: ['cae8c49f-8cb8-43d4-ae77-9af9d92219ab']
    };

    try {
        console.log('Attempting to fetch consults for mental health...');
        const res = await service.fetch(params);
        console.log('SUCCESS:', JSON.stringify(res, null, 2));
    } catch (err) {
        console.error('FAILED:', err.message);
    }
}

test();
