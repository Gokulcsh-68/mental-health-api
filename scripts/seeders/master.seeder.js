const Master = require('../../src/models/Master');

const masters = [
    { name: "Depression", slug: "depression", master_type_slug: "mental_health", minAge: 18, maxAge: 120, gender: "all" },
    { name: "Anxiety", slug: "anxiety", master_type_slug: "mental_health", minAge: 18, maxAge: 120, gender: "all" },
    { name: "Emotional Distress—Anger", slug: "anger", master_type_slug: "mental_health", minAge: 18, maxAge: 120, gender: "all" },
    { name: "Sleep Disturbance", slug: "sleep", master_type_slug: "mental_health", minAge: 11, maxAge: 120, gender: "all" },
    { name: "Mania", slug: "mania", master_type_slug: "mental_health", minAge: 18, maxAge: 120, gender: "all" },
    { name: "Somatic Symptom", slug: "somatic", master_type_slug: "mental_health", minAge: 18, maxAge: 120, gender: "all" },
    { name: "Bipolar Disorder", slug: "bipolar", master_type_slug: "mental_health", minAge: 18, maxAge: 120, gender: "all" },
    { name: "Repetitive Thoughts and Behaviors", slug: "repetitive_thoughts", master_type_slug: "mental_health", minAge: 18, maxAge: 120, gender: "all" },
    { name: "Substance Use / Addiction", slug: "substance_use", master_type_slug: "mental_health", minAge: 11, maxAge: 120, gender: "all" },
    { name: "Postpartum Depression", slug: "postpartum", master_type_slug: "mental_health", minAge: 18, maxAge: 120, gender: "female" },
    { name: "Anger (Pediatric)", slug: "anger_pediatric", master_type_slug: "mental_health", minAge: 11, maxAge: 17, gender: "all" },
    { name: "Irritability", slug: "irritability", master_type_slug: "mental_health", minAge: 11, maxAge: 17, gender: "all" },
    { name: "Anxiety (Pediatric)", slug: "anxiety_pediatric", master_type_slug: "mental_health", minAge: 11, maxAge: 17, gender: "all" },
    { name: "Repetitive Thoughts (Pediatric)", slug: "repetitive_thoughts_pediatric", master_type_slug: "mental_health", minAge: 11, maxAge: 17, gender: "all" },
    { name: "Separation Anxiety", slug: "separation_anxiety", master_type_slug: "mental_health", minAge: 11, maxAge: 14, gender: "male" },
    { name: "Oppositional Defiant Disorder", slug: "odd", master_type_slug: "mental_health", minAge: 11, maxAge: 17, gender: "male" },
    { name: "Social Anxiety", slug: "social_anxiety", master_type_slug: "mental_health", minAge: 18, maxAge: 120, gender: "all" },
    { name: "Agoraphobia", slug: "agoraphobia", master_type_slug: "mental_health", minAge: 11, maxAge: 17, gender: "all" },
    { name: "Panic Disorder", slug: "panic_disorder", master_type_slug: "mental_health", minAge: 18, maxAge: 120, gender: "all" },
    { name: "ADHD", slug: "adhd", master_type_slug: "mental_health", minAge: 7, maxAge: 120, gender: "all" },
    { name: "OCD", slug: "ocd", master_type_slug: "mental_health", minAge: 7, maxAge: 120, gender: "all" },
    { name: "Psychosis & Schizophrenia", slug: "psychosis", master_type_slug: "mental_health", minAge: 18, maxAge: 120, gender: "all" },
    { name: "Gambling Addiction", slug: "gambling", master_type_slug: "mental_health", minAge: 18, maxAge: 120, gender: "all" },
    { name: "Eating Disorder", slug: "eating_disorder", master_type_slug: "mental_health", minAge: 11, maxAge: 17, gender: "all" },
    { name: "PMDD Screening", slug: "pmdd", master_type_slug: "mental_health", minAge: 12, maxAge: 45, gender: "female" },
    { name: "Autism Spectrum", slug: "autism_spectrum", master_type_slug: "mental_health", minAge: 11, maxAge: 17, gender: "all" },
    { name: "Trauma/PTSD Screening", slug: "ptsd_pediatric", master_type_slug: "mental_health", minAge: 7, maxAge: 17, gender: "all" },
    { name: "Acute Stress Symptoms", slug: "acute_stress", master_type_slug: "mental_health", minAge: 18, maxAge: 120, gender: "all" },
    { name: "Dissociative Symptoms", slug: "dissociative_symptoms", master_type_slug: "mental_health", minAge: 18, maxAge: 120, gender: "all" },
    { name: "Personality Inventory", slug: "personality_inventory", master_type_slug: "mental_health", minAge: 11, maxAge: 17, gender: "all" },
];

const seedMasters = async () => {
    try {
        await Master.deleteMany();
        console.log('  🗑️  Cleared existing Masters');

        await Master.create(masters);
        console.log(`  ✅ ${masters.length} Masters seeded successfully`);
    } catch (err) {
        console.error('  ❌ Master Seeder Error:', err.message);
        throw err;
    }
};

module.exports = seedMasters;
