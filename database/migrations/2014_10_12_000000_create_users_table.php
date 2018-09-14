<?php

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
          $table->increments('id')->unsigned();
          $table->string('name');
          $table->string('last_name', 100)->nullable();
          $table->string('middle_name', 100)->nullable();
          $table->string('first_name', 100)->nullable();
          $table->tinyInteger("gender")->default(0);
          $table->string('email')->nullable();
          $table->string('phone')->nullable();
          $table->string('address')->nullable();
          $table->string("username", 50)->unique();
          $table->string('password', 60);
          $table->string("image", 100)->nullable();
          $table->integer('uid')->nullable();
          $table->string('sms_code')->nullable();
          $table->boolean('phone_verified')->default(false);
          $table->string('email_verification_code')->nullable();
          $table->boolean('email_verified')->default(false);
          $table->rememberToken();

          $table->softDeletes();
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
        Schema::drop('users');
    }
}
