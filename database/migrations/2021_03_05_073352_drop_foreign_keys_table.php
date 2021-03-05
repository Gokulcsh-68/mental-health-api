<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropForeignKeysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::table('patient_histories', function (Blueprint $table) {
            $table->dropForeign(['consult_id']);
        });

        Schema::table('patient_health', function (Blueprint $table) {
            $table->dropForeign(['consult_id']);
        });
        
        Schema::table('vitals', function (Blueprint $table) {
            $table->dropForeign(['consult_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
