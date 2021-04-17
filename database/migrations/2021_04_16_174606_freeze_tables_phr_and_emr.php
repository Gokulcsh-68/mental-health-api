<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FreezeTablesPhrAndEmr extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vitals', function (Blueprint $table) {
            $table->boolean('freeze')->default(0)->after('details')->comment="0-Inactive, 1-Active";
        });

        Schema::table('patient_histories', function (Blueprint $table) {
            $table->boolean('freeze')->default(0)->after('values')->comment="0-Inactive, 1-Active";
        });

        Schema::table('physical_examinations', function (Blueprint $table) {
            $table->boolean('freeze')->default(0)->after('values')->comment="0-Inactive, 1-Active";
        });

        Schema::table('review_of_systems', function (Blueprint $table) {
            $table->boolean('freeze')->default(0)->after('values')->comment="0-Inactive, 1-Active";
        });

        Schema::table('patient_health', function (Blueprint $table) {
            $table->boolean('freeze')->default(0)->after('values')->comment="0-Inactive, 1-Active";
        });

        Schema::table('docs', function (Blueprint $table) {
            $table->boolean('freeze')->default(0)->after('addition_info')->comment="0-Inactive, 1-Active";
        });

        Schema::table('activity_wellness', function (Blueprint $table) {
            $table->boolean('freeze')->default(0)->after('unit')->comment="0-Inactive, 1-Active";
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
            $table->dropColumn('freeze');
        });

        Schema::table('patient_histories, ', function (Blueprint $table) {
            $table->dropColumn('freeze');
        });

        Schema::table('physical_examinations', function (Blueprint $table) {
            $table->dropColumn('freeze');
        });

        Schema::table('review_of_systems', function (Blueprint $table) {
            $table->dropColumn('freeze');
        });

        Schema::table('patient_health', function (Blueprint $table) {
            $table->dropColumn('freeze');
        });

        Schema::table('docs', function (Blueprint $table) {
            $table->dropColumn('freeze');
        });

        Schema::table('activity_wellness', function (Blueprint $table) {
            $table->dropColumn('freeze');
        });
    }
}
