const mongoose = require('mongoose');
require('dotenv').config();

const MONGO_URI = process.env.MONGO_URI || 'mongodb://localhost:27017/mental_health_db';

async function fixCounters() {
    try {
        await mongoose.connect(MONGO_URI);
        console.log('Connected to MongoDB');

        const Counter = mongoose.model('Counter', new mongoose.Schema({ _id: String, seq: Number }));

        // 1. Fix MSE Counter
        const MSE = mongoose.model('MSE', new mongoose.Schema({ mseId: Number }));
        const maxMse = await MSE.findOne().sort({ mseId: -1 });
        const maxMseId = maxMse ? maxMse.mseId : 0;
        console.log(`Max MSE ID found: ${maxMseId}`);
        await Counter.findByIdAndUpdate({ _id: 'mseId' }, { $set: { seq: maxMseId } }, { upsert: true });
        console.log(`Counter 'mseId' synchronized to ${maxMseId}`);

        // 2. Fix HistoryOfIllness Counter
        const HOI = mongoose.model('HistoryOfIllness', new mongoose.Schema({ historyOfIllnessId: Number }));
        const maxHoi = await HOI.findOne().sort({ historyOfIllnessId: -1 });
        const maxHoiId = maxHoi ? maxHoi.historyOfIllnessId : 0;
        console.log(`Max HistoryOfIllness ID found: ${maxHoiId}`);
        await Counter.findByIdAndUpdate({ _id: 'historyOfIllnessId' }, { $set: { seq: maxHoiId } }, { upsert: true });
        console.log(`Counter 'historyOfIllnessId' synchronized to ${maxHoiId}`);

        await mongoose.connection.close();
        console.log('Done');
    } catch (err) {
        console.error(err);
        process.exit(1);
    }
}

fixCounters();
