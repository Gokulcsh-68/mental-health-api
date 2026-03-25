const TaxCode = require('../../src/models/TaxCode');

const taxCodes = [
    {
        code: 'GST_0',
        name: 'GST 0% (Exempt)',
        rate: 0,
        description: 'Tax-exempt services'
    },
    {
        code: 'GST_5',
        name: 'GST 5%',
        rate: 5,
        description: 'Reduced rate GST for essential health services'
    },
    {
        code: 'GST_12',
        name: 'GST 12%',
        rate: 12,
        description: 'Standard GST for general healthcare services'
    },
    {
        code: 'GST_18',
        name: 'GST 18%',
        rate: 18,
        description: 'Standard GST for professional consultation services'
    },
    {
        code: 'VAT_5',
        name: 'VAT 5%',
        rate: 5,
        description: 'Value Added Tax - 5%'
    },
    {
        code: 'NO_TAX',
        name: 'No Tax',
        rate: 0,
        description: 'No tax applicable'
    }
];

const seedTaxCodes = async () => {
    try {
        await TaxCode.deleteMany();
        console.log('  🗑️  Cleared existing Tax Codes');

        await TaxCode.create(taxCodes);
        console.log(`  ✅ ${taxCodes.length} Tax Codes seeded successfully`);
    } catch (err) {
        console.error('  ❌ Tax Code Seeder Error:', err.message);
        throw err;
    }
};

module.exports = seedTaxCodes;
