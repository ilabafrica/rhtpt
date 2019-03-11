<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAmmendedPTReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
CREATE TABLE `ammended_pt` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pt_id` int(10) unsigned NOT NULL,
  `status` tinyint(3) NOT NULL DEFAULT 1,
  `feedback` tinyint(4) NOT NULL DEFAULT '0',
  `incorrect_results` tinyint(4) NOT NULL DEFAULT '0',
  `incomplete_kit_data` tinyint(4) NOT NULL DEFAULT '0',
  `dev_from_procedure` tinyint(4) NOT NULL DEFAULT '0',
  `incomplete_other_information` tinyint(4) NOT NULL DEFAULT '0',
  `use_of_expired_kits` tinyint(4) NOT NULL DEFAULT '0',
  `invalid_results` tinyint(4) NOT NULL DEFAULT '0',
  `wrong_algorithm` tinyint(4) NOT NULL DEFAULT '0',
  `incomplete_results` tinyint(4) NOT NULL DEFAULT '0',
  `reason_for_ammendment` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ammended_by` int(10) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pt_id_foreign` (`pt_id`),
  CONSTRAINT `pt_id_foreign` FOREIGN KEY (`pt_id`) REFERENCES `pt` (`id`)
);


        Schema::create('ammended_pt', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('pt_id')->unsigned();
          $table->tinyint('status')->unsigned()->default(1);
          $table->tinyint('feedback')->unsigned()->default(0);
          $table->tinyint('incorrect_results')->unsigned()->default(0);
          $table->tinyint('incomplete_kit_data')->unsigned()->default(0);
          $table->tinyint('dev_from_procedure')->unsigned()->default(0);
          $table->tinyint('incomplete_other_information')->unsigned()->default(0);
          $table->tinyint('use_of_expired_kits')->unsigned()->default(0);
          $table->tinyint('invalid_results')->unsigned()->default(0);
          $table->tinyint('wrong_algorithm')->unsigned()->default(0);
          $table->tinyint('incomplete_results')->unsigned()->default(0);
          $table->string('reason_for_ammendment');
          $table->integer('ammended_by')->unsigned();
          $table->softDeletes();
          $table->timestamps();
          $table->foreign('pt_id')->references('id')->on('pt');
          $table->foreign('ammended_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ammended_pt');
    }
}