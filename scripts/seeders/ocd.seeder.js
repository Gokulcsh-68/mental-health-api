const Question = require('../../src/models/Question');
const Master = require('../../src/models/Master');
const StandardizedScore = require('../../src/models/StandardizedScore');

const ocdQuestions = [
    { 
        text: "I have saved up so many things that they get in the way.", 
        patientText: "I have a lot of items saved up that make it hard to move around or use spaces in my home.",
        professionalText: "Patient exhibits hoarding behaviors with significant functional impairment of living spaces.",
        category: "ocd", minAge: 7, maxAge: 120, gender: "all", uiType: "radio" 
    },
    { 
        text: "I check things more often than necessary.", 
        patientText: "I feel like I have to keep checking things (like locks or switches) over and over.",
        professionalText: "Presence of checking compulsions; repetitive behaviors performed to reduce anxiety/distress.",
        category: "ocd", minAge: 7, maxAge: 120, gender: "all", uiType: "radio" 
    },
    { 
        text: "I get upset if objects are not arranged properly.", 
        patientText: "I feel very bothered or stressed if things aren't in their exact right place.",
        professionalText: "Symmetry and orderliness obsessions; distress associated with environmental disarray.",
        category: "ocd", minAge: 7, maxAge: 120, gender: "all", uiType: "radio" 
    },
    { 
        text: "I feel compelled to count while I am doing things.", 
        patientText: "I feel like I have to count things while I am doing my daily activities.",
        professionalText: "Evidence of mental compulsions (counting); rigid adherence to internal numerical rules.",
        category: "ocd", minAge: 7, maxAge: 120, gender: "all", uiType: "radio" 
    },
    { 
        text: "I find it difficult to touch an object when I know it has been touched by strangers or certain people.", 
        patientText: "I really don't want to touch things that other people have touched because they might be dirty.",
        professionalText: "Contamination obsessions and avoidant behaviors; significant distress regarding germ transmission.",
        category: "ocd", minAge: 7, maxAge: 120, gender: "all", uiType: "radio" 
    },
    { 
        text: "I find it difficult to control my own thoughts.", 
        patientText: "I have thoughts that keep popping into my head that I can't seem to stop.",
        professionalText: "Presence of intrusive, ego-dystonic thoughts; patient experiences significant lack of cognitive control.",
        category: "ocd", minAge: 7, maxAge: 120, gender: "all", uiType: "radio" 
    },
    { 
        text: "I collect things I don’t need.", 
        patientText: "I have a hard time throwing away things, even if I don't use them.",
        professionalText: "Difficulty with discarding possessions (hoarding symptoms); potential accumulation of clutter.",
        category: "ocd", minAge: 7, maxAge: 120, gender: "all", uiType: "radio" 
    },
    { 
        text: "I repeatedly check doors, windows, drawers, etc.", 
        patientText: "I go back to check that I've closed or locked things many times.",
        professionalText: "Repetitive checking rituals targeting home security/access points.",
        category: "ocd", minAge: 7, maxAge: 120, gender: "all", uiType: "radio" 
    },
    { 
        text: "I get upset if others change the way I have arranged things.", 
        patientText: "I get very frustrated if someone moves things that I have set up in a certain way.",
        professionalText: "Rigidity regarding environmental order; distress triggered by external disruption of 'just right' state.",
        category: "ocd", minAge: 7, maxAge: 120, gender: "all", uiType: "radio" 
    },
    { 
        text: "I feel I have to repeat certain numbers.", 
        patientText: "I feel a strong need to say or think about certain numbers a specific number of times.",
        professionalText: "Numerical compulsions or counting rituals; behavior is performed to neutralize intrusive fear.",
        category: "ocd", minAge: 7, maxAge: 120, gender: "all", uiType: "radio" 
    },
    { 
        text: "I sometimes have to wash or clean myself simply because I feel contaminated.", 
        patientText: "I feel like I need to wash myself a lot because I feel 'unclean' even if I'm not physically dirty.",
        professionalText: "Cleaning/washing compulsions driven by feelings of internal or external contamination.",
        category: "ocd", minAge: 7, maxAge: 120, gender: "all", uiType: "radio" 
    },
    { 
        text: "I am upset by unpleasant thoughts that come into my mind against my will.", 
        patientText: "I get very bothered by mean or scary thoughts that I don't want to have.",
        professionalText: "Significant distress associated with intrusive ruminations; ego-dystonic content reported.",
        category: "ocd", minAge: 7, maxAge: 120, gender: "all", uiType: "radio" 
    },
    { 
        text: "I avoid throwing things away because I am afraid I might need them later.", 
        patientText: "I keep everything because I'm worried it might be important one day.",
        professionalText: "Fear of future regret or loss of utility driving hoarding behaviors.",
        category: "ocd", minAge: 7, maxAge: 120, gender: "all", uiType: "radio" 
    },
    { 
        text: "I repeatedly check gas and water taps and light switches after turning them off.", 
        patientText: "I keep checking the stove or the lights to make sure they are really off.",
        professionalText: "Safety-checking rituals aimed at preventing catastrophic outcomes (e.g., fire, flood).",
        category: "ocd", minAge: 7, maxAge: 120, gender: "all", uiType: "radio" 
    },
    { 
        text: "I need things to be arranged in a particular way.", 
        patientText: "Things have to be organized perfectly for me to feel okay.",
        professionalText: "Need for symmetry, order, and exactness; 'Just Right' phenomenon.",
        category: "ocd", minAge: 7, maxAge: 120, gender: "all", uiType: "radio" 
    },
    { 
        text: "I feel that there are good and bad numbers.", 
        patientText: "I believe some numbers are lucky and some are very unlucky or scary.",
        professionalText: "Magical thinking patterns associated with numerical values; OCD-consistent thought distortion.",
        category: "ocd", minAge: 7, maxAge: 120, gender: "all", uiType: "radio" 
    },
    { 
        text: "I wash my hands more often and longer than necessary.", 
        patientText: "I spend a lot of time washing my hands, more than most people do.",
        professionalText: "Excessive hand-washing behavior; exceeds objective hygiene requirements.",
        category: "ocd", minAge: 7, maxAge: 120, gender: "all", uiType: "radio" 
    },
    { 
        text: "I frequently get nasty thoughts and have difficulty in getting rid of them.", 
        patientText: "I often have bad thoughts that stay stuck in my head no matter how hard I try to ignore them.",
        professionalText: "Chronic intrusive thoughts; significant difficulty with thought suppression and neutralizing rituals.",
        category: "ocd", minAge: 7, maxAge: 120, gender: "all", uiType: "radio" 
    }
];

const options = [
    { text: "Not at all", score: 0 },
    { text: "A little", score: 1 },
    { text: "Moderately", score: 2 },
    { text: "A lot", score: 3 },
    { text: "Extremely", score: 4 }
];

const getInterpretation = (rawScore) => {
    // Mapping 0-72 total to severity categories
    if (rawScore <= 18) return 'Minimal OCD symptoms';
    if (rawScore <= 30) return 'Mild OCD symptoms';
    if (rawScore <= 42) return 'Moderate OCD symptoms';
    return 'Severe OCD symptoms';
};

const seedOCD = async () => {
    try {
        const master = await Master.findOne({ slug: 'ocd' });
        const masterId = master ? master._id : null;
        const masterSlug = master ? master.slug : 'ocd';

        await Question.deleteMany({ category: masterSlug });
        await StandardizedScore.deleteMany({ category: masterSlug });

        const questionsToSeed = ocdQuestions.map(q => ({
            ...q,
            type: "scale",
            options,
            master: masterId
        }));

        for (const q of questionsToSeed) {
            await Question.create(q);
        }
        console.log(`  ✅ ${questionsToSeed.length} OCD questions seeded`);

        const scoresToSeed = [];
        // Max raw is 18 * 4 = 72
        for (let raw = 0; raw <= 72; raw++) {
            scoresToSeed.push({
                category: masterSlug,
                master: masterId,
                rawScore: raw,
                tScore: (raw / 72) * 100, // Visual percentage
                standardError: 0,
                interpretation: getInterpretation(raw)
            });
        }

        await StandardizedScore.insertMany(scoresToSeed);
        console.log(`  📊 ${scoresToSeed.length} OCD interpretation records seeded`);

    } catch (err) {
        console.error('  ❌ OCD Seeder Error:', err.message);
        throw err;
    }
};

module.exports = seedOCD;
