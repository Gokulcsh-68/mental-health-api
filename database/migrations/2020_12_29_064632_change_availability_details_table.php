<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeAvailabilityDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*Schema::table('availability_details', function (Blueprint $table) {
            $table->dropForeign(['provider_id']);
            $table->dropForeign(['created_by']);
        });*/

        Schema::dropIfExists('availability_details');
        Schema::create('availability_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('provider_id');
            $table->string('day');
            $table->json('timing')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

            $table->unique(['provider_id', 'day']);
            
            $table->foreign('provider_id')->references('id')->on('providers')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('availability_details', function (Blueprint $table) {
            $table->dropForeign(['provider_id']);
            $table->dropForeign(['created_by']);
        });

        Schema::dropIfExists('availability_details');
    }
}
