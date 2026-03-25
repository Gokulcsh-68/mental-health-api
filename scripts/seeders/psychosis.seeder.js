const Question = require('../../src/models/Question');
const Master = require('../../src/models/Master');
const StandardizedScore = require('../../src/models/StandardizedScore');

const psychosisQuestions = [
    { 
        text: "Do familiar surroundings sometimes seem strange, confusing, threatening or unreal to you?",
        patientText: "Do places that you know well ever feel strange, scary, or like they aren't real?",
        professionalText: "Patient reports experiences of derealization or environmental strangeness.",
    },
    { 
        text: "Have you heard unusual sounds like banging, clicking, hissing, clapping or ringing in your ears?",
        patientText: "Have you heard any strange sounds like banging or clicking that others don't seem to hear?",
        professionalText: "Presence of elementary auditory hallucinations (non-verbal).",
    },
    { 
        text: "Do things that you see appear different from the way they usually do?",
        patientText: "Do things ever look different or strange to you, like colors changing or shapes moving?",
        professionalText: "Visual distortions or perceptual abnormalities reported.",
    },
    { 
        text: "Have you had experiences with telepathy, psychic forces, or fortune telling?",
        patientText: "Have you ever felt like you have special powers like reading minds or knowing the future?",
        professionalText: "Presence of magical thinking or overvalued ideas regarding paranormal abilities.",
    },
    { 
        text: "Have you felt that you are not in control of your own ideas or thoughts?",
        patientText: "Have you ever felt like your thoughts aren't your own or someone else is putting them there?",
        professionalText: "Thoughts insertion or delusions of thought control.",
    },
    { 
        text: "Do you have difficulty getting your point across, because you ramble or go off the track a lot when you talk?",
        patientText: "Do you find it hard to explain things because your mind jumps around a lot when you talk?",
        professionalText: "Evidence of formal thought disorder (tangentiality or circumstantiality).",
    },
    { 
        text: "Do you have strong feelings or beliefs about being unusually gifted or talented in some way?",
        patientText: "Do you feel like you have a very special talent or secret knowledge that others don't have?",
        professionalText: "Presence of grandiose delusions or inflated self-worth.",
    },
    { 
        text: "Do you feel that other people are watching you or talking about you?",
        patientText: "Do you ever feel like people are following you, watching you, or talking about you behind your back?",
        professionalText: "Ideas of reference or persecutory ideation.",
    },
    { 
        text: "Do you sometimes get strange feelings on or just beneath your skin, like bugs crawling?",
        patientText: "Do you ever feel strange things on your skin, like something is crawling on you when nothing is there?",
        professionalText: "Presence of tactile (formication) hallucinations.",
    },
    { 
        text: "Do you sometimes feel suddenly distracted by distant sounds that you are not normally aware of?",
        patientText: "Do you find yourself getting distracted by sounds that seem far away or that you don't usually notice?",
        professionalText: "Hyperacusis or increased sensitivity to environmental stimuli (prodromal marker).",
    },
    { 
        text: "Have you had the sense that some person or force is around you, although you couldn't see anyone?",
        patientText: "Have you ever felt like someone or something is near you, even though you can't see them?",
        professionalText: "Sense of presence (hallucinatory experience).",
    },
    { 
        text: "Do you worry at times that something may be wrong with your mind?",
        patientText: "Do you ever worry that your thoughts or feelings are becoming very confusing or strange?",
        professionalText: "Subjective experience of cognitive decline or mental fragmentation.",
    },
    { 
        text: "Have you ever felt that you don't exist, the world does not exist, or that you are dead?",
        patientText: "Have you ever felt like you aren't really here, or that the whole world has disappeared?",
        professionalText: "Cotard-like symptoms or nihilistic delusions.",
    },
    { 
        text: "Have you been confused at times whether something you experienced was real or imaginary?",
        patientText: "Have you ever had trouble telling the difference between your dreams and what is actually happening?",
        professionalText: "Impaired reality testing; difficulty distinguishing internal vs. external stimuli.",
    },
    { 
        text: "Do you hold beliefs that other people would find unusual or bizarre?",
        patientText: "Do you have ideas or beliefs that your friends or family think are very strange?",
        professionalText: "Presence of idiosyncratic or potentially bizarre delusions.",
    },
    { 
        text: "Do you feel that parts of your body have changed in some way, or that parts of your body are working differently?",
        patientText: "Do you ever feel like your body is changing in a way that doesn't make sense to you?",
        professionalText: "Somatic delusions or distorted body image (schizophrenic spectrum).",
    },
    { 
        text: "Are your thoughts sometimes so strong that you can almost hear them?",
        patientText: "Do your own thoughts ever sound so loud in your head that it's like someone is speaking?",
        professionalText: "Thought broadcasting or audible thoughts (Gedankenlautwerden).",
    },
    { 
        text: "Do you find yourself feeling mistrustful or suspicious of other people?",
        patientText: "Do you find it hard to trust people because you're worried they might be trying to trick or hurt you?",
        professionalText: "Paranoid ideation or pervasive mistrust.",
    },
    { 
        text: "Have you seen unusual things like flashes, flames, blinding light, or geometric figures?",
        patientText: "Have you ever seen strange lights, colors, or shapes that other people don't see?",
        professionalText: "Simple visual hallucinations (photopsia).",
    },
    { 
        text: "Have you seen things that other people can't see or don't seem to see?",
        patientText: "Have you ever seen people, animals, or objects that others couldn't see?",
        professionalText: "Complex visual hallucinations.",
    },
    { 
        text: "Do people sometimes find it hard to understand what you are saying?",
        patientText: "Do people ever tell you that they can't follow what you're saying when you're talking?",
        professionalText: "Grossly disorganized speech or derailment observed by others.",
    }
];

const combinedOptions = [
    { text: "No", score: 0 },
    { text: "Yes - Strongly disagree", score: 1 },
    { text: "Yes - Disagree", score: 2 },
    { text: "Yes - Neutral", score: 3 },
    { text: "Yes - Agree", score: 4 },
    { text: "Yes - Strongly agree", score: 5 }
];

const seedPsychosis = async () => {
    try {
        const master = await Master.findOne({ slug: 'psychosis' });
        const masterId = master ? master._id : null;
        const masterSlug = master ? master.slug : 'psychosis';

        await Question.deleteMany({ category: masterSlug });
        await StandardizedScore.deleteMany({ category: masterSlug });

        const questionsToSeed = psychosisQuestions.map(q => ({
            text: q.text,
            category: masterSlug,
            type: "scale",
            minAge: 18,
            maxAge: 120,
            gender: "all",
            uiType: "radio",
            options: combinedOptions,
            master: masterId
        }));

        for (const q of questionsToSeed) {
            await Question.create(q);
        }
        console.log(`  ✅ ${questionsToSeed.length} Psychosis questions seeded`);

        const scoresToSeed = [];
        // Max raw is 21 * 5 = 105
        for (let raw = 0; raw <= 105; raw++) {
            let interpretation = "Low risk";
            if (raw >= 24) {
                interpretation = "Heightened risk - Professional evaluation recommended";
            }

            scoresToSeed.push({
                category: masterSlug,
                master: masterId,
                rawScore: raw,
                tScore: (raw / 105) * 100, // Visual percentage
                standardError: 0,
                interpretation: interpretation
            });
        }

        await StandardizedScore.insertMany(scoresToSeed);
        console.log(`  📊 ${scoresToSeed.length} Psychosis interpretation records seeded`);

    } catch (err) {
        console.error('  ❌ Psychosis Seeder Error:', err.message);
        throw err;
    }
};

module.exports = seedPsychosis;
