<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterForeignToAllTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dynamic_forms', function (Blueprint $table) {
            $table->dropForeign(['slug']);

            $table->foreign('slug')->references('slug')->on('masters')->onDelete('cascade')->onUpdate('cascade');
        });


        Schema::table('patient_health', function (Blueprint $table) {
            $table->dropForeign(['slug']);

            $table->foreign('slug')->references('slug')->on('masters')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('physical_examinations', function (Blueprint $table) {
            $table->dropForeign(['slug']);

            $table->foreign('slug')->references('slug')->on('masters')->onDelete('cascade')->onUpdate('cascade');
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
            //
            $table->dropForeign(['slug']);
        });

        Schema::table('patient_health', function (Blueprint $table) {
            //
            $table->dropForeign(['slug']);
        }); 

        Schema::table('physical_examinations', function (Blueprint $table) {
            //
            $table->dropForeign(['slug']);
        });
    }
}
