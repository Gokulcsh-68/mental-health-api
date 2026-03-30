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
    },
    { 
        name: "Privacy Policy", 
        slug: "privacy_policy", 
        master_type_slug: "portal_content",
        attributes: { body: "Your privacy is important to us. This policy explains how we collect, use, and safeguard your data. We use industry-standard encryption to protect your sensitive health information." }
    },
    { 
        name: "Terms of Service", 
        slug: "terms_of_service", 
        master_type_slug: "portal_content",
        attributes: { body: "By using this platform, you agree to our terms. This platform provides mental health support and is not a substitute for emergency psychiatric care." }
    },
    { 
        name: "About MindBalance", 
        slug: "about_mindbalance", 
        master_type_slug: "portal_content",
        attributes: { body: "MindBalance is a comprehensive mental health platform designed to bridge the gap between patients and practitioners. We provide tools for self-assessment, clinical feedback, and therapy management to ensure a holistic approach to mental wellbeing." }
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
