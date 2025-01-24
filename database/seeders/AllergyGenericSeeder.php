<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class AllergyGenericSeeder extends Seeder
{
    public function run()
    {
        $drugs = ['Aceclofenac','Acetazolamide','Acrivastine','Amiodarone','Amitriptyline','Amlodipine','Amoxapine','Amoxicillin','Amoxicillin/Clarithromycin','Amoxicillin/Clavulanate (Augmentin)','Amoxicillin/Rifampin','Ampicillin','Ampicillin/Amoxicillin','Ampicillin/Gentamicin','Ampicillin/Sulbactam','Apixaban','Aspirin','Aspirin/Codeine','Atenolol','Atorvastatin','Azelastine','Azithromycin','Aztreonam/Clavulanate','Baclofen','Bacteroides/Clindamycin','Betahistine','Bilastine','Bromide','Brompheniramine','Bupropion','Buspirone','Cannabidiol (CBD)','Carbamazepine','Carbinoxamine','Carvedilol','Cataflam','Cefazolin/Gentamicin','Cefoperazone/Sulbactam','Cefotaxime/Aminoglycoside','Cefoxitin','Ceftazidime','Ceftazidime/Avibactam','Ceftolozane/Tazobactam','Ceftriaxone','Cefuroxime','Celecoxib','Cephalexin','Cetirizine','Cetirizine/Pseudoephedrine','Chloramphenicol','Chlorphenamine','Chlorpheniramine','Chlorpromazine','Choline Magnesium Trisalicylate','Ciprofloxacin','Ciprofloxacin/Clindamycin','Citalopram','Clindamycin','Clobazam','Clomipramine','Clonidine','Clopidogrel','Cyproheptadine','Dabigatran','Daptomycin/Gentamicin','Desloratadine','Desvenlafaxine','Dexamethasone','Dexketoprofen','Diclofenac','Diflunisal','Digoxin','Diltiazem','Dimenhydrinate','Diphenhydramine','Diphenylpyraline','Divalproex Sodium','Doxepin','Doxycycline','Doxycycline/Amoxicillin','Doxylamine','Dronedarone','Duloxetine','Edoxaban','Enoxaparin','Epinastine','Ertapenem','Ertapenem/Antibiotic Combination','Erythromycin','Escitalopram','Eslicarbazepine Acetate','Ethosuximide','Etodolac','Felbamate','Fenoprofen','Fexofenadine','Fexofenadine/Pseudoephedrine','Flecainide','Fluoxetine','Flurbiprofen','Fluvoxamine','Fosphenytoin','Fusidic Acid','Gabapentin','Gentamicin','Hydralazine','Hydroxyzine','Hydroxyzine Pamoate','Ibuprofen','Imipenem','Imipenem/Cilastatin','Indomethacin','Isocarboxazid','Isosorbide Dinitrate','Ketoprofen','Ketorolac','Labetalol','Lacosamide','Lamotrigine','Levetiracetam','Levocetirizine','Linezolid','Lisinopril','Lithium','Loratadine','Loratadine/Pseudoephedrine','Lornoxicam','Losartan','Maprotiline','Meclizine','Meclofenamate','Mefenamic Acid','Meloxicam','Meropenem','Meropenem/Colistin','Meropenem/Vaborbactam','Metoprolol','Metronidazole/Clindamycin','Mirtazapine','Moxifloxacin','Moxifloxacin/Clindamycin','Nabumetone','Nafcillin','Naproxen','Nefazodone','Nitrofurantoin','Nitroglycerin','Nortriptyline','Olopatadine','Oxacillin','Oxaprozin','Oxcarbazepine','Paracetamol (Acetaminophen)','Paroxetine','Penicillin','Perampanel','Phenelzine','Pheniramine','Phenobarbital','Phenytoin','Piperacillin/Tazobactam (Zosyn)','Piroxicam','Pravastatin','Pregabalin','Primidone','Promethazine','Protriptyline','Quinidine','Quinupristin/Dalfopristin (Synercid)','Quinupristin/Dalfopristin (Synercid)','Reboxetine','Rifampin','Rivaroxaban','Rofecoxib','Rosuvastatin','Rufinamide','Salsalate','Selegiline','Sertraline','Simvastatin','Sotalol','Stiripentol','Sulfamethoxazole/Trimethoprim (Bactrim)','Sulfamethoxazole/Trimethoprim (Bactrim)','Sulindac','Telavancin','Tetracycline','Tiagabine','Ticarcillin/Clavulanate','Tobramycin','Tobramycin/Aminoglycoside','Tolfenamic Acid','Topiramate','Tranylcypromine','Trazodone','Trimipramine','Triprolidine','Valdecoxib','Valproic Acid','Vancomycin','Vancomycin/Pristinamycin','Vancomycin/Rifampin','Venlafaxine','Verapamil','Vigabatrin','Vilazodone','Vortioxetine','Warfarin','Zaleplon','Zonisamide'];

        foreach ($drugs as $drug) {
            DB::table('masters')->updateOrInsert(
                ['name' => $drug],
                [
                    'master_type_slug' => 'Generic',
                    'slug' => strtolower(str_replace(' ', '_', $drug)),
                    'attributes' => json_encode(['allergy_type' => "Drug", 'allergy_category' => "Drug"]),
                    'is_active' => 1
                ]
            );
        }
    }
}
