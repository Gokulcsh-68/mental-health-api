<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FreezeTablesPhrAndEmrAddon extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        

        Schema::table('immunisations', function (Blueprint $table) {
            $table->boolean('freeze')->default(0)->after('details')->comment="0-Inactive, 1-Active";
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
        Schema::table('immunisations', function (Blueprint $table) {
            $table->dropColumn('freeze');
        });
    }
}
