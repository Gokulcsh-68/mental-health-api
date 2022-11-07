<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterForeignToMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('review_of_systems', function (Blueprint $table) {
            $table->dropForeign(['slug']);

            $table->foreign('slug')->references('slug')->on('masters')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('masters', function (Blueprint $table) {
            $table->dropForeign(['master_type_slug']);

            $table->foreign('master_type_slug')->references('slug')->on('master_types')->onDelete('cascade')->onUpdate('cascade');

        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {


        Schema::table('review_of_systems', function (Blueprint $table) {
            //
            $table->dropForeign(['slug']);
        });
        
        Schema::table('masters', function (Blueprint $table) {
            $table->dropForeign(['master_type_slug']);
            
        });
    }
}
