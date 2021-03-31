<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHospitalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hospitals', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->unsignedBigInteger('group_id')->nullable();
            $table->string('reg_no')->comment='Registration Number';
            $table->string('logo', 255)->nullable();
            $table->json('additional_info')->nullable();
            $table->timestamps();

            $table->foreign('group_id')->references('id')->on('hospital_groups')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hospitals', function (Blueprint $table) {
            $table->dropForeign(['group_id']);
        });
        Schema::dropIfExists('hospitals');
    }
}
