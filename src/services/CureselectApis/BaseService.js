const axios = require('axios');
const dotenv = require('dotenv');
const logger = require('../../config/logger');

dotenv.config();

class BaseService {
    constructor() {
        this._base_url = process.env.CURESELECT_API_ENDPOINT;
        this._client_id = process.env.CURESELECT_API_CLIENT_ID;
        this._client_secret = process.env.CURESELECT_API_CLIENT_SECRET;
        this._token = null;
        this._token_expiry = null;
    }

    async getToken() {
        // If token exists and is not expired (using a 23h buffer like PHP's 1380 minutes)
        if (this._token && this._token_expiry && Date.now() < this._token_expiry) {
            return this._token;
        }

        const success = await this.authenticate();
        if (success) {
            return this._token;
        }
        return null;
    }

    async authenticate() {
        if (!this._base_url || !this._client_id || !this._client_secret) {
            const error_message = 'Please check values are assigned to following variables in env file. The variables are CURESELECT_API_ENDPOINT, CURESELECT_API_CLIENT_ID, CURESELECT_API_CLIENT_SECRET';
            throw new Error(error_message);
        }

        const url = `${this._base_url}v1/users/authenticate/api`;

        const data = {
            client_id: this._client_id,
            client_secret: this._client_secret
        };

        try {
            logger.info(`Authenticating with Cureselect: ${url}`);
            const response = await axios.post(url, data, {
                headers: {
                    'Content-Type': 'application/json'
                }
            });

            logger.info('Authentication Response Status: %d', response.status);
            if (response.status === 200 && (response.data.code == 200 || response.data.code == '200')) {
                let token = response.headers['authorization'] || response.headers['Authorization'];

                // If the token starts with 'Bearer ', strip it so we can consistently add it later
                if (token && token.startsWith('Bearer ')) {
                    token = token.substring(7);
                }

                this._token = token;
                logger.info('Token acquired successfully');

                // Set expiry for 1380 minutes (23 hours)
                this._token_expiry = Date.now() + 1380 * 60 * 1000;

                return this._token;
            } else {
                logger.error('Authentication failed with unexpected data:', response.data);
            }
        } catch (error) {
            logger.error('CURESELECT AUTH ERROR: %O', error.response ? error.response.data : error.message);
            return false;
        }

        return false;
    }

    // Helper to extract headers if needed (mirroring PHP method for completeness, though axios handles it)
    get_headers_from_response(response) {
        return response.headers;
    }
}

module.exports = BaseService;
