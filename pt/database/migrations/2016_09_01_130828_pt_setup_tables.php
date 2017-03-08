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
      			$table->string('description', 100)->nullable();
            $table->softDeletes();
      			$table->timestamps();
    		});
        //	User tiers - county/sub-county/facility
    		Schema::create('user_tiers', function(Blueprint $table)
    		{
      			$table->increments('id')->unsigned();
      			$table->integer('user_id')->unsigned();
      			$table->integer('role_id')->unsigned();
            $table->integer('tier');
      			$table->integer('program_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('role_id')->references('id')->on('roles');
            $table->unique(array('user_id','role_id'));
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
            $table->string('prepared_by');
            $table->integer('user_id')->unsigned();
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
            $table->smallInteger('tester_id_range');
      			$table->string('pt_id');
      			$table->integer('material_id')->unsigned();
      			$table->integer('round_id')->unsigned();
            $table->string('prepared_by');
            $table->integer('user_id')->unsigned();
            $table->foreign('material_id')->references('id')->on('materials');
            $table->foreign('round_id')->references('id')->on('rounds');
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
            $table->string('tested_by');
            $table->integer('user_id')->unsigned();
            $table->foreign('item_id')->references('id')->on('items');
            $table->foreign('user_id')->references('id')->on('users');
            $table->softDeletes();
      			$table->timestamps();
    		});
        //  Shippers
        Schema::create('shippers', function(Blueprint $table)
    		{
      			$table->increments('id')->unsigned();
            $table->smallInteger('shipper_type');
      			$table->string('name');
      			$table->string('contact', 100);
            $table->string('phone', 12)->nullable();
            $table->string('email', 50)->nullable();
            $table->softDeletes();
      			$table->timestamps();
    		});
        //  Shipper-facilties
        Schema::create('shipper_facilities', function(Blueprint $table)
    		{
      			$table->increments('id')->unsigned();
            $table->integer('shipper_id')->unsigned();
            $table->integer('facility_id')->unsigned();
            $table->foreign('shipper_id')->references('id')->on('shippers');
            $table->foreign('facility_id')->references('id')->on('facilities');
            $table->unique(array('shipper_id','facility_id'));
    		});
        //  Shipments
        Schema::create('shipments', function(Blueprint $table)
    		{
      			$table->increments('id')->unsigned();
            $table->integer('round_id')->unsigned();
            $table->date('date_prepared');
            $table->date('date_shipped');
            $table->integer('shipper_id')->unsigned();
            $table->string('shipping_method')->nullable();
            $table->integer('facility_id')->unsigned();
      			$table->string('panels_shipped');
            $table->integer('user_id')->unsigned();
            $table->foreign('round_id')->references('id')->on('rounds');
            $table->foreign('shipper_id')->references('id')->on('shippers');
            $table->foreign('facility_id')->references('id')->on('facilities');
            $table->foreign('user_id')->references('id')->on('users');
            $table->softDeletes();
      			$table->timestamps();
    		});
        //  Receive Samples
        Schema::create('receipts', function(Blueprint $table)
    		{
      			$table->increments('id')->unsigned();
            $table->integer('shipment_id')->unsigned();
            $table->date('date_received');
            $table->string('panels_received');
      			$table->string('condition', 500);
            $table->string('receiver', 100);
            $table->foreign('shipment_id')->references('id')->on('shipments');
            $table->softDeletes();
      			$table->timestamps();
    		});
        //  Results Entry
        Schema::create('pt', function(Blueprint $table)
    		{
    			$table->increments('id')->unsigned();
    			$table->integer('round_id')->unsigned();
          $table->integer('user_id')->unsigned();
          $table->smallInteger('feedback')->nullable();
          $table->smallInteger('panel_status');
          $table->tinyInteger('checks')->default(0);
          $table->tinyInteger('panel_result')->default(0);
          $table->tinyInteger('incorrect_results')->default(0);
          $table->tinyInteger('incomplete_kit_data')->default(0);
          $table->tinyInteger('dev_from_procedure')->default(0);
          $table->tinyInteger('incomplete_other_information')->default(0);
          $table->tinyInteger('use_of_expired_kits')->default(0);
          $table->tinyInteger('invalid_results')->default(0);
          $table->tinyInteger('wrong_algorithm')->default(0);
          $table->tinyInteger('incomplete_results')->default(0);
          $table->string('comment', 250)->nullable();
          $table->integer('verified_by')->nullable();
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
      Schema::dropIfExists('receipts');
      Schema::dropIfExists('shipments');
      Schema::dropIfExists('shipper_facilities');
      Schema::dropIfExists('shippers');
      Schema::dropIfExists('expected_results');
      Schema::dropIfExists('items');
      Schema::dropIfExists('rounds');
      Schema::dropIfExists('user_tiers');
      Schema::dropIfExists('materials');
      Schema::dropIfExists('programs');
    }
}
