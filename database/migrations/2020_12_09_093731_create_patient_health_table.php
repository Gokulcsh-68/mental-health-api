<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientHealthTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patient_health', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id')->comment="user id of staff/student";
            $table->unsignedBigInteger('consult_id')->nullable();
            $table->string('slug', 45);
            $table->json('values');
            $table->timestamps();

            $table->foreign('slug')->references('slug')->on('masters')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('patient_id')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('consult_id')->references('id')->on('consults')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('patient_health', function (Blueprint $table) {
            $table->dropForeign(['slug']);
            $table->dropForeign(['patient_id']);
            $table->dropForeign(['consult_id']);
        });
        
        Schema::dropIfExists('patient_health');
    }
}
