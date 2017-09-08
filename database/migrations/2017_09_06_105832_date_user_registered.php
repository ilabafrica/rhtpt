<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DateUserRegistered extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //  add a date_registered column in the users table to differentiate new/existing users per round
        Schema::table('users', function(Blueprint $table)
        {
            $table->date('date_registered')->nullable()->after('updated_at');
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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('date_registered');

        });    
    }
}
