<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePtTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      //  Marking of PT submissions
      Schema::table('pt', function ($table)
      {
        $table->tinyInteger('checks')->default(0)->after('panel_status');
        $table->tinyInteger('panel_result')->default(0)->after('checks');
        $table->tinyInteger('incorrect_results')->default(0)->after('panel_result');
        $table->tinyInteger('incomplete_kit_data')->default(0)->after('incorrect_results');
        $table->tinyInteger('dev_from_procedure')->default(0)->after('incomplete_kit_data');
        $table->tinyInteger('incomplete_other_information')->default(0)->after('dev_from_procedure');
        $table->tinyInteger('use_of_expired_kits')->default(0)->after('incomplete_other_information');
        $table->tinyInteger('invalid_results')->default(0)->after('use_of_expired_kits');
        $table->tinyInteger('wrong_algorithm')->default(0)->after('invalid_results');
        $table->tinyInteger('incomplete_results')->default(0)->after('wrong_algorithm');
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
        Schema::table('pt', function ($table)
        {
            $table->dropColumn(['checks', 'panel_result', 'incorrect_results', 'incomplete_kit_data', 'dev_from_procedure', 'incomplete_other_information', 'use_of_expired_kits', 'invalid_results', 'wrong_algorithm', 'incomplete_results']);
        });
    }
}
