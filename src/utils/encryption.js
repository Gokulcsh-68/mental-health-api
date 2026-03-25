const crypto = require('crypto');
const config = require('../config/config');

const algorithm = 'aes-256-gcm';
const ivLength = 12;
const tagLength = 16;
const key = crypto.scryptSync(process.env.JWT_SECRET || 'fallback-secret', 'salt', 32);

/**
 * Encrypts sensitive text using AES-256-GCM
 * @param {string} text 
 * @returns {string} Encrypted text in format: iv:content:tag
 */
const encrypt = (text) => {
    if (!text) return text;

    const iv = crypto.randomBytes(ivLength);
    const cipher = crypto.createCipheriv(algorithm, key, iv);

    let encrypted = cipher.update(text, 'utf8', 'hex');
    encrypted += cipher.final('hex');

    const tag = cipher.getAuthTag().toString('hex');

    return `${iv.toString('hex')}:${encrypted}:${tag}`;
};

/**
 * Decrypts text encrypted by the above method
 * @param {string} encryptedText 
 * @returns {string} Decrypted text
 */
const decrypt = (encryptedText) => {
    if (!encryptedText || !encryptedText.includes(':')) return encryptedText;

    try {
        const [ivHex, encrypted, tagHex] = encryptedText.split(':');

        const iv = Buffer.from(ivHex, 'hex');
        const tag = Buffer.from(tagHex, 'hex');
        const decipher = crypto.createDecipheriv(algorithm, key, iv);

        decipher.setAuthTag(tag);

        let decrypted = decipher.update(encrypted, 'hex', 'utf8');
        decrypted += decipher.final('utf8');

        return decrypted;
    } catch (err) {
        console.error('[EncryptionUtil] Decryption failed:', err.message);
        return encryptedText; // Fallback to original text if decryption fails
    }
};

module.exports = {
    encrypt,
    decrypt
};
