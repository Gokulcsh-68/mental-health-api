<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAvailabilityDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('availability_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('provider_id');
            $table->datetime('from_date_time');
            $table->datetime('to_date_time');
            $table->integer('duration');
            $table->integer('slot_group')->comment="1-Queue Slot, 2-Time Slot";
            $table->string('available_type');
            $table->tinyInteger('slot_type')->comment="1-appointment, 2-queue";
            $table->string('slot_status');
            $table->string('available_status');
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

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
