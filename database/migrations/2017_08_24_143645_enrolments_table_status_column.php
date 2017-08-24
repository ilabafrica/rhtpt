<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EnrolmentsTableStatusColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //add a status column in the enrollments table
          Schema::table('enrolments', function(Blueprint $table)
        {
            $table->tinyInteger('status')->default(0)->after('round_id');
        });  

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //  drop columns
        Schema::table('enrolments', function (Blueprint $table) {
            $table->dropColumn('status');

        });    
    }
}
