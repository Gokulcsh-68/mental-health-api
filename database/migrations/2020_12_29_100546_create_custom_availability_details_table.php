<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomAvailabilityDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_availability_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('provider_id');
            $table->date('from_date');
            $table->date('to_date');
            $table->json('timing')->nullable();
            $table->timestamps();

            $table->foreign('provider_id')->references('user_id')->on('providers')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('custom_availability_details', function (Blueprint $table) {
            $table->dropForeign(['provider_id']);
        });
        Schema::dropIfExists('custom_availability_details');
    }
}
