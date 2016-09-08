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
        //  Program
        Schema::create('programs', function(Blueprint $table)
    		{
      			$table->increments('id')->unsigned();
      			$table->string('name');
      			$table->string('label')->nullable();
      			$table->string('description', 100)->nullable();
            $table->softDeletes();
      			$table->timestamps();
    		});
        //  Sample Preparation
        Schema::create('materials', function(Blueprint $table)
    		{
      			$table->increments('id')->unsigned();
      			$table->string('batch', 50);
      			$table->date('date_prepared');
            $table->date('expiry_date');
            $table->smallInteger('material_type');
            $table->string('original_source');
            $table->date('date_collected');
            $table->integer('prepared_by')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->foreign('prepared_by')->references('id')->on('users');
            $table->foreign('user_id')->references('id')->on('users');
            $table->softDeletes();
      			$table->timestamps();
    		});
        //  PT Rounds
        Schema::create('rounds', function(Blueprint $table)
    		{
      			$table->increments('id')->unsigned();
      			$table->string('name');
      			$table->string('description', 100)->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->softDeletes();
      			$table->timestamps();
    		});
        //  PT item
        Schema::create('items', function(Blueprint $table)
    		{
      			$table->increments('id')->unsigned();
            $table->integer('program_id')->unsigned();
      			$table->string('pt_id');
      			$table->integer('material_id')->unsigned();
      			$table->integer('round_id')->unsigned();
            $table->integer('prepared_by')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->foreign('program_id')->references('id')->on('programs');
            $table->foreign('material_id')->references('id')->on('materials');
            $table->foreign('round_id')->references('id')->on('rounds');
            $table->foreign('prepared_by')->references('id')->on('users');
            $table->foreign('user_id')->references('id')->on('users');
            $table->softDeletes();
      			$table->timestamps();
    		});
        //  Expected Results
        Schema::create('expected_results', function(Blueprint $table)
    		{
      			$table->increments('id')->unsigned();
      			$table->integer('item_id')->unsigned();
            $table->smallInteger('result');
            $table->integer('tested_by')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->foreign('item_id')->references('id')->on('items');
            $table->foreign('tested_by')->references('id')->on('users');
            $table->foreign('user_id')->references('id')->on('users');
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
      			$table->string('panels_shipped');
            $table->integer('user_id')->unsigned();
            $table->foreign('round_id')->references('id')->on('rounds');
            $table->foreign('participant')->references('id')->on('users');
            $table->foreign('user_id')->references('id')->on('users');
            $table->softDeletes();
      			$table->timestamps();
    		});
        //  Receive Samples
        Schema::create('receipts', function(Blueprint $table)
    		{
      			$table->increments('id')->unsigned();
            $table->integer('shipment_id')->unsigned();
            $table->dateTime('date_received');
            $table->string('panels_received');
      			$table->string('condition', 500);
            $table->string('storage', 500);
            $table->decimal('transit_temperature', 5, 2);
            $table->string('recipient', 100);
            $table->foreign('shipment_id')->references('id')->on('shipments');
            $table->softDeletes();
      			$table->timestamps();
    		});
        //  Results Entry
        Schema::create('pt', function(Blueprint $table)
    		{
    			$table->increments('id')->unsigned();
    			$table->integer('receipt_id')->unsigned();
          $table->integer('user_id')->unsigned();
          $table->smallInteger('feedback')->nullable();
          $table->smallInteger('panel_status');
          $table->string('comment', 250);
          $table->integer('verified_by')->nullable();
          $table->foreign('receipt_id')->references('id')->on('receipts');
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
      Schema::dropIfExists('receipts');
      Schema::dropIfExists('shipments');
      Schema::dropIfExists('expected_results');
      Schema::dropIfExists('items');
      Schema::dropIfExists('rounds');
      Schema::dropIfExists('materials');
      Schema::dropIfExists('programs');
    }
}
