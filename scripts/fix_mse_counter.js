const mongoose = require('mongoose');
require('dotenv').config();

const MONGO_URI = process.env.MONGO_URI || 'mongodb://localhost:27017/mental_health_db';

async function fixCounter() {
    try {
        await mongoose.connect(MONGO_URI);
        console.log('Connected to MongoDB');

        const MSE = mongoose.model('MSE', new mongoose.Schema({ mseId: Number }));
        const Counter = mongoose.model('Counter', new mongoose.Schema({ _id: String, seq: Number }));

        const maxMse = await MSE.findOne().sort({ mseId: -1 });
        const maxId = maxMse ? maxMse.mseId : 0;
        console.log(`Max MSE ID found: ${maxId}`);

        const counter = await Counter.findByIdAndUpdate(
            { _id: 'mseId' },
            { $set: { seq: maxId } },
            { new: true, upsert: true }
        );
        console.log(`Counter 'mseId' updated to: ${counter.seq}`);

        await mongoose.connection.close();
        console.log('Done');
    } catch (err) {
        console.error(err);
        process.exit(1);
    }
}

fixCounter();
