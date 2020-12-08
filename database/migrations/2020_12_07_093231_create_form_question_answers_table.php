<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormQuestionAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_question_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('form_question_id');
            $table->unsignedBigInteger('answer_id')->nullable();
            $table->integer('jump_to_question_id')->nullable();
            $table->integer('score')->nullable();
            $table->integer('order')->nullable();
            $table->enum('type', ['ordinary', 'others'])->default('ordinary');
            $table->text('label')->nullable();
            $table->timestamps();

            $table->foreign('form_question_id')->references('id')->on('forms')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('answer_id')->references('id')->on('answers')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('form_question_answers', function (Blueprint $table) {
            $table->dropForeign(['form_question_id']);
            $table->dropForeign(['answer_id']);
        });
        Schema::dropIfExists('form_question_answers');
    }
}
