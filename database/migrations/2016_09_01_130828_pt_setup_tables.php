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
        //  Lots
        Schema::create('lots', function(Blueprint $table)
    		{
      			$table->increments('id')->unsigned();
            $table->integer('round_id')->unsigned();
      			$table->smallInteger('lot');
      			$table->string('tester_id', 25);
            $table->integer('user_id')->unsigned();
            $table->foreign('round_id')->references('id')->on('rounds');
            $table->foreign('user_id')->references('id')->on('users');
            $table->softDeletes();
      			$table->timestamps();
    		});
        //  PT panels
        Schema::create('panels', function(Blueprint $table)
    		{
      			$table->increments('id')->unsigned();
      			$table->integer('lot_id')->unsigned();
      			$table->smallInteger('panel');
      			$table->integer('material_id')->unsigned();
            $table->smallInteger('result');
            $table->string('prepared_by');
            $table->string('tested_by');
            $table->integer('user_id')->unsigned();
            $table->foreign('material_id')->references('id')->on('materials');
            $table->foreign('lot_id')->references('id')->on('lots');
            $table->foreign('user_id')->references('id')->on('users');
            $table->softDeletes();
      			$table->timestamps();
    		});
        //  Shipping agents
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
      			$table->string('panels_shipped')->nullable();
            $table->date('date_received')->nullable();
            $table->string('panels_received')->nullable();
      			$table->string('condition', 500)->nullable();
            $table->string('receiver', 100)->nullable();
            $table->integer('user_id')->unsigned();
            $table->foreign('round_id')->references('id')->on('rounds');
            $table->foreign('shipper_id')->references('id')->on('shippers');
            $table->foreign('facility_id')->references('id')->on('facilities');
            $table->foreign('user_id')->references('id')->on('users');
            $table->softDeletes();
      			$table->timestamps();
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
        //  Results Entry
        Schema::create('pt', function(Blueprint $table)
    		{
      			$table->increments('id')->unsigned();
      			$table->integer('enrolment_id')->unsigned();
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
            $table->foreign('enrolment_id')->references('id')->on('enrolments');
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
      Schema::dropIfExists('shipments');
      Schema::dropIfExists('shippers');
      Schema::dropIfExists('panels');
      Schema::dropIfExists('rounds');
      Schema::dropIfExists('materials');
      Schema::dropIfExists('programs');
    }
}