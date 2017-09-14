<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ParticipantRejection extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //  status and reason for rejection to users table
        Schema::table('users', function(Blueprint $table)
        {
            $table->tinyInteger('status')->nullable()->after('date_registered');
            $table->string('reason', 25)->nullable()->after('status');
            $table->date('status_date')->nullable()->after('reason');
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
            $table->dropColumn('status');
            $table->dropColumn('reason');
            $table->dropColumn('status_date');
        });  
    }
}
