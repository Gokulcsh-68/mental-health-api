<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFamilyHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('family_histories', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->unsignedBigInteger('patient_id');
            $table->json('details');
            $table->timestamps();

            $table->foreign('patient_id')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');

            $table->foreign('slug')->references('slug')->on('masters')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('family_histories', function (Blueprint $table) {
            $table->dropForeign(['patient_id']);
            $table->dropForeign(['slug']);
        });

        Schema::dropIfExists('family_histories');
    }
}
