<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class AllergyFoodSeeder extends Seeder
{
    public function run()
    {
        $foods = [
            'Alcohol', 'Almond', 'Almond Joy Bar', 'Almond Milk Yogurt', 'Alphonso Mango', 
            'Amaranth', 'Anchovies', 'Appam Batter', 'Apple', 'Apricot', 'Artichoke',
            'Artificial Flavoring', 'Artificial Sweetener', 'Ash Gourd', 'Aspartame',
            'Bael Fruit', 'Bamboo Shoot', 'Banana', 'Barley', 'Barley Flour', 'Barley Malt Extract',
            'Beer', 'Beetroot', 'Bell Pepper', 'Benzaldehyde', 'Bilimbi', 'Biryani with Yogurt',
            'Bitter Gourd', 'Blackcurrant', 'Blue 1', 'Blueberry', 'Bok Choy', 'Bottle Gourd',
            'Brazil Nut', 'Brinjal', 'Broccoli', 'Brown Sugar', 'Buckwheat', 'Buckwheat Flour',
            'Bulgur', 'Butter', 'Buttermilk', 'Buttermilk Drink', 'Butylated Hydroxytoluene',
            'Cabbage', 'Cactus Fruit', 'Carrageenan', 'Carrot', 'Cashew', 'Cassava', 'Cauliflower',
            'Celery', 'Cheese', 'Cheese Paratha', 'Cheese Puff', 'Cheese Samosa', 'Cheese Spread',
            'Cheesecake', 'Cherry', 'Chestnut', 'Chicory', 'Chive', 'Chutney', 'Clams', 'Clotted Cream',
            'Coconut', 'Cod', 'Collard', 'Condensed Milk', 'Condiment', 'Coriander', 'Corn',
            'Corn Syrup', 'Cornmeal', 'Couscous', 'Crab', 'Crawfish', 'Cream', 'Cream Cheese',
            'Cucumber', 'Curd', 'Curry Leaf', 'Curry Pod', 'Custard Apple', 'Dahi Puri', 'Dairy',
            'Dairy Sauce', 'Dairy Whipped Topping', 'Dairy-Based Creamer', 'Dairy-Based Dessert',
            'Dairy-Based Ice Cream Bar', 'Dairy-Based Soup', 'Date', 'Dhokla Flour', 'Dhokla with Yogurt',
            'Disodium Guanylate', 'Disodium Inosinate', 'Dragon Fruit', 'Dried Apricot', 'Dried Fig',
            'Dried Fruit', 'Dried Milk', 'Drumstick', 'Edamame', 'Elderberry', 'Endive', 'Ethyl Maltol',
            'Evaporated Milk', 'Farro', 'Farsi Puri Flour', 'Fennel', 'Fenugreek Flour', 'Fenugreek Leaf',
            'Feta', 'Fig', 'Fine Semolina', 'Finger Millet Flour', 'Fish', 'Flattened Rice', 'Flavored Tea',
            'Flax Seed', 'Food Coloring', 'Freekeh', 'Fruit', 'Garlic', 'Gelatin', 'Ghee', 'Gluten',
            'Gooseberry', 'Graham Flour', 'Gram Flour', 'Granulated Sugar', 'Grape', 'Gravy Made with Cream',
            'Green Bean', 'Green Chili', 'Grilled Cheese Sandwich', 'Grit', 'Groundnut', 'Guar Gum',
            'Guava', 'Halibut', 'Hazelnut', 'Herring', 'Honey', 'Ice Cream', 'Idli/Dosa Batter',
            'Instant Soup', 'Jabuticaba', 'Jackfruit', 'Kachori', 'Kachori with Curd', 'Khichdi Mix',
            'Khichdi with Ghee', 'Kiwano', 'Kiwi', 'Lady\'s Finger', 'Langda Mango', 'Leavened Flatbread',
            'Lecithin', 'Lemon', 'Lettuce', 'Lime', 'Lobster', 'Longan', 'Lotus Root', 'Lychee',
            'Macadamia Nut', 'Mackerel', 'Malt', 'Mango', 'Milk', 'Milk Chocolate', 'Milk-Based Smoothie',
            'Millet', 'Miso', 'Mixed Nut', 'Mono- and Diglyceride', 'Monosodium Glutamate', 'Mulberry',
            'Mushroom', 'Mussels', 'Mustard', 'Nectarine', 'Nut', 'Nut Brittle', 'Nut Butter', 'Nut Cluster',
            'Nut Crust for Tart', 'Nut Dip', 'Nut Flour', 'Nut Granola', 'Nut Granola Bar', 'Nut Milk',
            'Nut Oil', 'Nut Pesto', 'Nut Topping', 'Nut-Based Dessert', 'Nut-Based Energy Ball',
            'Nut-Based Snack', 'Nut-Based Vegan Cheese', 'Nut-Crusted Chicken', 'Nut-Infused Oil',
            'Nut-Studded Chocolate', 'Nutty Fruit Bar', 'Nutty Porridge', 'Nutty Protein Powder',
            'Nutty Rice Pilaf', 'Nutty Salad Dressing', 'Nutty Smoothie', 'Oat', 'Oat Flour', 'Onion',
            'Orange', 'Oysters', 'Packaged Dessert', 'Paneer', 'Paneer Tikka', 'Papaya', 'Parsley',
            'Passion Fruit', 'Pea', 'Peach', 'Peanut', 'Peanut Crunch Bar', 'Pearl Millet Flatbread',
            'Pearl Millet Flour', 'Pecan Nut', 'Pine Nut', 'Pineapple', 'Pistachio', 'Pistachio Ice Cream',
            'Pizza', 'Polenta', 'Polysorbate 80', 'Pomegranate', 'Pomelo', 'Poppy Seed', 'Potassium Sorbate',
            'Potato', 'Prawn', 'Preservative', 'Prickly Pear', 'Protein Powder', 'Pudding', 'Pumpkin',
            'Puttu Flour', 'Quinoa', 'Radicchio', 'Radish', 'Raisin', 'Raspberry', 'Red 40', 'Red Chili',
            'Rice', 'Rice Flour', 'Rice Pudding', 'Ricotta', 'Roasted Nut', 'Rye', 'Saccharin', 'Sago Flour',
            'Salmon', 'Samosa', 'Santol', 'Sapodilla', 'Sardines', 'Sauce', 'Scallops', 'Semolina', 'Sesame',
            'Sesame Butter', 'Sesame Oil', 'Sesame Seed', 'Shellfish', 'Shrikhand', 'Shrimp', 'Snack Bar',
            'Snapper', 'Sodium Benzoate', 'Sorghum', 'Soy', 'Soy Flour', 'Soy Milk', 'Soy Oil', 'Soy Sauce',
            'Soybean', 'Spelt', 'Spiced Nut', 'Spinach', 'Spirit', 'Spring Onion', 'Spring Roll', 'Squid',
            'Starfruit', 'Strawberry', 'Sucralose', 'Sugar', 'Sunflower Seed', 'Sweet', 'Sweet Potato',
            'Sweetened Condensed Milk', 'Swiss Chard', 'Tahini', 'Tamarind', 'Tapioca Pearl', 'Taro Root',
            'Tartrazine', 'Teff', 'Tempeh', 'Tiger Nut', 'Tofu', 'Tomato', 'Trail Mix with Nut', 'Triticale',
            'Trout', 'Tuna', 'Vanillin', 'Walnut', 'Watercress', 'Watermelon', 'Wheat Germ', 'Wheat Starch',
            'Whey', 'Whole Wheat Flour', 'Wine', 'Xanthan Gum', 'Yeast', 'Yogurt', 'Yogurt Chutney',
            'Yogurt Drink', 'Yogurt-Based Salad', 'Zucchini'
        ];

        foreach ($foods as $food) {
            DB::table('masters')->updateOrInsert(
                ['name' => $food],
                [
                    'master_type_slug' => 'allergy',
                    'slug' => strtolower(str_replace(' ', '_', $food)),
                    'attributes' => json_encode(['allergy_type' => "Food", 'allergy_category' => "Food"]),
                    'is_active' => 1
                ]
            );
        }
    }
}
