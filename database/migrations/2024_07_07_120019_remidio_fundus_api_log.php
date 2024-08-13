<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemidioFundusApiLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('remidio_fundus_api_log', function (Blueprint $table) {
            $table->id();
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
        Schema::dropIfExists('remidio_fundus_api_log');
    }
}
