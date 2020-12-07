<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpecialityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('speciality', function (Blueprint $table) {
            $table->id('id');
            $table->string('code', 32)->unique();
            $table->string('name', 32)->comment = 'Display name';
            $table->boolean('is_active')->default(0)->comment="0-Inactive, 1-Active";
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('speciality');
    }
}
