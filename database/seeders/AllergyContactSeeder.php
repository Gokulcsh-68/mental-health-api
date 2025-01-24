<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class AllergyContactSeeder extends Seeder
{
    public function run()
    {
        $contacts = ["Acne treatment","Air freshener","Air freshening products","Animal dander","Anti-aging cream","Antibacterial soap","Antibiotic","Antiperspirant","Art supply","Artificial nail","Balsam of Peru","Band-aid","Beauty products","Belt buckle","Benzocaine","Brass doorknob","Bronze coin","Candle","Car paint","Carpet cleaner","Carpet fibers","Cell phone case","Cement","Chamois leather","Chemical adhesives","Chemical residue","Children's toys","Chrome","Cigarette","Cleaning wipes","Cobalt","Cocoa","Coin","Construction materials","Copper utensil","Corn","Curling iron","Dentures or dental material","Deodorant","Disposable diapers","Dust mite","Dye","Earring made from alloys","Egg","Essential oil","Eye drop","Fabric dyes","Fabric softener","Face masks","Feminine hygiene products","Fertilizer","Fish","Fishing tackle","Foil wrap","Formaldehyde","Fragrance","Furniture polish","Gardening tool","Glycolic acid","Gold","Grommet in clothing","Hair accessory","Hair dye","Hair gel","Hair product","Hair straightener","Hairspray","Home fragrances","Household appliances","Household cleaning product","Household dust","Industrial chemicals","Insulation material","Lanolin","Latex","Laundry additive","Laundry detergent","Lip balm","Makeup","Medical tape","Metal fixtures","Metal surfaces","Methyldibromo glutaronitrile","Methylisothiazolinone","Milk","Moisturizer","Mold","Nail polish","Nail polish remover","Natural rubber","Nickel","Nickel-containing jewelry","Nylon","Pain reliever","Paint","Paper","Paper clip","Paraben","Peanut","Perfumed lotion","Personal care products","Pesticide","Pesticide residues","Pet hair","Plant material","Plastic bag","Plastic containers","Polyester","Potassium sorbate","Resins","Rubber","Rubber band","Safety pin","Scented products","Shaving cream","Shellfish","Shower curtains","Silicone","Skin care product","Soaps","Some food packaging material","Sorbic acid","Soy","Sunscreen","Synthetic fabrics","Tannin","Tanning lotion","Tattoo","Textiles","Toxic substances in the environment","Tweezers","Varnish","Vinyl glove","Walnut","Watch with metal band","Wheat","Wood dust","Wood treatment","Wool","Wool carpet","Zipper"];
        foreach ($contacts as $contact) {
            DB::table('masters')->updateOrInsert(
                ['name' => $contact],
                [
                    'master_type_slug' => 'allergy',
                    'slug' => strtolower(str_replace(' ', '_', $contact)),
                    'attributes' => json_encode(['allergy_type' => "Contact", 'allergy_category' => "Contact"]),
                    'is_active' => 1
                ]
            );
        }
    }
}
