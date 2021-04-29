<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entity', function (Blueprint $table) {
            $table->id('id');
            $table->string('key', 128)->unique();
            $table->string('end_url', 128);
            $table->string('entity_name', 128)->unique()->comment = 'Entity name';
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
        Schema::dropIfExists('entity');
    }
}
