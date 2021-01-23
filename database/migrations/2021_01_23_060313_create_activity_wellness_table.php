<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivityWellnessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_wellness', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id')->unique();
            $table->date('act_date')->unique();
            $table->time('act_time')->unique()->nullable();
            $table->string('act_catagory')->unique()->nullable();
            $table->string('act_type')->unique()->nullable();
            $table->string('act_duration')->nullable();
            $table->string('act_intensity')->nullable();
            $table->string('act_intake')->nullable();
            $table->string('unit')->nullable();
            $table->integer('status')->unique()->default(1);
            $table->timestamps();


            $table->foreign('patient_id')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('immunisation', function (Blueprint $table) {
            $table->dropForeign(['patient_id']);
        });
        Schema::dropIfExists('activity_wellness');
    }
}
