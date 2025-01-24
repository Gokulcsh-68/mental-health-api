<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class AllergyEnvironmentSeeder extends Seeder
{
    public function run()
    {
        $environments = ['Acacia Pollen','Acetic Acid','Acids','Additives','Air Fresheners',
        'Airborne Dust Particles','Alder Pollen','Algae Dust','Allergens from Live Animals','Alternaria Mold','Ammonia',
        'Animal Allergens','Animal Bedding Dust','Animal Fecal Particles','Animal Feces','Animal Feed Dust','Animal Furs',
        'Animal Hair on Clothing','Animal Protein Powders','Animal Scent Glands','Animal Serum','Animal Trimming Residues',
        'Animal Urine','Animal Waste Products','Anise Pollen','Anjan (Hardwickia binata) Pollen','Ant Venom','Ants ','Aphids',
        'Arsenic','Asbestos','Bamboos Pollen','Barley Pollen','Bases','Bed Bugs','Bedding Dust','Bee Hives','Bees ','Beetles',
        'Benzene','Bermuda Grass Pollen','Big Bluestem Pollen','Birch Pollen','Bird Droppings','Bird Feathers','Bleach',
        'Bluegrass Pollen','Body Lotions','Boxelder Bugs','Camel Dander','Canine Saliva','Carbon Dioxide','Carbon Monoxide',
        'Carpenter Ants','Carpet Beetles','Carpet Dust','Casuarina Pollen','Cat Dander','Cat Litter Dust','Cattle Dander',
        'Cedar Pollen','Chalk Dust','Chemical Allergens','Chemical Fertilizers','Chlorine','Cicadas','Cleaning Products',
        'Cluster Flies','Cockroach Droppings','Cockroaches','Construction Dust','Cotton Dust','Cottonseed Dust',
        'Cottonwood Pollen','Crabgrass Pollen','Cypress Pollen','Dandelion Fluff','Dander from Pets ','Detergents',
        'Dog Dander','Dog Saliva','Drywall Dust','Dust and Mold','Dust from Animal Bedding','Dust from Furniture',
        'Dust Mite Waste','Dust Mites','Elm Pollen','Enzymes from Animal Products','Eucalyptus Globulus Pollen',
        'Eucalyptus Pollen','Fabric Softeners','Feather Dander','Feline Saliva','Ferret Dander','Ferret Urine','Fiberglass Dust',
        'Ficus Pollen','Fish Food Allergens','Fish Scales','Flavors','Fleas','Fly Allergens','Food Preservatives','Formaldehyde',
        'Formalin','Foxtail Grass Pollen','Fragrances','Fungal Byproducts','Fungal Mycelium','Fungal Plant Debris','Fungal Spores',
        'Fusarium Mold','Glues','Goat Dander','Goat Hair','Grain Dust','Grain Elevator Dust','Grain Mites','Grass Pollen',
        'Guinea Pig Dander','Hair Dyes','Hair Products','Hamster Dander','Helenium Pollen','Herbicides','Hornets','Horse Dander',
        'Horse Hair','Horsefly','House Dust Mites','Household Dust','Hydrogen Sulfide','Indoor Fungal Species',
        'Indoor Mold (e.g., Aspergillus)','Industrial Chemicals','Ink Dust','Insect Allergens','Insect Larvae','Insect Venom',
        'Isocyanates','Japanese Beetles','Jatropha Pollen','Johnson Grass Pollen','Kikar (Acacia) Pollen','Laundry Dust',
        'Lead','Leaf Miners','Leafcutter Ants','Leafhoppers','Lice','Little Bluestem Pollen','Lovegrass Pollen','Mahua Pollen',
        'Mealybugs','Mercury','Mite Infestations','Moldy Leaves','Mosquitoes','Moth Larvae','Moths','Mucor Mold','Mulberry Pollen',
        'Mushroom Spores','Nail Polish','Nitrogen Dioxide','Oak Pollen','Oat Pollen','Orchard Grass Pollen','Outdoor Mold (e.g., Cladosporium)',
        'Paint Fumes','Palm Pollen','Paper Dust','Parabens','Parakeet Feathers','Penicillium Mold',
        'Perennial Ryegrass Pollen','Perfumes','Pesticides','Pet Bird Dander','Pet Grooming Products','Pet Hair',
        'Pet Hair in Carpets','Phenol','Phthalates','Pigeon Droppings','Pigeon Feathers','Pine Pollen','Plague Locusts',
        'Plant Bugs','Pollen from Weeds','Poplar Pollen','Porcupine Quills','Powdery Mildew','Quack Grass Pollen',
        'Rabbit Dander','Ragweed Pollen','Reptile Dander','Resin','Rhizopus Mold','Rodent Dander','Rye Dust','Ryegrass Pollen',
        'Sal (Shorea) Pollen','Sandfly','Sawdust','Scabies Mites','Scale Insects','Shampoo Chemicals','Sheep Dander','Silicone',
        'Silverfish','Silverfish Droppings','Soil Dust','Soil Microorganisms','Solvents','Soot Dust','Sorghum Pollen','Spices Dust',
        'Spiders','Spore Traps','Squirrel Dander','Stachybotrys (Black Mold)','Stinging Insects','Stored Grain Dust','Sulfates',
        'Sunscreens','Switchgrass Pollen','Synthetic Dyes','Teak Pollen','Termite Droppings','Termites','Textile Dust','Thrips',
        'Ticks','Timothy Grass Pollen','Timothy Hay Pollen','Toluene','Tree Pollen','Volatile Organic Compounds (VOCs)','Wasp Nests',
        'Wasps ','Weevils','Wheat Dust','Wheat Pollen','Wild Rye Pollen','Willow Pollen','Wood Borers','Wood Dust','Wool from Sheep',
        'Yellowjackets','Zoonotic Pathogens','Zoysia Grass Pollen'];

        foreach ($environments as $environment) {
            DB::table('masters')->updateOrInsert(
                ['name' => $environment],
                [
                    'master_type_slug' => 'allergy',
                    'slug' => strtolower(str_replace(' ', '_', $environment)),
                    'attributes' => json_encode(['allergy_type' => "Environment", 'allergy_category' => "Environment"]),
                    'is_active' => 1
                ]
            );
        }
    }
}
