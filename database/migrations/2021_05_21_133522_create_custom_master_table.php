<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateCustomMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_masters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('provider_id');
            $table->string('master_type_slug', 45);
            $table->string('name', 255);
            $table->string('slug', 45);
            $table->json('attributes')->nullable();
            $table->boolean('is_active')->default(0)->comment="0-Inactive, 1-Active";
            $table->unique(['master_type_slug', 'slug', 'provider_id']);
            $table->index('slug');
            $table->foreign('master_type_slug')->references('slug')->on('master_types')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('provider_id')->references('id')->on('providers')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('custom_masters', function (Blueprint $table) {
            $table->dropForeign(['master_type_slug']);
        });

        Schema::dropIfExists('custom_masters');
    }
}


