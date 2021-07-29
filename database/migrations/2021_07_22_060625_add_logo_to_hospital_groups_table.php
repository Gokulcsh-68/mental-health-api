<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLogoToHospitalGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hospital_groups', function (Blueprint $table) {
            //
            $table->json('logo')->nullable()->comment='ext,name,mime_type,original_name,url';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hospital_groups', function (Blueprint $table) {
            //
            $table->dropColumn('logo');
        });
    }
}
