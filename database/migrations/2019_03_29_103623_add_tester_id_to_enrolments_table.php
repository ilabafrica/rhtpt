<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTesterIdToEnrolmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('enrolments', function (Blueprint $table) {
            $table->integer('tester_id')->unsigned()->nullable()->after('facility_id')
                  ->comment('ID of the person that actually tested the panel');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('enrolments', function (Blueprint $table) {
            $table->dtopColumn('tester_id');
        });
    }
}
