<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RolesTableSeeder::class,
            MasterTypesTableSeeder::class,
            CountriesSeeder::class,
            DietMasterTableSeeder::class,
            DocumentSourceMasterTableSeeder::class,
            SpecialityTableSeeder::class,
            GenderSeeder::class,
            TimezonesTableSeeder::class,
            VitalSeeder::class,
            DynamicFormsMasterTableSeeder::class,
            DynamicFormsTableSeeder::class,
            AssessmentGroupSeeder::class,// Assement Forms Group
            HistorySeeder::class,
            HealthSeeder::class,
            ImmunisationSeeder::class,
            ConsultMenuSeeder::class,
            AssementFormSeeder::class,// Assement Forms
            AllergySeeder::class,
            ReactionSeeder::class,
            ConditionSeeder::class,
            ProcedureSeeder::class,
            ActivitySeeder::class,
            OccupationSeeder::class,
            LivingRelationshipSeeder::class,
            FamilyHistoryRelationshipSeeder::class,
            ImagingSeeder::class,
            LabSeeder::class,
            MeasurementStrengthSeeder::class,
            MedicationBrandSeeder::class,
            MedicationGenericSeeder::class,
            UsMedicineSeeder::class,
            UsLabSeeder::class,
            AssementFormADHDSeeder::class,
            AssementFormApgarSeeder::class,
            HealthFormSeeder::class,
            IcdSeeder::class,
            AdminUserSeeder::class,
            AssessmentCovidSeeder::class,
            AssessmentCovidSeederUpdate::class,
            AssessmentCovidTriageSeeder::class,
            AssessmentCovidInvestigationSeeder::class,
            AssessmentCovidCtScoringSeeder::class,
            SigSeeder::class,
            UpdateImmunisationSeeder::class,
            UpdateAllergySeeder::class,
            UpdateApiServiceSeeder::class,
            UpdateVitalsSeeder::class,
            SymptomSeeder::class,
            AssessmentPsychiatricExamPhobia::class,
            AddHealthFormSeeder::class,
            AssessmentPsychiatricExamAutismSpectrumDisorder::class,
            AssessmentPsychiatricExamSocialCommunicationDisorder::class,
            AssessmentPsychiatricExamPsychosisSymptomSeverity::class,
            AssessmentPsychiatricExamConductDisorder::class,
            AssessmentPsychiatricExamNonsuicidalSelfInjury::class,
            AssessmentPsychiatricExamOppositionalDefiantDisorder::class,
            AssessmentPsychiatricExamSomaticSymptomDisorder::class,
            SymptomUpdateNewSeeder::class,
            UpdateRolesSeeder::class
        ]);
    }
}
