<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDynamicFormsSubmittedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dynamic_forms_submitted', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 45);
            $table->json('values');
            $table->timestamps();

            $table->foreign('slug')->references('slug')->on('dynamic_forms')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dynamic_forms', function (Blueprint $table) {
            $table->dropForeign(['slug']);
        });

        Schema::dropIfExists('dynamic_forms_submitted');
    }
}
