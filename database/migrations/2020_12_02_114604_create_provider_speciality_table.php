<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProviderSpecialityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('provider_specialities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('provider_id');
            $table->string('speciality', 45);
            $table->unsignedBigInteger('school_id');
            $table->timestamps();
            
            $table->foreign('provider_id')->references('id')->on('providers')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('speciality')->references('slug')->on('masters')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('provider_specialities', function (Blueprint $table) {
            $table->dropForeign(['provider_id']);
            $table->dropForeign(['speciality']);
            $table->dropForeign(['school_id']);
        });

        Schema::dropIfExists('provider_specialities');
    }
}
