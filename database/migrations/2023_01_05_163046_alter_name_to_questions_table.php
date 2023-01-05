<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterNameToQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('questions', function (Blueprint $table) {
           $table->string('name', 512)->change();       
        });

        try {
            DB::transaction(function () {
                DB::statement("ALTER TABLE `questions` MODIFY COLUMN `type` ENUM('input', 'select', 'checkbox', 'radio', 'sub_question', 'instructions', 'other') NOT NULL DEFAULT 'input'");
            });
        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('questions', function (Blueprint $table) {
            
            $table->dropColumn('name');
        });
    }
}
