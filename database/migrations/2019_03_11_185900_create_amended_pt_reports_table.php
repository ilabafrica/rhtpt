<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAmendedPTReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amended_pt', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('pt_id')->unsigned();
          $table->tinyInteger('status')->unsigned()->default(1);
          $table->tinyInteger('feedback')->unsigned()->default(0);
          $table->tinyInteger('incorrect_results')->unsigned()->default(0);
          $table->tinyInteger('incomplete_kit_data')->unsigned()->default(0);
          $table->tinyInteger('dev_from_procedure')->unsigned()->default(0);
          $table->tinyInteger('incomplete_other_information')->unsigned()->default(0);
          $table->tinyInteger('use_of_expired_kits')->unsigned()->default(0);
          $table->tinyInteger('invalid_results')->unsigned()->default(0);
          $table->tinyInteger('wrong_algorithm')->unsigned()->default(0);
          $table->tinyInteger('incomplete_results')->unsigned()->default(0);
          $table->string('reason_for_amendment');
          $table->integer('amended_by')->unsigned();
          $table->softDeletes();
          $table->timestamps();
          $table->foreign('pt_id')->references('id')->on('pt');
          $table->foreign('amended_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('amended_pt');
    }
}