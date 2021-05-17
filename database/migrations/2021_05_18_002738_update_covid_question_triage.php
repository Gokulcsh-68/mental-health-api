<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCovidQuestionTriage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Triage
        DB::table('questions')->where('name',"No Symptoms that are consistant with Covid19")
            ->update(["name" => 'Symptoms that are consistant with Covid19']);
        DB::table('questions')->where('name',"No shortness of breath")
            ->update(["name" => 'Shortness of breath']);
        DB::table('questions')->where('name',"No difficulty in breathing")
            ->update(["name" => 'Difficulty in breathing']);
        DB::table('questions')->where('name',"No abnormal chest imaging")
            ->update(["name" => 'Abnormal chest imaging']);
        DB::table('questions')->where('name',"No Current Mental Conditions")
            ->update(["name" => 'Current Mental Conditions']);
        DB::table('questions')->where('name',"Correlate with No co-morbidity if any")
            ->update(["name" => 'Co-morbidity like diabetes, hypertension, cardiac, hepatic, renal, etc conditions']);
        DB::table('questions')->where('name',"On Clinical Evaluation or Symptomatic Assessment")
            ->update(["name" => 'Positive clinical signs on evaluation / symptomatic assessment']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
