const axios = require('axios');
const BaseService = require('./BaseService');
const logger = require('../../config/logger');

class TeleConsultApiService extends BaseService {
    constructor() {
        super();
        this.endpoint_url = `${this._base_url}v1/resource/consults`;
        this.teleconsult_url = `${this._base_url}v1/consults/token-validate`;
        this.default_virtual_service_provider = 'tokbox';
        this.lastResponse = null;
    }

    formatDate(date) {
        const dateObj = new Date(date);
        return dateObj.getFullYear() + '-' +
            String(dateObj.getMonth() + 1).padStart(2, '0') + '-' +
            String(dateObj.getDate()).padStart(2, '0') + ' ' +
            String(dateObj.getHours()).padStart(2, '0') + ':' +
            String(dateObj.getMinutes()).padStart(2, '0') + ':' +
            String(dateObj.getSeconds()).padStart(2, '0');
    }

    async consultCreateValidate(payload) {
        const requiredFields = ['consult_date_time', 'consult_reason', 'consult_type', 'provider.id', 'provider.name', 'patient.id', 'patient.name'];
        for (const field of requiredFields) {
            const keys = field.split('.');
            let value = payload;
            for (const key of keys) {
                if (value[key] === undefined || value[key] === null) {
                    throw new Error(`Validation failed: ${field} is required`);
                }
                value = value[key];
            }
        }

        if (!['virtual', 'home', 'clinic'].includes(payload.consult_type)) {
            throw new Error('Validation failed: consult_type must be virtual, home, or clinic');
        }
    }

    processUserData(data, role) {
        const user = {
            role: role,
            ref_number: String(data.id || ''),
            participant_info: {
                name: data.name || null,
                email: data.email || null,
                phone: data.phone || null,
                gender: data.gender || null,
                profile_pic: data.profile_pic || null
            }
        };

        if (data.additional_info) {
            user.participant_info.additional_info = data.additional_info;
        }

        return user;
    }

    async apiCall(url, options, method = 'POST') {
        try {
            const config = {
                method: method.toLowerCase(),
                url: url,
                headers: options.headers || {},
                params: options.query || {},
                data: options.body || null,
                timeout: 10000 // 10 second timeout to prevent 504 hanging
            };

            logger.info(`Cureselect API Call: ${method} ${url}`);
            const response = await axios(config);
            this.lastResponse = response.data;
            logger.info('Cureselect API Response Success');
            return response.data;
        } catch (error) {
            this.lastResponse = error.response ? error.response.data : { message: error.message };
            logger.error('Cureselect API Call Error Payload: %O', options.body);
            logger.error('Cureselect API Call Error Response: %O', this.lastResponse);
            throw error;
        }
    }

    async create(payload) {
        await this.consultCreateValidate(payload);

        const provider_data = payload.provider;
        const patient_data = payload.patient;
        const consult_type = payload.consult_type;
        const consult_reason = payload.consult_reason;
        const consult_date_time = payload.consult_date_time;
        const service_provider = payload.service_provider || this.default_virtual_service_provider;
        const consult_additional_info = payload.additional_info || null;

        try {
            const url = this.endpoint_url;
            const formattedDate = this.formatDate(consult_date_time);

            const form_data = {
                scheduled_at: formattedDate,
                consult_type: consult_type,
                reason: consult_reason,
                virtual_service_provider: service_provider,
                participants: [
                    this.processUserData(provider_data, 'publisher'),
                    this.processUserData(patient_data, 'subscriber')
                ],
                additional_info: consult_additional_info
            };

            if (payload.consult_status) {
                form_data.consult_status = payload.consult_status;
            }

            const token = await this.getToken();
            const headers = {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            };

            const options = {
                headers: headers,
                body: form_data
            };

            await this.apiCall(url, options, 'POST');
            const api_response = this.lastResponse;

            return { consult_id: api_response.data.consults.id };
        } catch (error) {
            logger.error('Cureselect Teleconsult API ERROR: %s', error.message);
            if (this.lastResponse && (this.lastResponse.code == 422 || this.lastResponse.code == '422')) {
                throw new Error(JSON.stringify(this.lastResponse.data));
            }
            throw error;
        }
    }

    async fetch(params = {}, per_page = 10, page_number = 1) {
        const allowedParams = ['participant_ref_number', 'consult_status_id', 'consult_type', 'consult_status', 'scheduled_from_date', 'scheduled_to_date', 'consult_id', 'category_id', 'x_name'];
        const filteredParams = {};
        for (const key of allowedParams) {
            if (params[key] !== undefined) filteredParams[key] = params[key];
        }

        const queryParams = { ...filteredParams, limit: per_page, page: page_number };

        // Ensure participant_ref_number is an array if present, as the remote Laravel API 
        // expects an array for cleanBindings when using whereIn
        if (queryParams.participant_ref_number && !Array.isArray(queryParams.participant_ref_number)) {
            queryParams.participant_ref_number = [queryParams.participant_ref_number];
        }
        const token = await this.getToken();
        const headers = {
            'Authorization': `Bearer ${token}`
        };

        const options = {
            headers: headers,
            query: queryParams
        };

        try {
            const url = this.endpoint_url;
            logger.info(`Fetching consults from: ${url} with final query params: ${JSON.stringify(queryParams)}`);
            await this.apiCall(url, options, 'GET');

            if (this.lastResponse && this.lastResponse.data && this.lastResponse.data.pagination) {
                logger.info(`API Response Pagination: ${JSON.stringify(this.lastResponse.data.pagination)}`);
            }
            return this.lastResponse;
        } catch (error) {
            console.error('Cureselect Teleconsult API ERROR ------- ', error.message);
            throw error;
        }
    }

    async fetchById(consult_id, params = {}) {
        const allowedParams = ['participant_ref_number', 'consult_status_id', 'consult_type', 'consult_status', 'scheduled_from_date', 'scheduled_to_date', 'consult_id'];
        const filteredParams = {};
        for (const key of allowedParams) {
            if (params[key] !== undefined) filteredParams[key] = params[key];
        }

        const token = await this.getToken();
        const headers = {
            'Authorization': `Bearer ${token}`
        };

        const options = {
            headers: headers,
            query: filteredParams
        };

        try {
            const url = `${this.endpoint_url}/${consult_id}`;
            await this.apiCall(url, options, 'GET');
            return this.lastResponse;
        } catch (error) {
            console.error('Cureselect Teleconsult API ERROR ------- ', error.message);
            throw error;
        }
    }

    async patch(data) {
        try {
            const url = `${this.endpoint_url}/${data.id}`;
            const { id, ...payload } = data;
            
            // Filter out undefined values from payload
            const form_data = {};
            Object.keys(payload).forEach(key => {
                if (payload[key] !== undefined) {
                    form_data[key] = payload[key];
                }
            });

            // Format scheduled_at if present
            if (data.scheduled_at) {
                form_data.scheduled_at = this.formatDate(data.scheduled_at);
            }

            const token = await this.getToken();
            const headers = {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            };

            const options = {
                headers: headers,
                body: form_data
            };

            await this.apiCall(url, options, 'PATCH');
            const api_response = this.lastResponse;

            // Handle cases where the remote response might be malformed or missing data
            if (!api_response || !api_response.data || !api_response.data.consults) {
                logger.warn('Remote patch response missing consult data:', api_response);
                return { consult_id: data.id };
            }

            return { consult_id: api_response.data.consults.id };
        } catch (error) {
            const errorMsg = error.response ? JSON.stringify(error.response.data) : error.message;
            logger.error(`Cureselect Teleconsult API PATCH ERROR for ID ${data.id}: ${errorMsg}`);
            throw error;
        }
    }

    async consultDetails(request) {
        try {
            const token = await this.getToken();
            const headers = {
                'Authorization': `Bearer ${token}`
            };

            const options = {
                headers: headers
            };

            const url = `${this.teleconsult_url}?token=${request.token}`;
            await this.apiCall(url, options, 'GET');
            return this.lastResponse;
        } catch (error) {
            console.error('Cureselect Teleconsult API ERROR ------- ', error.message);
            throw error;
        }
    }
}

module.exports = TeleConsultApiService;
