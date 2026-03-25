const Question = require('../../src/models/Question');
const Master = require('../../src/models/Master');
const StandardizedScore = require('../../src/models/StandardizedScore');

const adhdQuestions = [
    { 
        text: "How often do you have trouble wrapping up the final details of a project, once the challenging parts have been done?", 
        patientText: "How often do you find it hard to finish the last small steps of a task after the hard part is over?",
        professionalText: "Difficulty completing task finalization; potential impairment in executive functioning/closure.",
        threshold: "sometimes" 
    },
    { 
        text: "How often do you have difficulty getting things in order when you have to do a task that requires organization?", 
        patientText: "How often do you struggle to get organized when you have a job to do?",
        professionalText: "Deficits in organizational skills; difficulty with complex task planning and execution.",
        threshold: "sometimes" 
    },
    { 
        text: "How often do you have problems remembering appointments or obligations?", 
        patientText: "How often do you forget about meetings, appointments, or things you promised to do?",
        professionalText: "Impairment in prospective memory; frequent forgetting of scheduled obligations.",
        threshold: "sometimes" 
    },
    { 
        text: "When you have a task that requires a lot of thought, how often do you avoid or delay getting started?", 
        patientText: "How often do you put off starting a task that seems like it will take a lot of mental effort?",
        professionalText: "Task avoidance or procrastination regarding mentally taxing activities.",
        threshold: "often" 
    },
    { 
        text: "How often do you fidget or squirm with your hands or feet when you have to sit down for a long time?", 
        patientText: "How often do you find it hard to keep still, like tapping your feet or moving in your chair, when sitting for a while?",
        professionalText: "Presence of psychomotor restlessness or fidgeting behaviors.",
        threshold: "often" 
    },
    { 
        text: "How often do you feel overly active and compelled to do things, like you were driven by a motor?", 
        patientText: "How often do you feel like you have too much energy and just have to keep moving, like you're 'driven by a motor'?",
        professionalText: "Subjective experience of internal restlessness; hyperactivity consistent with ADHD (motor-driven).",
        threshold: "often" 
    },
    { 
        text: "How often do you make careless mistakes when you have to work on a boring or difficult project?", 
        patientText: "How often do you miss small details or make mistakes during boring or hard work?",
        professionalText: "Inattentiveness leading to careless errors during sustained mental effort.",
        threshold: "often" 
    },
    { 
        text: "How often do you have difficulty keeping your attention when you are doing boring or repetitive work?", 
        patientText: "How often does your mind wander when you're doing something repetitive or boring?",
        professionalText: "Difficulty sustaining attention during non-stimulating or repetitive tasks.",
        threshold: "often" 
    },
    { 
        text: "How often do you have difficulty concentrating on what people say to you, even when they are speaking to you directly?", 
        patientText: "How often do you feel like you aren't really listening or hearing someone when they are talking right to you?",
        professionalText: "Impairment in active listening; patient appears inattentive even without clear external distractors.",
        threshold: "sometimes" 
    },
    { 
        text: "How often do you misplace or have difficulty finding things at home or at work?", 
        patientText: "How often do you lose track of things like keys, your phone, or important papers?",
        professionalText: "Frequent loss of items necessary for tasks or activities.",
        threshold: "sometimes" 
    },
    { 
        text: "How often are you distracted by activity or noise around you?", 
        patientText: "How often do small noises or things happening nearby pull your attention away from what you're doing?",
        professionalText: "High distractibility by external, irrelevant stimuli.",
        threshold: "often" 
    },
    { 
        text: "How often do you leave your seat in meetings or other situations in which you are expected to remain seated?", 
        patientText: "How often do you get up and walk around when you're supposed to stay in your seat?",
        professionalText: "Difficulty remaining seated in situations where sedentary behavior is expected.",
        threshold: "sometimes" 
    },
    { 
        text: "How often do you feel restless or fidgety?", 
        patientText: "How often do you feel like you can't be still or just feel 'antsy' inside?",
        professionalText: "General psychomotor restlessness reported.",
        threshold: "often" 
    },
    { 
        text: "How often do you have difficulty unwinding and relaxing when you have time to yourself?", 
        patientText: "How often is it hard for you to just relax and do nothing when you have free time?",
        professionalText: "Impaired ability to achieve relaxation; persistent state of high arousal.",
        threshold: "often" 
    },
    { 
        text: "How often do you find yourself talking too much when you are in social situations?", 
        patientText: "How often do you feel like you talk more than you should or dominant the conversation?",
        professionalText: "Excessive talkativeness; social impulsivity marker.",
        threshold: "often" 
    },
    { 
        text: "When you’re in a conversation, how often do you find yourself finishing the sentences of the people you are talking to, before they can finish them themselves?", 
        patientText: "How often do you finish people's sentences for them before they can get the words out?",
        professionalText: "Impulsive interruption; difficulty waiting for conversational turns.",
        threshold: "sometimes" 
    },
    { 
        text: "How often do you have difficulty waiting your turn in situations when turn taking is required?", 
        patientText: "How often do you find it really hard to wait in line or wait for your turn?",
        professionalText: "Impaired behavioral inhibition; significant difficulty with delayed gratification/waiting.",
        threshold: "sometimes" 
    },
    { 
        text: "How often do you interrupt others when they are busy?", 
        patientText: "How often do you jump in and interrupt others while they are in the middle of a task or talking?",
        professionalText: "Intrusive behavior; frequent interruption of others' activities.",
        threshold: "sometimes" 
    }
];

const getOptions = (threshold) => {
    return [
        { text: "Never", score: 0 },
        { text: "Rarely", score: 0 },
        { text: "Sometimes", score: threshold === 'sometimes' ? 1 : 0 },
        { text: "Often", score: 1 },
        { text: "Very Often", score: 1 }
    ];
};

const seedADHD = async () => {
    try {
        const master = await Master.findOne({ slug: 'adhd' });
        const masterId = master ? master._id : null;
        const masterSlug = master ? master.slug : 'adhd';

        await Question.deleteMany({ category: masterSlug });
        await StandardizedScore.deleteMany({ category: masterSlug });

        const questionsToSeed = adhdQuestions.map(q => ({
            text: q.text,
            category: masterSlug,
            type: "scale",
            minAge: 7,
            maxAge: 120,
            gender: "all",
            uiType: "radio",
            options: getOptions(q.threshold),
            master: masterId
        }));

        for (const q of questionsToSeed) {
            await Question.create(q);
        }
        console.log(`  ✅ ${questionsToSeed.length} ADHD questions seeded`);

        const scoresToSeed = [];
        // Total score range 0-18
        for (let raw = 0; raw <= 18; raw++) {
            let interpretation = "Low likelihood of ADHD";
            if (raw >= 4) {
                interpretation = "Symptoms consistent with ADHD - Professional evaluation recommended";
            }

            scoresToSeed.push({
                category: masterSlug,
                master: masterId,
                rawScore: raw,
                tScore: (raw / 18) * 100, // Visual percentage
                standardError: 0,
                interpretation: interpretation
            });
        }

        await StandardizedScore.insertMany(scoresToSeed);
        console.log(`  📊 ${scoresToSeed.length} ADHD interpretation records seeded`);

    } catch (err) {
        console.error('  ❌ ADHD Seeder Error:', err.message);
        throw err;
    }
};

module.exports = seedADHD;
