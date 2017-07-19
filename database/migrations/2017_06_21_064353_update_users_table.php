<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //  add verification columns
        Schema::table('users', function(Blueprint $table)
        {
            $table->string('sms_code')->nullable()->after('uid');
            $table->boolean('phone_verified')->default(false)->after('sms_code');
            $table->string('email_verification_code')->nullable()->after('phone_verified');
            $table->boolean('email_verified')->default(false)->after('email_verification_code');
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
            $table->dropColumn('email_verified');
            $table->dropColumn('email_verification_code');
            $table->dropColumn('phone_verified');
            $table->dropColumn('sms_code');
        });
    }
}
