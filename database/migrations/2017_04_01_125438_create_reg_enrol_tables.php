<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegEnrolTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //  Capture reasons for non-performance
        Schema::create('nonperformance', function(Blueprint $table)
        {
            $table->increments('id')->unsigned();
            $table->string('title', 100);
            $table->string('description', 200);

            $table->softDeletes();
            $table->timestamps();
        });
        //  Capture details for self-registering participants
        Schema::create('registrations', function(Blueprint $table)
        {
            $table->increments('id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->integer('uid');
            $table->integer('nonperformance_id')->unsigned();
            $table->string('comment', 100)->nullable();

            $table->softDeletes();
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('nonperformance_id')->references('id')->on('nonperformance');
        });
        //  Capture details of enrolments
        Schema::create('enrolments', function(Blueprint $table)
        {
            $table->increments('id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->integer('round_id')->unsigned();

            $table->softDeletes();
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('round_id')->references('id')->on('rounds');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //  Reverse migrations
        Schema::dropIfExists('enrolments');
        Schema::dropIfExists('registration');
        Schema::dropIfExists('nonperformance');
    }
}
