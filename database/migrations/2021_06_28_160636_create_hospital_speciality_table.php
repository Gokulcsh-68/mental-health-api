<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHospitalSpecialityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hospital_specialities', function (Blueprint $table) {
            $table->unsignedBigInteger('hospital_id');
            $table->string('speciality', 45);
            $table->timestamps();

            $table->unique(['hospital_id', 'speciality']);
            
            $table->foreign('hospital_id')->references('id')->on('hospitals')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('speciality')->references('slug')->on('masters')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hospital_specialities', function (Blueprint $table) {
            $table->dropForeign(['hospital_id']);
            $table->dropForeign(['speciality']);
        });

        Schema::dropIfExists('hospital_specialities');
    }
}
