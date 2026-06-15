const mongoose = require('mongoose');
const config = require('./config');

const connectDB = async () => {
    try {
        console.log(`[DEBUG] Connecting to: ${config.MONGO_URI}`);
        const conn = await mongoose.connect(config.MONGO_URI, { serverSelectionTimeoutMS: 30000, socketTimeoutMS: 30000 });
        console.log(`MongoDB Connected: ${conn.connection.host}`);
    } catch (error) {
        console.warn(`Primary connection failed: ${error.message}. Attempting fallback connection.`);
        // Fallback: replace '+srv' with standard host format and add default port
        const fallbackUri = config.MONGO_URI.replace('+srv', '').replace('mongodb://', 'mongodb://');
        try {
            const conn = await mongoose.connect(fallbackUri, { serverSelectionTimeoutMS: 30000, socketTimeoutMS: 30000 });
            console.log(`MongoDB Connected (fallback): ${conn.connection.host}`);
        } catch (fallbackError) {
            console.error(`Fallback connection error: ${fallbackError.message}`);
            process.exit(1);
        }
    }
};

module.exports = connectDB;
