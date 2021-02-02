<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class FamilyHistoryRelationshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $master_types = [
            [ 'slug' => 'family_history_diseases']
        ];
        
        DB::table('master_types')->insert($master_types);

        $familyHistoryRelationship = [
            ['master_type_slug' => 'family_history_diseases', 'name' =>'Cancer', 'slug' => str_slug('Cancer'), 
            'attributes' =>json_encode([
    "values" => [
      ['relationship' => 'Grandparents'],
      ['relationship' => 'Father'],
      ['relationship' => 'Mother'],
      ['relationship' => 'Brothers'],
      ['relationship' => 'Sisters'],
      ['relationship' => 'Daughters'],
      ['relationship' => 'Sons']
    ]
  ]), 'is_active' => 1],
            ['master_type_slug' => 'family_history_diseases', 'name' =>'Heart Disease', 'slug' => str_slug('Heart Disease'), 
            'attributes' =>json_encode([
    "values" => [
      ['relationship' => 'Grandparents'],
      ['relationship' => 'Father'],
      ['relationship' => 'Mother'],
      ['relationship' => 'Brothers'],
      ['relationship' => 'Sisters'],
      ['relationship' => 'Daughters'],
      ['relationship' => 'Sons']
    ]
  ]), 'is_active' => 1],
            ['master_type_slug' => 'family_history_diseases', 'name' =>'Diabetes', 'slug' => str_slug('Diabetes'), 
            'attributes' =>json_encode([
    "values" => [
      ['relationship' => 'Grandparents'],
      ['relationship' => 'Father'],
      ['relationship' => 'Mother'],
      ['relationship' => 'Brothers'],
      ['relationship' => 'Sisters'],
      ['relationship' => 'Daughters'],
      ['relationship' => 'Sons']
    ]
  ]), 'is_active' => 1],
            ['master_type_slug' => 'family_history_diseases', 'name' =>'Stroke', 'slug' => str_slug('Stroke'), 
            'attributes' =>json_encode([
    "values" => [
      ['relationship' => 'Grandparents'],
      ['relationship' => 'Father'],
      ['relationship' => 'Mother'],
      ['relationship' => 'Brothers'],
      ['relationship' => 'Sisters'],
      ['relationship' => 'Daughters'],
      ['relationship' => 'Sons']
    ]
  ]), 'is_active' => 1],
            ['master_type_slug' => 'family_history_diseases', 'name' =>'High Blood Pressure', 'slug' => str_slug('High Blood Pressure'), 
            'attributes' =>json_encode([
    "values" => [
      ['relationship' => 'Grandparents'],
      ['relationship' => 'Father'],
      ['relationship' => 'Mother'],
      ['relationship' => 'Brothers'],
      ['relationship' => 'Sisters'],
      ['relationship' => 'Daughters'],
      ['relationship' => 'Sons']
    ]
  ]), 'is_active' => 1],
            ['master_type_slug' => 'family_history_diseases', 'name' =>'High Cholestrol', 'slug' => str_slug('High Cholestrol'), 
            'attributes' =>json_encode([
    "values" => [
      ['relationship' => 'Grandparents'],
      ['relationship' => 'Father'],
      ['relationship' => 'Mother'],
      ['relationship' => 'Brothers'],
      ['relationship' => 'Sisters'],
      ['relationship' => 'Daughters'],
      ['relationship' => 'Sons']
    ]
  ]), 'is_active' => 1],
            ['master_type_slug' => 'family_history_diseases', 'name' =>'Liver Disease', 'slug' => str_slug('Liver Disease'), 
            'attributes' =>json_encode([
    "values" => [
      ['relationship' => 'Grandparents'],
      ['relationship' => 'Father'],
      ['relationship' => 'Mother'],
      ['relationship' => 'Brothers'],
      ['relationship' => 'Sisters'],
      ['relationship' => 'Daughters'],
      ['relationship' => 'Sons']
    ]
  ]), 'is_active' => 1],
            ['master_type_slug' => 'family_history_diseases', 'name' =>'Alcohol or Drug Abuse', 'slug' => str_slug('Alcohol or Drug Abuse'), 
            'attributes' =>json_encode([
    "values" => [
      ['relationship' => 'Grandparents'],
      ['relationship' => 'Father'],
      ['relationship' => 'Mother'],
      ['relationship' => 'Brothers'],
      ['relationship' => 'Sisters'],
      ['relationship' => 'Daughters'],
      ['relationship' => 'Sons']
    ]
  ]), 'is_active' => 1],
            ['master_type_slug' => 'family_history_diseases', 'name' =>'Anxiety,Depression or Psychiatric Illness', 'slug' => str_slug('Anxiety,Depression or Psychiatric Illness'), 
            'attributes' =>json_encode([
    "values" => [
      ['relationship' => 'Grandparents'],
      ['relationship' => 'Father'],
      ['relationship' => 'Mother'],
      ['relationship' => 'Brothers'],
      ['relationship' => 'Sisters'],
      ['relationship' => 'Daughters'],
      ['relationship' => 'Sons']
    ]
  ]), 'is_active' => 1],
            ['master_type_slug' => 'family_history_diseases', 'name' =>'Tuberculosis', 'slug' => str_slug('Tuberculosis'), 
            'attributes' =>json_encode([
    "values" => [
      ['relationship' => 'Grandparents'],
      ['relationship' => 'Father'],
      ['relationship' => 'Mother'],
      ['relationship' => 'Brothers'],
      ['relationship' => 'Sisters'],
      ['relationship' => 'Daughters'],
      ['relationship' => 'Sons']
    ]
  ]), 'is_active' => 1],
            ['master_type_slug' => 'family_history_diseases', 'name' =>'Anesthesia', 'slug' => str_slug('Anesthesia'), 
            'attributes' =>json_encode([
    "values" => [
      ['relationship' => 'Grandparents'],
      ['relationship' => 'Father'],
      ['relationship' => 'Mother'],
      ['relationship' => 'Brothers'],
      ['relationship' => 'Sisters'],
      ['relationship' => 'Daughters'],
      ['relationship' => 'Sons']
    ]
  ]), 'is_active' => 1],
            ['master_type_slug' => 'family_history_diseases', 'name' =>'Genetic Disorder', 'slug' => str_slug('Genetic Disorder'), 
            'attributes' =>json_encode([
    "values" => [
      ['relationship' => 'Grandparents'],
      ['relationship' => 'Father'],
      ['relationship' => 'Mother'],
      ['relationship' => 'Brothers'],
      ['relationship' => 'Sisters'],
      ['relationship' => 'Daughters'],
      ['relationship' => 'Sons']
    ]
  ]), 'is_active' => 1],
            ['master_type_slug' => 'family_history_diseases', 'name' =>'Allergies', 'slug' => str_slug('Allergies'), 
            'attributes' =>json_encode([
    "values" => [
      ['relationship' => 'Grandparents'],
      ['relationship' => 'Father'],
      ['relationship' => 'Mother'],
      ['relationship' => 'Brothers'],
      ['relationship' => 'Sisters'],
      ['relationship' => 'Daughters'],
      ['relationship' => 'Sons']
    ]
  ]), 'is_active' => 1],
            ['master_type_slug' => 'family_history_diseases', 'name' =>'Sinus', 'slug' => str_slug('Sinus'), 
            'attributes' =>json_encode([
    "values" => [
      ['relationship' => 'Grandparents'],
      ['relationship' => 'Father'],
      ['relationship' => 'Mother'],
      ['relationship' => 'Brothers'],
      ['relationship' => 'Sisters'],
      ['relationship' => 'Daughters'],
      ['relationship' => 'Sons']
    ]
  ]), 'is_active' => 1],
            ['master_type_slug' => 'family_history_diseases', 'name' =>'Asthma', 'slug' => str_slug('Asthma'), 
            'attributes' =>json_encode([
    "values" => [
      ['relationship' => 'Grandparents'],
      ['relationship' => 'Father'],
      ['relationship' => 'Mother'],
      ['relationship' => 'Brothers'],
      ['relationship' => 'Sisters'],
      ['relationship' => 'Daughters'],
      ['relationship' => 'Sons']
    ]
  ]), 'is_active' => 1],
            ['master_type_slug' => 'family_history_diseases', 'name' =>'Eczema', 'slug' => str_slug('Eczema'), 
            'attributes' =>json_encode([
    "values" => [
      ['relationship' => 'Grandparents'],
      ['relationship' => 'Father'],
      ['relationship' => 'Mother'],
      ['relationship' => 'Brothers'],
      ['relationship' => 'Sisters'],
      ['relationship' => 'Daughters'],
      ['relationship' => 'Sons']
    ]
  ]), 'is_active' => 1],
            ['master_type_slug' => 'family_history_diseases', 'name' =>'Hay Fever', 'slug' => str_slug('Hay Fever'), 
            'attributes' =>json_encode([
    "values" => [
      ['relationship' => 'Grandparents'],
      ['relationship' => 'Father'],
      ['relationship' => 'Mother'],
      ['relationship' => 'Brothers'],
      ['relationship' => 'Sisters'],
      ['relationship' => 'Daughters'],
      ['relationship' => 'Sons']
    ]
  ]), 'is_active' => 1],
            ['master_type_slug' => 'family_history_diseases', 'name' =>'Hives', 'slug' => str_slug('Hives'), 
            'attributes' =>json_encode([
    "values" => [
      ['relationship' => 'Grandparents'],
      ['relationship' => 'Father'],
      ['relationship' => 'Mother'],
      ['relationship' => 'Brothers'],
      ['relationship' => 'Sisters'],
      ['relationship' => 'Daughters'],
      ['relationship' => 'Sons']
    ]
  ]), 'is_active' => 1],
            ['master_type_slug' => 'family_history_diseases', 'name' =>'Migraine', 'slug' => str_slug('Migraine'), 
            'attributes' =>json_encode([
    "values" => [
      ['relationship' => 'Grandparents'],
      ['relationship' => 'Father'],
      ['relationship' => 'Mother'],
      ['relationship' => 'Brothers'],
      ['relationship' => 'Sisters'],
      ['relationship' => 'Daughters'],
      ['relationship' => 'Sons']
    ]
  ]), 'is_active' => 1],
            ['master_type_slug' => 'family_history_diseases', 'name' =>'Thyroid Disease', 'slug' => str_slug('Thyroid Disease'), 
            'attributes' =>json_encode([
    "values" => [
      ['relationship' => 'Grandparents'],
      ['relationship' => 'Father'],
      ['relationship' => 'Mother'],
      ['relationship' => 'Brothers'],
      ['relationship' => 'Sisters'],
      ['relationship' => 'Daughters'],
      ['relationship' => 'Sons']
    ]
  ]), 'is_active' => 1],
            ['master_type_slug' => 'family_history_diseases', 'name' =>'Emphysema', 'slug' => str_slug('Emphysema'), 
            'attributes' =>json_encode([
    "values" => [
      ['relationship' => 'Grandparents'],
      ['relationship' => 'Father'],
      ['relationship' => 'Mother'],
      ['relationship' => 'Brothers'],
      ['relationship' => 'Sisters'],
      ['relationship' => 'Daughters'],
      ['relationship' => 'Sons']
    ]
  ]), 'is_active' => 1],
            ['master_type_slug' => 'family_history_diseases', 'name' =>'Cystic Fibrosis', 'slug' => str_slug('Cystic Fibrosis'), 
            'attributes' =>json_encode([
    "values" => [
      ['relationship' => 'Grandparents'],
      ['relationship' => 'Father'],
      ['relationship' => 'Mother'],
      ['relationship' => 'Brothers'],
      ['relationship' => 'Sisters'],
      ['relationship' => 'Daughters'],
      ['relationship' => 'Sons']
    ]
  ]), 'is_active' => 1],
            ['master_type_slug' => 'family_history_diseases', 'name' =>'Pneumococcal Vaccine', 'slug' => str_slug('Pneumococcal Vaccine'), 
            'attributes' =>json_encode([
    "values" => [
      ['relationship' => 'Grandparents'],
      ['relationship' => 'Father'],
      ['relationship' => 'Mother'],
      ['relationship' => 'Brothers'],
      ['relationship' => 'Sisters'],
      ['relationship' => 'Daughters'],
      ['relationship' => 'Sons']
    ]
  ]), 'is_active' => 1],
            ['master_type_slug' => 'family_history_diseases', 'name' =>'Alive', 'slug' => str_slug('Alive'), 
            'attributes' =>json_encode([
    "values" => [
      ['relationship' => 'Grandparents'],
      ['relationship' => 'Father'],
      ['relationship' => 'Mother'],
      ['relationship' => 'Brothers'],
      ['relationship' => 'Sisters'],
      ['relationship' => 'Daughters'],
      ['relationship' => 'Sons']
    ]
  ]), 'is_active' => 1],
        ];

        DB::table('masters')->insert($familyHistoryRelationship);
    }
}
