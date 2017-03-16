<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateEnrollments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //  Add round-id to refer to enrolments
        Schema::table('user_tiers', function ($table)
        {
            $table->integer('round_id')->default(1)->after('program_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //  Drop columns
        Schema::table('user_tiers', function ($table)
        {
            $table->dropColumn(['round_id']);
        });
    }
}
