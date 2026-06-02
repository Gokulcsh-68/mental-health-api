# Mental Health API

This is a RESTful API built with Node.js and Express for managing mental health records, clinical summaries, user authentication, and specialist schedules. It uses MongoDB for data storage and includes integrations with various external services.

## 🚀 Getting Started

Follow these instructions to get a copy of the project up and running on your local machine for development and testing purposes.

### Prerequisites

You need to have the following installed on your machine:
- [Node.js](https://nodejs.org/) (v14 or higher recommended)
- [MongoDB](https://www.mongodb.com/) (Local instance or Atlas URL)
- [Git](https://git-scm.com/)

### 📦 1. Setting Up from Git

1. **Clone the repository:**
   ```bash
   git clone <repository-url>
   cd "Mental Health/Api"
   ```

2. **Install dependencies:**
   ```bash
   npm install
   ```

3. **Configure Environment Variables:**
   Create a `.env` file in the root directory (if it doesn't already exist) and populate it with the necessary variables. Ensure you have the following bare minimum variables:

   ```env
   PORT=5000
   NODE_ENV=development
   MONGO_URI=mongodb://localhost:27017/mental_health_db
   JWT_SECRET=your_jwt_secret
   API_KEY=your_api_key
   ```

### 🗄️ 2. Database Setup

The project uses **MongoDB** as its primary database and `mongoose` for object data modeling (ODM).

1. **Start MongoDB:**
   Ensure your local MongoDB server is running:
   ```bash
   mongod
   ```
   *Note: If you are using MongoDB Atlas, ensure your `MONGO_URI` in the `.env` file points to your cloud instance.*

2. **Seed the Database (Optional but recommended):**
   The project includes seeder scripts to populate the database with initial master data:
   ```bash
   npm run seed
   # or
   npm run runAllSeeders
   ```
   To reset the database during testing, you can run:
   ```bash
   npm run test:reset
   ```

### ⚙️ 3. How It Works

This is a modular REST API configured via Express.js. Here is the high-level architecture and execution flow:

1. **Server Initialization (`src/server.js`):**
   - The application entry point is `server.js`.
   - It establishes a connection to the MongoDB database using the configuration in `config/db.js`.
   - It starts up the Express server on the port defined in `.env` (default is 5000).

2. **Express App Configuration (`src/app.js`):**
   - **Logging & Security Middleware:** Applies global middlewares like `express.json()`, `morgan` for file-based access logging (`/logs/access.log`), and multiple security modules (`helmet`, `cors`, `xss-clean`, `express-rate-limit`, `hpp`).
   - **API Key Validation:** All `/api/v1/` routes enforce an API key check handled by the `validateApiKey` middleware. Requests missing a valid `x-api-key` header matching the `.env` configuration will be rejected.
   - **Modular Routing:** API routes are strictly grouped by domain context (e.g. `/api/v1/users`, `/api/v1/assessments`, `/api/v1/patients`) for easy codebase navigation and scaling.

3. **Running the Application:**

   To start the application in development mode (with hot reloading via `nodemon`):
   ```bash
   npm run dev
   ```

   To start the application for production:
   ```bash
   npm start
   ```

4. **Testing the API:**
   The API has built-in test suites. You can run them using:
   ```bash
   npm test
   # To test full end-to-end flows:
   npm run test:e2e
   ```


test