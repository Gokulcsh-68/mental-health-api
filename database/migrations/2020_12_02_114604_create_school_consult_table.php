<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchoolConsultTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consults', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id')->comment="user id of staff/student";
            $table->unsignedBigInteger('provider_id');
            $table->unsignedBigInteger('school_id');
            $table->unsignedBigInteger('class_id');
            $table->string('consult_type', 16);
            $table->string('consult_slot_type');
            $table->dateTime('consult_date_time', 0);
            $table->string('consult_duration', 64);
            $table->string('speciality', 45);

            $table->foreign('patient_id')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('provider_id')->references('id')->on('providers')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('class_id')->references('id')->on('classes')->onDelete('restrict')->onUpdate('cascade');
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
        Schema::table('consults', function (Blueprint $table) {
            $table->dropForeign(['patient_id']);
            $table->dropForeign(['provider_id']);
            $table->dropForeign(['school_id']);
            $table->dropForeign(['class_id']);
        });

        Schema::dropIfExists('consults');
    }
}
