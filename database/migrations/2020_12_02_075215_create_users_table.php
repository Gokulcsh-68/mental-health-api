<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('role_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('isd_code', 5)->nullable();
            $table->string('mobile', 20)->nullable();
            $table->string('username');
            $table->string('secret');
            $table->string('password')->nullable();
            $table->string('profile_image')->nullable();
            $table->string('gender', 45)->nullable();
            $table->date('dob')->nullable();
            $table->string('blood_group', 5)->nullable();
            $table->unsignedInteger('timezone_id');

            $table->json('address')->comment="line,city,state,zipcode";
            $table->char('country_iso', 2)->nullable();

            $table->boolean('is_2fa')->default(0)->comment="0-Disabled, 1-Enabled";
            $table->boolean('is_active')->default(0)->comment="0-Inactive, 1-Active";
            
            $table->timestamps();

            $table->foreign('role_id')->references('id')->on('roles')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('gender')->references('slug')->on('masters')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('timezone_id')->references('id')->on('timezones')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropForeign(['gender']);
            $table->dropForeign(['timezone_id']);
        });

        Schema::dropIfExists('users');
    }
}
