const { S3Client, PutObjectCommand } = require('@aws-sdk/client-s3');
const multer = require('multer');
const multerS3 = require('multer-s3');
const config = require('../config/config');
const path = require('path');
const logger = require('../config/logger');
const axios = require('axios');

// ── 1. Initialize S3 Client ─────────────────────────────────
let s3;
try {
    if (!config.S3.KEY || !config.S3.SECRET) {
        logger.warn('S3 credentials are missing. Uploads will fail.');
    }
    s3 = new S3Client({
        credentials: {
            accessKeyId: config.S3.KEY || 'MISSING',
            secretAccessKey: config.S3.SECRET || 'MISSING'
        },
        region: config.S3.REGION || 'ap-south-1'
    });
} catch (error) {
    logger.error('Failed to initialize S3 Client:', error.message);
}

// ── 2. Configure Multer Storage (Multipart Form Data) ───────
const upload = multer({
    storage: multerS3({
        s3: s3,
        bucket: config.S3.BUCKET,
        acl: 'public-read',
        contentType: multerS3.AUTO_CONTENT_TYPE,
        key: function (req, file, cb) {
            const fileName = `${Date.now()}-${Math.round(Math.random() * 1E9)}${path.extname(file.originalname)}`;
            const fullPath = `${config.S3.BASE_PATH}${fileName}`;
            cb(null, fullPath);
        }
    }),
    fileFilter: (req, file, cb) => {
        if (file.mimetype.startsWith('audio/') || file.mimetype.startsWith('image/')) {
            cb(null, true);
        } else {
            cb(new Error('Invalid file type. Only audio and image files are allowed.'), false);
        }
    },
    limits: {
        fileSize: 50 * 1024 * 1024 // 50MB limit
    }
});

/**
 * @desc    Upload an image from a URL to S3
 * @param   {string} url - External image URL
 * @returns {string} - Public S3 URL
 */
const uploadFromUrl = async (url) => {
    try {
        const response = await axios.get(url, { responseType: 'arraybuffer' });
        
        const fileName = `profile-${Date.now()}-${Math.round(Math.random() * 1E9)}${path.extname(url.split('?')[0]) || '.jpg'}`;
        const fullPath = `${config.S3.BASE_PATH}${fileName}`;

        const command = new PutObjectCommand({
            Bucket: config.S3.BUCKET,
            Key: fullPath,
            Body: response.data,
            ACL: 'public-read',
            ContentType: response.headers['content-type'] || 'image/jpeg'
        });

        await s3.send(command);
        
        // Return the public URL
        if (config.S3.PUBLIC_BASE_PATH) {
            const cleanBasePath = config.S3.PUBLIC_BASE_PATH.endsWith('/') 
                ? config.S3.PUBLIC_BASE_PATH 
                : `${config.S3.PUBLIC_BASE_PATH}/`;
            return `${cleanBasePath}${fileName}`;
        }
        return `https://${config.S3.BUCKET}.s3.${config.S3.REGION || 'ap-south-1'}.amazonaws.com/${fullPath}`;
    } catch (error) {
        logger.error('S3 URL Upload Error: %s', error.message);
        throw error;
    }
};

const memoryUpload = multer({
    storage: multer.memoryStorage(),
    fileFilter: (req, file, cb) => {
        if (file.mimetype.startsWith('audio/')) {
            cb(null, true);
        } else {
            cb(new Error('Invalid file type. Only audio files are allowed.'), false);
        }
    },
    limits: {
        fileSize: 25 * 1024 * 1024 // 25MB limit for transcription
    }
});

module.exports = {
    s3,
    upload,
    memoryUpload,
    uploadFromUrl
};
