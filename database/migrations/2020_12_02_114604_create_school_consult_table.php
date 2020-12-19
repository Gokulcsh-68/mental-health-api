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
            $table->string('unique_id');
            $table->tinyInteger('patient_in_room');
            $table->tinyInteger('provider_in_room');
            $table->unsignedBigInteger('patient_id')->comment = "user id of staff/student";
            $table->unsignedBigInteger('provider_id');
            $table->unsignedBigInteger('school_id');
            $table->unsignedBigInteger('class_id');
            $table->enum('consult_type', ['Video', 'Voice'])->default('Video');
            $table->string('consult_slot_type');
            $table->dateTime('consult_date_time', 0);
            $table->string('consult_duration', 64);
            $table->string('speciality', 45);
            $table->integer('unit');
            $table->json('slots');
            $table->dateTime('started_date_time');
            $table->dateTime('ended_date_time');
            $table->json('consent')->nullable();
            $table->unsignedBigInteger('camera_id')->nullable();
            $table->text('consult_notes');
            $table->text('Addendum_notes');
            $table->string('reason_for_consult');
            $table->string('status');

            $table->timestamps();

            $table->foreign('patient_id')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('provider_id')->references('id')->on('providers')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('class_id')->references('id')->on('school_classes')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('speciality')->references('slug')->on('masters')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('camera_id')->references('id')->on('cameras')->onDelete('restrict')->onUpdate('cascade');
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
            $table->dropForeign(['speciality']);
            $table->dropForeign(['camera_id']);
        });

        Schema::dropIfExists('consults');
    }
}
