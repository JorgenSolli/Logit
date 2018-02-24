<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->string('provider')->nullable();
            $table->string('provider_id')->unique()->nullable();
            $table->boolean('first_time')->default(1);
            $table->string('token')->nullable();
            $table->string('name');
            $table->string('email', 250)->unique();
            $table->integer('yob')->nullable();
            $table->string('goal')->nullable();
            $table->string('avatar')->nullable();
            $table->string('gender')->nullable();
            $table->string('country')->nullable();
            $table->string('password');
            $table->boolean('verified')->default(false);
            $table->string('verification_token')->nullable();
            $table->boolean('is_admin')->default(0);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
