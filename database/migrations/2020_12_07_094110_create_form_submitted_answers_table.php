<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormSubmittedAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_submitted_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('form_id');
            $table->unsignedBigInteger('patient_id');
            $table->json('answers')->nullable();
            $table->decimal('score', 4, 2)->nullable();
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

            $table->foreign('form_id')->references('id')->on('forms')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('patient_id')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('form_submitted_answers', function (Blueprint $table) {
            $table->dropForeign(['form_id']);
            $table->dropForeign(['patient_id']);
            $table->dropForeign(['created_by']);
        });
        Schema::dropIfExists('form_submitted_answers');
    }
}
