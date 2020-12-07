<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVitalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vitals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->comment='staff/student user id';
            $table->unsignedBigInteger('consult_id')->nullable();
            $table->unsignedBigInteger('peripheral_id')->nullable();
            $table->string('slug', 45)->nullable();
            $table->json('details');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('consult_id')->references('id')->on('consults')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('slug')->references('slug')->on('masters')->onDelete('restrict')->onUpdate('cascade');
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
        Schema::table('vitals', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['consult_id']);
            $table->dropForeign(['slug']);
        });

        Schema::dropIfExists('vitals');
    }
}
