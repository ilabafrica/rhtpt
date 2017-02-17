<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //  Notifications
        Schema::create('notifications', function(Blueprint $table)
        {
            $table->increments('id')->unsigned();
            $table->tinyInteger('template');
            $table->string('message');
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
        //  Reverse Migrations
        Schema::dropIfExists('notifications');
    }
}
