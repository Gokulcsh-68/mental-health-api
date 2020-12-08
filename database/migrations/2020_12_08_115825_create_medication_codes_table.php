<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicationCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medication_codes', function (Blueprint $table) {
            $table->id();
            $table->integer('catelog_no')->nullable();
            $table->integer('strength')->nullable();
            $table->string('name');
            $table->string('dosage')->nullable();
            $table->char('iso_code', 2)->nullable();
            $table->boolean('is_generic')->default(0)->comment="0-Brand, 1-Generic";
            $table->boolean('is_active')->default(1)->comment="0-Inactive, 1-Active";
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
        Schema::dropIfExists('medication_codes');
    }
}
