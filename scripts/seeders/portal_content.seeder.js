const Master = require('../../src/models/Master');

const portalContent = [
    { 
        name: "Mood Guide", 
        slug: "mood_guide", 
        master_type_slug: "portal_content",
        attributes: { body: "Daily mood tracking helps identify patterns. Try to record your mood at the same time every day." }
    },
    { 
        name: "Sleep Guide", 
        slug: "sleep_guide", 
        master_type_slug: "portal_content",
        attributes: { body: "Maintain a consistent sleep schedule. Avoid screens 1 hour before bed for better quality sleep." }
    },
    { 
        name: "Anxiety Guide", 
        slug: "anxiety_guide", 
        master_type_slug: "portal_content",
        attributes: { body: "Practice deep breathing (4-7-8 technique) when you feel overwhelmed. It helps calm the nervous system." }
    }
];

const seedPortalContent = async () => {
    try {
        // Delete existing portal content to avoid duplicates and trigger pre-save hooks on create
        const slugs = portalContent.map(c => c.slug);
        await Master.deleteMany({ slug: { $in: slugs }, master_type_slug: 'portal_content' });
        
        await Master.create(portalContent);
        console.log(`  ✅ ${portalContent.length} Portal Content items seeded`);
    } catch (err) {
        console.error('  ❌ Portal Content Seeder Error:', err.message);
        throw err;
    }
};

module.exports = seedPortalContent;
