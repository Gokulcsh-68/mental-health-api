const multer = require('multer');
const path = require('path');

// Multer in-memory storage for images (to be uploaded to S3 manually if needed)
// or just using S3Service.upload if we want direct S3 streaming.

const storage = multer.memoryStorage();

const imageFilter = (req, file, cb) => {
    if (file.mimetype.startsWith('image/')) {
        cb(null, true);
    } else {
        cb(new Error('Please upload only images.'), false);
    }
};

const uploadProfileImage = multer({
    storage: storage,
    fileFilter: imageFilter,
    limits: { fileSize: 5 * 1024 * 1024 } // 5MB limit
});

module.exports = {
    uploadProfileImage
};
