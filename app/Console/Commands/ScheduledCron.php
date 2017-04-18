<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB;
use Hash;

use App\Dump;
use App\Round;
use App\User;
use App\Pt;
use App\Result;
use App\Facility;
use App\Program;
use App\Registration;
use App\Role;
use App\Nonperformance;
use App\Field;
use App\Option;

use Jenssegers\Date\Date as Carbon;

class ScheduledCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scheduled:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /**
        * 1. Get all results in the pt-dump and add them to pt table.
        *
        */
        //  Fetch all pending records
        $counter = Dump::count();
        if($counter > 0)
        {
            $dumps = Dump::all();
            $this->moveDump($dumps);
        }
        /**
        * 2. Get all unchecked results and execute the algorithm.
        *
        */
        $pts = Pt::where('panel_status', Pt::NOT_CHECKED)->get();
        if(is_array($pts))
        {
            $rsController = new ResultController;
            $rsController->runAlgorithm($pts);
        }
        $this->info('Scheduled:Cron Command Run successfully!');
    }
    /**
    * Function to move data from pt-dump to pt table
    *
    * @return \Illuminate\Http\Response
    */
    public function moveDump($dumps)
    {
        foreach($dumps as $dump)
        {        
            //  Check if new tester
            $userId = NULL;
            if(!empty($dump->Participant_Full_Names))
            {
                $user = new User;
                $user->name = $dump->Participant_Full_Names;
                $user->email = $dump->NT_Email;
                $user->password = Hash::make(User::DEFAULT_PASSWORD);
                $user->address = NULL;
                $user->gender = User::gender($dump->NTester_gender);
                $user->phone = "+254$dump->NTMobile_Number";
                $user->username = '';
                $user->save();
                //  Update username
                $user->username = $user->id;
                $user->uid = $user->id;
                $user->save();
                $userId = $user->id;
                //  Creare role-user
                $role = Role::idByName('Participant');
                $facility = Facility::idByCode($dump->MFL_Code);
                if($dump->NT_Program == "LVCT")
                    $program_id = Program::idByTitle("VCT");
                else
                    $program_id = Program::idByTitle($dump->NT_Program);
                DB::table('role_user')->insert(["user_id" => $userId, "role_id" => $role, "tier" => $facility, "program_id" => $program_id]);
                //  Reasons for non-performance to registration
                $reg = new Registration;
                $reg->user_id = $userId;
                $reg->uid = $dump->ID_No;
                if(!empty($dump->Transferred_County) || !empty($dump->Transferred_Facility))
                {
                    $reg->nonperformance_id = Nonperformance::idByTitle('Transferred');
                    $reg->comment = $dump->Transferred_Facility;
                }
                else if(!empty($dump->Tester_Off_Duty))
                    $reg->nonperformance_id = Nonperformance::idByTitle('Off Duty');
                else
                    $reg->nonperformance_id = Nonperformance::idByTitle('Other');
                $reg->save();
            }
            else
            {
                $userId = User::idByUID($dump->ID_No);
            }
            //  Save to pt table
            $pt = new Pt;
            $pt->round_id = Round::idByTitle($dump->Round);
            $pt->user_id = $userId;
            $pt->panel_status = Pt::NOT_CHECKED;
            $pt->comment = $dump->Comments;
            $pt->save();

            //  Save results - one by one for the whole form
            //  Date received
            $dateReceived = $this->saveToPt($pt->id, 'Date PT Panel Received', $dump->Panel_recv_Date);
            //  Date constituted
            $dateConstituted = $this->saveToPt($pt->id, 'Date PT Panel Constituted', $dump->Panel_Const_Date);
            //  Date tested
            $dateTested = $this->saveToPt($pt->id, 'Date PT Panel Tested', $dump->Panel_Tested_Date);
            //  Test 1 kit name
            $test1kitName = $this->saveToPt($pt->id, 'Test 1 Kit Name', $dump->Test1_Name);
            //  Test 1 kit lot no
            $test1kitLotNo = $this->saveToPt($pt->id, 'Test 1 Lot No.', $dump->Kit1_Lot_No);
            //  Test 1 kit expiry date
            $test1kitExpiryDate = $this->saveToPt($pt->id, 'Test 1 Expiry Date', $dump->Kit1_Exp_Date);
            //  Test 2 kit name
            $test2kitName = $this->saveToPt($pt->id, 'Test 2 Kit Name', $dump->Test2_Name);
            //  Test 2 kit lot no
            $test2kitLotNo = $this->saveToPt($pt->id, 'Test 2 Lot No.', $dump->Kit2_Lot_No);
            //  Test 2 kit expiry date
            $test2kitExpiryDate = $this->saveToPt($pt->id, 'Test 2 Expiry Date', $dump->Kit2_Exp_Date);
            //  Test 3 kit name
            $test3kitName = $this->saveToPt($pt->id, 'Test 3 Kit Name', $dump->Test3_Name);
            //  Test 3 kit lot no
            $test3kitLotNo = $this->saveToPt($pt->id, 'Test 3 Lot No.', $dump->Kit3_Lot_No);
            //  Test 3 kit expiry date
            $test3kitExpiryDate = $this->saveToPt($pt->id, 'Test 3 Expiry Date', $dump->Kit3_Exp_Date);
            //  Panel 1 test 1 results
            $ptPanel1Test1Result = $this->saveToPt($pt->id, 'PT Panel 1 Test 1 Results', $dump->PT1TEST1Results);
            //  Panel 1 test 2 results
            $ptPanel1Test2Result = $this->saveToPt($pt->id, 'PT Panel 1 Test 2 Results', $dump->PT1TEST2Results);
            //  Panel 1 test 3 results
            $ptPanel1Test3Result = $this->saveToPt($pt->id, 'PT Panel 1 Test 3 Results', $dump->PT1TEST3Results);
            //  Panel 1 final results
            $ptPanel1FinalResult = $this->saveToPt($pt->id, 'PT Panel 1 Final Results', $dump->PT1FinalResults);
            //  Panel 2 test 1 results
            $ptPanel2Test1Result = $this->saveToPt($pt->id, 'PT Panel 2 Test 1 Results', $dump->PT2TEST1Results);
            //  Panel 2 test 2 results
            $ptPanel2Test2Result = $this->saveToPt($pt->id, 'PT Panel 2 Test 2 Results', $dump->PT2TEST2Results);
            //  Panel 2 test 3 results
            $ptPanel2Test3Result = $this->saveToPt($pt->id, 'PT Panel 2 Test 3 Results', $dump->PT2TEST3Results);
            //  Panel 2 final results
            $ptPanel2FinalResult = $this->saveToPt($pt->id, 'PT Panel 2 Final Results', $dump->PT2FinalResults);
            //  Panel 3 test 1 results
            $ptPanel3Test1Result = $this->saveToPt($pt->id, 'PT Panel 3 Test 1 Results', $dump->PT3TEST1Results);
            //  Panel 3 test 2 results
            $ptPanel3Test2Result = $this->saveToPt($pt->id, 'PT Panel 3 Test 2 Results', $dump->PT3TEST2Results);
            //  Panel 3 test 3 results
            $ptPanel3Test3Result = $this->saveToPt($pt->id, 'PT Panel 3 Test 3 Results', $dump->PT3TEST3Results);
            //  Panel 3 final results
            $ptPanel3FinalResult = $this->saveToPt($pt->id, 'PT Panel 3 Final Results', $dump->PT3FinalResults);
            //  Panel 4 test 1 results
            $ptPanel4Test1Result = $this->saveToPt($pt->id, 'PT Panel 4 Test 1 Results', $dump->PT4TEST1Results);
            //  Panel 4 test 2 results
            $ptPanel4Test2Result = $this->saveToPt($pt->id, 'PT Panel 4 Test 2 Results', $dump->PT4TEST2Results);
            //  Panel 4 test 3 results
            $ptPanel4Test3Result = $this->saveToPt($pt->id, 'PT Panel 4 Test 3 Results', $dump->PT4TEST3Results);
            //  Panel 4 final results
            $ptPanel4FinalResult = $this->saveToPt($pt->id, 'PT Panel 4 Final Results', $dump->PT4FinalResults);
            //  Panel 5 test 1 results
            $ptPanel5Test1Result = $this->saveToPt($pt->id, 'PT Panel 5 Test 1 Results', $dump->PT5TEST1Results);
            //  Panel 5 test 2 results
            $ptPanel5Test2Result = $this->saveToPt($pt->id, 'PT Panel 5 Test 2 Results', $dump->PT5TEST2Results);
            //  Panel 5 test 3 results
            $ptPanel5Test3Result = $this->saveToPt($pt->id, 'PT Panel 5 Test 3 Results', $dump->PT5TEST3Results);
            //  Panel 5 final results
            $ptPanel5FinalResult = $this->saveToPt($pt->id, 'PT Panel 5 Final Results', $dump->PT5FinalResults);
            //  Panel 6 test 1 results
            $ptPanel6Test1Result = $this->saveToPt($pt->id, 'PT Panel 6 Test 1 Results', $dump->PT6TEST1Results);
            //  Panel 6 test 2 results
            $ptPanel6Test2Result = $this->saveToPt($pt->id, 'PT Panel 6 Test 2 Results', $dump->PT6TEST2Results);
            //  Panel 6 test 3 results
            $ptPanel6Test3Result = $this->saveToPt($pt->id, 'PT Panel 6 Test 3 Results', $dump->PT6TEST3Results);
            //  Panel 6 final results
            $ptPanel6FinalResult = $this->saveToPt($pt->id, 'PT Panel 6 Final Results', $dump->PT6FinalResults);
            //  Soft-delete the saved dump
            $dump->delete();
        }
    }
    /**
    * Function to save data to the pt table
    *
    * $field = name of field, $dmp = corresponding value in pt_dump
    */
    public function saveToPt($ptId, $field, $dmp = NULL, $comment = NULL)
    {
        if($field_id = Field::idByUID($field))
        {
            if($field_id == Field::idByUID("Test 1 Kit Name") && $dmp == "Determine")
            {
                $comment = $dmp;
                $dmp = "Other";
            }
            if (strpos($dmp, '/') !== false)
            {
                if(empty($this->strip($this->strip($dmp))))
                    $dmp = NULL;
                else{
                    $dmp = Carbon::createFromFormat('d/m/Y', $dmp)->toDateString();
                }
            }
            if(!empty($dmp))
            {
                $fld = Field::find($field_id);
                $result = new Result;
                $result->pt_id = $ptId;
                $result->field_id = $field_id;
                if (preg_match('/_/', $dmp))
                    $dmp = str_replace('_', ' ', $dmp);
                if($fld->tag == 5)
                    $result->response = Option::idByTitle($dmp);
                else
                    $result->response = $dmp;
                if(!empty($comment))
                    $result->comment = $comment;
                $result->save();
            }
        }
        else
        {
            print($field);
        }
        return response()->json('Saved.');
    }
    /**
     * Remove the specified begining of text to get Id alone.
     *
     * @param  int  $id
     * @return Response
     */
    public function strip($field)
    {
        if(($pos = strpos($field, '/')) !== FALSE)
            return substr($field, $pos+1);
    }
}
