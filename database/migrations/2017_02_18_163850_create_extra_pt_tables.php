<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExtraPtTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //  Teleform dump table
        Schema::create('pt_dump', function(Blueprint $table)
        {
            $table->increments('id')->unsigned();
            $table->integer('rid')->nullable();
            $table->integer('Round')->nullable();
            $table->string('Program_Name', 50)->nullable();
            $table->string('ID_No', 50)->nullable();
            $table->string('Tester_Name', 50)->nullable();
            $table->string('Designation', 50)->nullable();
            $table->string('Tester_gender', 50)->nullable();
            $table->string('Employ_No', 50)->nullable();
            $table->string('Mobile_Number', 50)->nullable();
            $table->string('Tester_email', 50)->nullable();
            $table->string('Facility_Name', 50)->nullable();
            $table->string('MFL_code', 50)->nullable();
            $table->string('SUBCOUNTY', 50)->nullable();
            $table->string('DNAME', 50)->nullable();
            $table->string('COUNTY', 50)->nullable();
            $table->string('PNAME', 50)->nullable();
            $table->string('Form_Number', 50)->nullable();
            $table->string('Panel_Tested_Date', 50)->nullable();
            $table->string('Panel_recv_Date', 50)->nullable();
            $table->string('Panel_Const_Date', 50)->nullable();
            $table->string('Test2_Name', 50)->nullable();
            $table->string('Test3_Name', 50)->nullable();
            $table->string('Test1_Name', 50)->nullable();
            $table->string('Kit1_Lot_No', 50)->nullable();
            $table->string('Kit2_Lot_No', 50)->nullable();
            $table->string('Kit3_Lot_No', 50)->nullable();
            $table->string('Kit1_Exp_Date', 50)->nullable();
            $table->string('Kit3_Exp_Date', 50)->nullable();
            $table->string('Kit2_Exp_Date', 50)->nullable();
            $table->string('PT1FinalResults', 50)->nullable();
            $table->string('PT1TEST1Results', 50)->nullable();
            $table->string('PT1TEST2Results', 50)->nullable();
            $table->string('PT1TEST3Results', 50)->nullable();
            $table->string('PT2FinalResults', 50)->nullable();
            $table->string('PT2TEST1Results', 50)->nullable();
            $table->string('PT2TEST2Results', 50)->nullable();
            $table->string('PT2TEST3Results', 50)->nullable();
            $table->string('PT3TEST1Results', 50)->nullable();
            $table->string('PT3TEST2Results', 50)->nullable();
            $table->string('PT3TEST3Results', 50)->nullable();
            $table->string('PT3FinalResults', 50)->nullable();
            $table->string('PT4FinalResults', 50)->nullable();
            $table->string('PT4TEST1Results', 50)->nullable();
            $table->string('PT4TEST2Results', 50)->nullable();
            $table->string('PT4TEST3Results', 50)->nullable();
            $table->string('PT5FinalResults', 50)->nullable();
            $table->string('PT5TEST1Results', 50)->nullable();
            $table->string('PT5TEST2Results', 50)->nullable();
            $table->string('PT5TEST3Results', 50)->nullable();
            $table->string('PT6FinalResults', 50)->nullable();
            $table->string('PT6TEST1Results', 50)->nullable();
            $table->string('PT6TEST3Results', 50)->nullable();
            $table->string('PT6TEST2Results', 50)->nullable();
            $table->string('Date_tester_sign', 50)->nullable();
            $table->string('Site_Incharge', 50)->nullable();
            $table->string('In_charge_other_designation', 50)->nullable();
            $table->string('Site_Incharge_designation', 50)->nullable();
            $table->string('Date_Site_Incharge_signs', 50)->nullable();
            $table->string('Incharge_Mobile_No', 50)->nullable();
            $table->string('Incharge_email', 50)->nullable();
            $table->string('BatchCust1', 50)->nullable();
            $table->string('BatchCust2', 50)->nullable();
            $table->string('BatchCust3', 50)->nullable();
            $table->string('BatchCust4', 50)->nullable();
            $table->string('BatchCust5', 50)->nullable();
            $table->string('BatchDir', 50)->nullable();
            $table->string('BatchNo', 50)->nullable();
            $table->string('BatchPgCnt', 50)->nullable();
            $table->string('BatchPgDta', 50)->nullable();
            $table->string('BatchPgNo', 50)->nullable();
            $table->string('BatchPgPos', 50)->nullable();
            $table->string('BatchRDate', 50)->nullable();
            $table->string('BatchScOpr', 50)->nullable();
            $table->string('BatchTrack', 50)->nullable();
            $table->string('CSID', 50)->nullable();
            $table->string('CSID2', 50)->nullable();
            $table->string('Form_Id', 50)->nullable();
            $table->string('Form_Notes', 50)->nullable();
            $table->string('Form_Pri', 50)->nullable();
            $table->string('FormIDMthd', 50)->nullable();
            $table->string('Image_Seq', 50)->nullable();
            $table->string('Orig_File', 50)->nullable();
            $table->string('OrigPgSeq', 50)->nullable();
            $table->string('Remote_User', 50)->nullable();
            $table->string('Remote_Bid', 50)->nullable();
            $table->string('Remote_Cmp', 50)->nullable();
            $table->string('Remote_Fax', 50)->nullable();
            $table->string('Remote_Phn', 50)->nullable();
            $table->string('Remote_Uid', 50)->nullable();
            $table->string('Route_To', 50)->nullable();
            $table->string('Suspense_File', 50)->nullable();
            $table->string('Time_Stamp', 50)->nullable();
            $table->string('Verify_Wks', 50)->nullable();
            $table->string('Participant_Refresher_Date', 50)->nullable();
            $table->string('Participant_Refresher_TR', 50)->nullable();
            $table->string('Participant_Trained', 50)->nullable();
            $table->string('Tester_Off_Duty', 50)->nullable();
            $table->string('Transferred_Facility', 50)->nullable();
            $table->string('Participant_Designation', 50)->nullable();
            $table->string('Participant_Full_Names', 50)->nullable();
            $table->string('NTester_gender', 50)->nullable();
            $table->string('NTMobile_Number', 50)->nullable();
            $table->string('Checks', 50)->nullable();
            $table->string('MyDate', 50)->nullable();
            $table->string('Panel_Result', 50)->nullable();
            $table->string('IncorrectResults', 50)->nullable();
            $table->string('IncompleteKitData', 50)->nullable();
            $table->string('DevFromProcedure', 50)->nullable();
            $table->string('IncompleteOtherInformation', 50)->nullable();
            $table->string('UseOfExpiredKits', 50)->nullable();
            $table->string('InvalidResults', 50)->nullable();
            $table->string('WrongAlgorithm', 50)->nullable();
            $table->string('Overall_Result', 50)->nullable();
            $table->string('IncompleteResults', 50)->nullable();
            $table->string('VerifiedBy', 50)->nullable();
            $table->string('VerifiedDate', 50)->nullable();
            $table->string('Status', 50)->nullable();
            $table->string('Comments', 50)->nullable();
            $table->string('NT_Program', 50)->nullable();
            $table->string('Transferred_County', 50)->nullable();
            $table->string('NT_Email', 50)->nullable();
            $table->string('NewSite_incharge', 50)->nullable();
            $table->string('New_incharge_mobile_No', 50)->nullable();
            $table->string('New_Incharge_email', 50)->nullable();
            $table->string('lot', 50)->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //  Reverse migration
        Schema::dropIfExists('pt_dump');
    }
}
