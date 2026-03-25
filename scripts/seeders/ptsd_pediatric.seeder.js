const Question = require('../../src/models/Question');
const Master = require('../../src/models/Master');
const StandardizedScore = require('../../src/models/StandardizedScore');

const ptsdQuestions = [
    { 
        text: "Upsetting thoughts or pictures about what happened that pop into your head.", 
        patientText: "I have scary or upsetting pictures in my head about the bad thing that happened.",
        professionalText: "Presence of intrusive thoughts or mental imagery related to the traumatic event.",
        category: "ptsd_pediatric", type: "scale" 
    },
    { 
        text: "Bad dreams reminding you of what happened.", 
        patientText: "I have bad dreams or nightmares that remind me of what happened.",
        professionalText: "Traumatic nightmares or sleep disturbances with event-related content.",
        category: "ptsd_pediatric", type: "scale" 
    },
    { 
        text: "Feeling as if what happened is happening all over again.", 
        patientText: "Sometimes it feels like the bad thing is happening again right now.",
        professionalText: "Dissociative reactions (flashbacks) where the patient feels as if the trauma is recurring.",
        category: "ptsd_pediatric", type: "scale" 
    },
    { 
        text: "Feeling very upset when you are reminded of what happened.", 
        patientText: "I feel very sad, scared, or angry when something reminds me of that time.",
        professionalText: "Intense or prolonged psychological distress at exposure to internal or external cues.",
        category: "ptsd_pediatric", type: "scale" 
    },
    { 
        text: "Strong physical reactions when reminded of what happened (sweating, fast heartbeat, upset stomach).", 
        patientText: "My body feels weird, like my heart beats fast or my tummy hurts, when I think about what happened.",
        professionalText: "Marked physiological reactions to reminders of the traumatic event.",
        category: "ptsd_pediatric", type: "scale" 
    },
    { 
        text: "Trying not to think about or talk about what happened, or avoiding feelings about it.", 
        patientText: "I try really hard not to think or talk about what happened.",
        professionalText: "Efforts to avoid distressing memories, thoughts, or feelings about the trauma.",
        category: "ptsd_pediatric", type: "scale" 
    },
    { 
        text: "Staying away from people, places, things, or situations that remind you of what happened.", 
        patientText: "I stay away from people or places that make me remember what happened.",
        professionalText: "Efforts to avoid external reminders (people, places, conversations, activities, objects, situations).",
        category: "ptsd_pediatric", type: "scale" 
    },
    { 
        text: "Not being able to remember part of what happened.", 
        patientText: "I can't remember parts of what happened, even when I try.",
        professionalText: "Inability to remember an important aspect of the traumatic event (dissociative amnesia).",
        category: "ptsd_pediatric", type: "scale" 
    },
    { 
        text: "Negative thoughts about yourself or others (e.g., 'I won’t have a good life,' 'No one can be trusted,' 'The world is unsafe').", 
        patientText: "I often think bad things like 'I'm not a good person' or 'The world isn't safe'.",
        professionalText: "Persistent and exaggerated negative beliefs or expectations about oneself or the world.",
        category: "ptsd_pediatric", type: "scale" 
    },
    { 
        text: "Blaming yourself for what happened, or blaming someone else when it isn’t their fault.", 
        patientText: "I feel like it was my fault, or I blame others even when it wasn't their fault.",
        professionalText: "Persistent, distorted cognitions about the cause or consequences of the traumatic event leading to self-blame.",
        category: "ptsd_pediatric", type: "scale" 
    },
    { 
        text: "Having bad feelings such as fear, anger, guilt, or shame most of the time.", 
        patientText: "I feel scared, angry, or ashamed most of the time.",
        professionalText: "Persistent negative emotional state (e.g., fear, horror, anger, guilt, or shame).",
        category: "ptsd_pediatric", type: "scale" 
    },
    { 
        text: "Not wanting to do things you used to enjoy.", 
        patientText: "I don't feel like playing or doing the things I used to love doing.",
        professionalText: "Markedly diminished interest or participation in significant activities/play.",
        category: "ptsd_pediatric", type: "scale" 
    },
    { 
        text: "Not feeling close to other people.", 
        patientText: "I don't feel close to my friends or family like I used to.",
        professionalText: "Feelings of detachment or estrangement from others.",
        category: "ptsd_pediatric", type: "scale" 
    },
    { 
        text: "Not being able to have good or happy feelings.", 
        patientText: "It's really hard for me to feel happy or good about things.",
        professionalText: "Persistent inability to experience positive emotions (anhedonia).",
        category: "ptsd_pediatric", type: "scale" 
    },
    { 
        text: "Feeling angry, having outbursts, or taking anger out on others.", 
        patientText: "I get angry easily and sometimes yell or take it out on other people.",
        professionalText: "Irritable behavior and angry outbursts (with little or no provocation).",
        category: "ptsd_pediatric", type: "scale" 
    },
    { 
        text: "Doing unsafe or risky things.", 
        patientText: "I sometimes do things that aren't safe or take chances I shouldn't.",
        professionalText: "Reckless or self-destructive behavior.",
        category: "ptsd_pediatric", type: "scale" 
    },
    { 
        text: "Being overly alert or on guard (constantly checking who is around you).", 
        patientText: "I'm always looking around and checking if things are safe.",
        professionalText: "Hypervigilance; patient is constantly on guard for potential threats.",
        category: "ptsd_pediatric", type: "scale" 
    },
    { 
        text: "Being jumpy or easily startled.", 
        patientText: "I get scared or jumpy easily, like when I hear a loud noise.",
        professionalText: "Exaggerated startle response.",
        category: "ptsd_pediatric", type: "scale" 
    },
    { 
        text: "Problems with concentration or paying attention.", 
        patientText: "It's hard for me to pay attention or focus on my schoolwork or games.",
        professionalText: "Problems with concentration; difficulty sustaining attention on tasks.",
        category: "ptsd_pediatric", type: "scale" 
    },
    { 
        text: "Trouble falling asleep or staying asleep.", 
        patientText: "I have a hard time falling asleep or I wake up a lot during the night.",
        professionalText: "Sleep disturbance (e.g., difficulty falling or staying asleep, or restless sleep).",
        category: "ptsd_pediatric", type: "scale" 
    },

    // Interference Markers (Yes/No)
    { 
        text: "Has these problems interfered with getting along with others?", 
        patientText: "Do these feelings make it hard for you to get along with your friends or other kids?",
        professionalText: "Social impairment; trauma symptoms interfering with peer relationships.",
        category: "ptsd_pediatric", type: "boolean" 
    },
    { 
        text: "Has these problems interfered with your Hobbies/Fun?", 
        patientText: "Do these feelings stop you from having fun or doing your favorite hobbies?",
        professionalText: "Functional impairment in recreational/leisure activities.",
        category: "ptsd_pediatric", type: "boolean" 
    },
    { 
        text: "Has these problems interfered with School or work?", 
        patientText: "Do these feelings make it hard for you to do your schoolwork?",
        professionalText: "Academic/occupational impairment due to trauma symptoms.",
        category: "ptsd_pediatric", type: "boolean" 
    },
    { 
        text: "Has these problems interfered with Family relationships?", 
        patientText: "Do these feelings cause trouble at home or with your family?",
        professionalText: "Impairment in familial functioning.",
        category: "ptsd_pediatric", type: "boolean" 
    },
    { 
        text: "Has these problems interfered with your General happiness?", 
        patientText: "Do these feelings make you feel less happy overall?",
        professionalText: "Impact on global subjective well-being and happiness.",
        category: "ptsd_pediatric", type: "boolean" 
    }
];

const scaleOptions = [
    { text: "Never", score: 0 },
    { text: "Once in a while", score: 1 },
    { text: "Half the time", score: 2 },
    { text: "Almost always", score: 3 }
];

const booleanOptions = [
    { text: "Yes", score: 0 }, // Interference is not part of the numeric score-based severity
    { text: "No", score: 0 }
];

const getInterpretation = (rawScore) => {
    if (rawScore < 15) return 'Normal / Minimal distress';
    if (rawScore <= 20) return 'Moderate distress';
    return 'High distress - Clinical evaluation recommended';
};

const seedPTSDPediatric = async () => {
    try {
        const master = await Master.findOne({ slug: 'ptsd_pediatric' });
        const masterId = master ? master._id : null;
        const masterSlug = master ? master.slug : 'ptsd_pediatric';

        await Question.deleteMany({ category: masterSlug });
        await StandardizedScore.deleteMany({ category: masterSlug });

        const questionsToSeed = ptsdQuestions.map(q => ({
            ...q,
            minAge: 7,
            maxAge: 17,
            gender: "all",
            uiType: "radio",
            options: q.type === "scale" ? scaleOptions : booleanOptions,
            master: masterId
        }));

        for (const q of questionsToSeed) {
            await Question.create(q);
        }
        console.log(`  ✅ ${questionsToSeed.length} Trauma/PTSD questions seeded`);

        const scoresToSeed = [];
        // Max raw is 20 * 3 = 60
        for (let raw = 0; raw <= 60; raw++) {
            scoresToSeed.push({
                category: masterSlug,
                master: masterId,
                rawScore: raw,
                tScore: (raw / 60) * 100, // Visual percentage
                standardError: 0,
                interpretation: getInterpretation(raw)
            });
        }

        await StandardizedScore.insertMany(scoresToSeed);
        console.log(`  📊 ${scoresToSeed.length} Trauma/PTSD interpretation records seeded`);

    } catch (err) {
        console.error('  ❌ Trauma/PTSD Seeder Error:', err.message);
        throw err;
    }
};

module.exports = seedPTSDPediatric;
