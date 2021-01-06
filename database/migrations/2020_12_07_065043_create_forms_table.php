<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('slug');
            $table->string('name', 512)->unique();
            $table->text('desc');
            $table->string('assessment_group', 45)->nullable();
            $table->enum('type', ['normal', 'score'])->default('normal');
            $table->json('images')->nullable();
            $table->boolean('is_active')->default(1)->comment="0-Inactive, 1-Active";
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->unique(['parent_id', 'slug']);

            $table->foreign('assessment_group')->references('slug')->on('masters')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('parent_id')->references('id')->on('forms')->onDelete('restrict')->onUpdate('cascade');
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
        Schema::table('forms', function (Blueprint $table) {
            $table->dropForeign(['assessment_group']);
            $table->dropForeign(['parent_id']);
            $table->dropForeign(['created_by']);
        });
        Schema::dropIfExists('forms');

    }
}
