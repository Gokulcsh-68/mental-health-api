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
            $table->unsignedBigInteger('patient_id');
            $table->date('act_date');
            $table->time('act_time')->nullable();
            $table->string('act_catagory')->nullable();
            $table->string('act_type')->nullable();
            $table->string('act_duration')->nullable();
            $table->string('act_intensity')->nullable();
            $table->string('act_intake')->nullable();
            $table->string('unit')->nullable();
            $table->integer('status')->default(1);
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
        Schema::table('activity_wellness', function (Blueprint $table) {
            $table->dropForeign(['patient_id']);
        });
        
        Schema::dropIfExists('activity_wellness');
    }
}
