<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PtSetupTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //  Sample Preparation
        Schema::create('sample_preparation', function(Blueprint $table)
    		{
      			$table->increments('id')->unsigned();
            $table->string('pt_identifier', 25);
      			$table->string('batch', 50);
      			$table->date('date_prepared');
            $table->date('expiry_date');
            $table->smallInteger('material_type');
            $table->string('original_source');
            $table->date('date_collected');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->softDeletes();
      			$table->timestamps();
    		});
        //  PT Rounds
        Schema::create('rounds', function(Blueprint $table)
    		{
      			$table->increments('id')->unsigned();
      			$table->string('panel_name');
      			$table->string('round_name');
      			$table->string('description', 100)->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->softDeletes();
      			$table->timestamps();
    		});
        //  Shipments
        Schema::create('shipments', function(Blueprint $table)
    		{
      			$table->increments('id')->unsigned();
            $table->integer('round_id')->unsigned();
            $table->date('date_prepared');
            $table->date('date_shipped');
            $table->smallInteger('shipping_method');
      			$table->string('courier');
            $table->integer('participant')->unsigned();
      			$table->integer('panels_shipped');
            $table->foreign('round_id')->references('id')->on('rounds');
            $table->foreign('participant')->references('id')->on('users');
            $table->softDeletes();
      			$table->timestamps();
    		});
        //  Receive Samples
        Schema::create('reception', function(Blueprint $table)
    		{
      			$table->increments('id')->unsigned();
            $table->integer('round_id')->unsigned();
            $table->dateTime('date_received');
            $table->integer('panels_received');
      			$table->string('condition', 500);
            $table->string('storage', 500);
            $table->decimal('transit_temperature', 5, 2);
            $table->integer('recipient')->unsigned();
            $table->foreign('round_id')->references('id')->on('rounds');
            $table->foreign('recipient')->references('id')->on('users');
            $table->softDeletes();
      			$table->timestamps();
    		});
        //  Results Entry = 1 and Expected Results = 0
        Schema::create('pt', function(Blueprint $table)
    		{
    			$table->increments('id')->unsigned();
    			$table->integer('round_id')->unsigned();
          $table->integer('user_id')->unsigned();
          $table->smallInteger('result');
          $table->foreign('round_id')->references('id')->on('rounds');
          $table->foreign('user_id')->references('id')->on('users');
          $table->softDeletes();
    			$table->timestamps();
    		});
        //  Actual Analysis results
        Schema::create('results', function(Blueprint $table)
    		{
    			$table->increments('id')->unsigned();
    			$table->integer('pt_id')->unsigned();
          $table->integer('field_id')->unsigned();
    			$table->string('response');
    			$table->string('comment')->nullable();
          $table->foreign('pt_id')->references('id')->on('pt');
          $table->foreign('field_id')->references('id')->on('fields');
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
      //  Reverse migrations
      Schema::dropIfExists('results');
      Schema::dropIfExists('pt');
      Schema::dropIfExists('reception');
      Schema::dropIfExists('shipments');
      Schema::dropIfExists('rounds');
      Schema::dropIfExists('sample_preparation');
    }
}
