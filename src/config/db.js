const mongoose = require('mongoose');
const config = require('./config');

const connectDB = async () => {
    try {
        console.log(`[DEBUG] Connecting to: ${config.MONGO_URI}`);
        const conn = await mongoose.connect(config.MONGO_URI);

        console.log(`MongoDB Connected: ${conn.connection.host}`);
    } catch (error) {
        console.error(`Error: ${error.message}`);
        process.exit(1);
    }
};

module.exports = connectDB;
