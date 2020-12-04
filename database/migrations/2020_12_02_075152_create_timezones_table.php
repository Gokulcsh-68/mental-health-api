<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimeZonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    # currency, timezone, telephone code
    public function up()
    {
        Schema::create('timezones', function (Blueprint $table) {
            $table->increments('id');
            $table->char('country_code', 2);
            $table->string('zone_name', 128)->unique();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('timezones');
    }
}
