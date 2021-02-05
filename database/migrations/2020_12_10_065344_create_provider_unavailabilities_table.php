<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProviderUnavailabilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('provider_unavailabilities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('provider_id');
            $table->date('from_date');
            $table->json('timing')->nullable();
            $table->string('available_type')->nullable();
            $table->integer('available_status')->default(1);
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

            $table->foreign('provider_id')->references('user_id')->on('providers')->onDelete('restrict')->onUpdate('cascade');
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
        Schema::table('provider_unavailabilities', function (Blueprint $table) {
            $table->dropForeign(['provider_id']);
            $table->dropForeign(['created_by']);
        });

        Schema::dropIfExists('provider_unavailabilities');
    }
}
