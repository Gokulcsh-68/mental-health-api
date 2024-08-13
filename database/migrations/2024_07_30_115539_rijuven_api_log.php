<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RijuvenApiLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rijuven_api_log', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('patient_id')->nullable();
            $table->bigInteger('rijuven_patient_id')->nullable();
            $table->string('action')->nullable();
            $table->json('data')->nullable();
            $table->string('notes')->nullable();
            $table->tinyInteger('status')->default(0)->comment='0 - pending, 1 - data processed';
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rijuven_api_log');
    }
}
