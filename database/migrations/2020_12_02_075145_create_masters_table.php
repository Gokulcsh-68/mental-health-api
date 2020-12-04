<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('masters', function (Blueprint $table) {
            $table->id();
            $table->string('master_type_slug', 45);
            $table->string('name');
            $table->string('slug', 45);
            $table->json('attributes')->nullable();
            $table->boolean('is_active')->default(0)->comment="0-Inactive, 1-Active";
            $table->timestamps();

            $table->unique(['master_type_slug', 'slug']);
            $table->index('slug');
            $table->foreign('master_type_slug')->references('slug')->on('master_types')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('masters', function (Blueprint $table) {
            $table->dropForeign(['master_type_slug']);
        });

        Schema::dropIfExists('masters');
    }
}
