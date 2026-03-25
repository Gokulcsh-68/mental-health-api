// Server Entry Point
const app = require('./app');
const config = require('./config/config');
const connectDB = require('./config/db');

const PORT = config.PORT || 5000;

const startServer = async () => {
    // Connect to database
    await connectDB();

    // Initialize System Settings
    const { initializeDefaultSettings } = require('./controllers/systemSetting.controller');
    await initializeDefaultSettings();

    const http = require('http');
    const server = http.createServer(app);

    server.listen(PORT, () => {
        console.log(`Server running in ${config.NODE_ENV} mode on port ${PORT}`);
    });

    // Initialize Socket.io
    const socketService = require('./services/socketService');
    socketService.init(server);

    // Initialize Timed Notifications (Cron Jobs)
    const timedNotificationService = require('./services/TimedNotificationService');
    timedNotificationService.init();

    // Handle unhandled promise rejections
    process.on('unhandledRejection', (err, promise) => {
        console.log(`Error: ${err.message}`);
        // Close server & exit process
        server.close(() => process.exit(1));
    });
};

startServer();