<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFacilityIdToEnrolmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('enrolments', function(Blueprint $table)
        {
            $table->integer('facility_id')->unsigned()->default(0)->after('round_id');
        }); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('enrolments', function(Blueprint $table)
        {
            $table->dropColumn('facility_id');
        }); 
    }
}
