<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrackingLogTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //  Details of distribution of shipments/consignments
        Schema::create('consignments', function(Blueprint $table)
        {
            $table->increments('id')->unsigned();
            $table->integer('shipment_id')->unsigned();
            $table->integer('facility_id')->unsigned();
            $table->string('tracker', 50)->nullable();
            $table->integer('total');
            $table->date('date_picked');
            $table->string('picked_by', 50);
            $table->string('contacts', 50);

            $table->foreign('shipment_id')->references('id')->on('shipments');
            $table->foreign('facility_id')->references('id')->on('facilities');

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
        Schema::dropIfExists('consignments');
    }
}
