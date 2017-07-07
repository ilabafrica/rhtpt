<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FacilityCatalogTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //  Counties table
        Schema::create('counties', function(Blueprint $table)
    		{
    			$table->increments('id')->unsigned();
    			$table->string('name');
          $table->softDeletes();
    			$table->timestamps();
    		});
        //	sub-counties
    		Schema::create('sub_counties', function(Blueprint $table)
    		{
    			$table->increments('id')->unsigned();
    			$table->string('name');
    			$table->integer('county_id')->unsigned();
          $table->foreign('county_id')->references('id')->on('counties');
          $table->softDeletes();
    			$table->timestamps();
    		});
        //  Facilities
        Schema::create('facilities', function(Blueprint $table)
    		{
    			$table->increments('id')->unsigned();
    			$table->string('code', 20)->nullable();
    			$table->string('name', 100);
          $table->string('registration_number', 25)->nullable();
    			$table->integer('sub_county_id')->unsigned();
    			$table->string('mailing_address', 50)->nullable();
    			$table->string('in_charge', 50)->nullable();
          $table->string('in_charge_phone', 50)->nullable();
          $table->string('in_charge_email', 50)->nullable();
    			$table->decimal('longitude', 5, 2)->nullable();
    			$table->decimal('latitude', 5, 2)->nullable();
          $table->foreign('sub_county_id')->references('id')->on('sub_counties');
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
        Schema::dropIfExists('facilities');
    		Schema::dropIfExists('sub_counties');
    		Schema::dropIfExists('counties');
    }
}
