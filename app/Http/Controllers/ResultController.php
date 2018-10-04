<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Pt;
use App\Result;
use App\Field;
use App\Option;
use App\User;
use App\Notification;
use App\Enrol;
use App\Round;
use App\County;
use App\SubCounty;
use App\Facility;
use App\Program;
use App\Panel;
use App\Material;
use App\EvaluatedResult;
use App\ImplementingPartner;
use App\SmsHandler;
use App\Libraries\AfricasTalkingGateway as Bulk;

use Auth;
use Jenssegers\Date\Date as Carbon;
use DB;
use PDF;

class ResultController extends Controller
{

    public function manageResult()
    {
        return view('result.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $error = ['error' => 'No results found, please try with different keywords.'];
        $items_per_page = 100;

        if(Auth::user()->isCountyCoordinator())
        {
            $results = County::find(Auth::user()->ru()->tier)->results();
        }
        else if(Auth::user()->isSubCountyCoordinator())
        {
           $results = SubCounty::find(Auth::user()->ru()->tier)->results();
        }
        else if(Auth::user()->isFacilityInCharge())
        {
           $results = Facility::find(Auth::user()->ru()->tier)->results();
        }
        else if(Auth::user()->isParticipant())
        {
            $results = Auth::user()->results();
        }
        else if(Auth::user()->isPartner())
        {
           $results = ImplementingPartner::find(Auth::user()->ru()->tier)->results();
        }else if(Auth::user()->isSuperAdministrator())
        {
            $results = Pt::withTrashed();
        }

        //search results by user details
        if ($request->has('q')||$request->has('county')||$request->has('sub_county')||$request->has('facility')||$request->has('feedback_status')||$request->has('result_status')) {        
            if($request->has('q')) 
            {
                $search = $request->get('q');
                $search_ar = ['search'=>$search];
                
                if(Auth::user()->isCountyCoordinator())
                {
                    $results = County::find(Auth::user()->ru()->tier)->results($search_ar);
                }
                else if(Auth::user()->isSubCountyCoordinator())
                {
                    $results = SubCounty::find(Auth::user()->ru()->tier)->results($search_ar);
                }
                else if(Auth::user()->isFacilityInCharge())
                {
                   $results = Facility::find(Auth::user()->ru()->tier)->results($search_ar);
                }
                else if(Auth::user()->isPartner())
                {
                   $results = ImplementingPartner::find(Auth::user()->ru()->tier)->results($search_ar);
                }
                else{
                    $users = User::where('name', 'LIKE', "%{$search}%")
                        ->orWhere('first_name', 'LIKE', "%{$search}%")
                        ->orWhere('middle_name', 'LIKE', "%{$search}%")
                        ->orWhere('last_name', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%")
                        ->orWhere('phone', 'LIKE', "%{$search}%")
                        ->orWhere('uid', 'LIKE', "%{$search}%")->pluck('id');

                    $enrolments = Enrol::whereIn('user_id', $users)->pluck('id');
                    $results = Pt::whereIn('enrolment_id', $enrolments);
                }
            }
            //search results by filters
            if($request->has('county')) 
            {            
                    $results = County::find($request->get('county'))->results();
            }
            if($request->has('sub_county')) 
            {
                $results = SubCounty::find($request->get('sub_county'))->results();
            }
            if($request->has('facility')) 
            {
               $results = Facility::find($request->get('facility'))->results();
            }
            if($request->has('result_status')) 
            {
                $results = $results->where('panel_status', $request->get('result_status'));
            }
            if($request->has('feedback_status')) 
            {     
                $results = $results->where('feedback', $request->get('feedback_status'));
            }
        }

        $results = $results->withTrashed()->paginate($items_per_page);

        foreach($results as $result)
        {
            $result->rnd = $result->enrolment->round->name;
            $result->tester = $result->enrolment->user->first_name . " " . $result->enrolment->user->middle_name . " " . $result->enrolment->user->last_name;
            $result->uid = $result->enrolment->user->uid;

            //particpants should not see the result feedback until it has been verified by the admin             
            if(Auth::user()->isParticipant() ||Auth::user()->isSubCountyCoordinator()||Auth::user()->isCountyCoordinator()||Auth::user()->isPartner()){
                if ($result->panel_status != 3) {
                    $result->feedback = 2;
                }                
            }

	        $result->user_role = Auth::user()->ru()->role_id;
        }

        $response = [
            'pagination' => [
                'total' => $results->total(),
                'per_page' => $results->perPage(),
                'current_page' => $results->currentPage(),
                'last_page' => $results->lastPage(),
                'from' => $results->firstItem(),
                'to' => $results->lastItem()
            ],
            'user_role' => Auth::user()->ru()->role_id,
            'data' => $results
        ]; 

        return $results->count() > 0 ? response()->json($response) : $error;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {        
        //Check if round has been         
        if ($request->get('round_id') =="") {
            return response()->json(['1']);            
        } else
        {   
            //	Save pt first then proceed to save form fields
            $round_id = $request->get('round_id');
            $enrolment = Enrol::where('user_id', Auth::user()->id)->where('round_id', $round_id)->first();
            
            //Validation: Check if the enrolment results have been submitted
            if ($enrolment->status ==1) {
                return response()->json(['2']);            
            }else
            {   
                $pt = new Pt;
                $pt->enrolment_id = $enrolment->id;
                $pt->panel_status = Pt::NOT_CHECKED;
                $pt->save();

                //update enrollment status to 1
                $enrolment->status = Enrol::DONE;        
                $enrolment->save();     
               
                //	Proceed to form-fields
                // get all fields and insert into results
                $fields = Field::all();
                $response = '';

                foreach ($fields as $field) {
                    $result = new Result;
                    $result->pt_id = $pt->id;
                    $result->field_id = $field->id;
                   
                   //loop through the results entered and get the response for each field
                    foreach ($request->all() as $key => $value)
                    {
                        if((stripos($key, 'token') !==FALSE) || (stripos($key, 'method') !==FALSE))
                            continue;
                        else if(stripos($key, 'field') !==FALSE)
                        {
                            $fieldId = (int)$this->strip($key);
                            if(is_array($value))
                              $value = implode(', ', $value);
                            

                            if ($field->id == $fieldId) {
                                $response = $value;
                                break;
                            }else if ($field->id != $fieldId) {
                                $response = '';

                            }                      
                        }                        
                    } 
                    // save the response for respective field
                    $result->response = $response;
                    $result->save();   
                }

                //  Send SMS
                $round = Round::find($pt->enrolment->round->id)->description;
                $message = Notification::where('template', Notification::RESULTS_RECEIVED)->first()->message;
                $message = $this->replace_between($message, '[', ']', $round);
                $message = str_replace(' [', ' ', $message);
                $message = str_replace(']', ' ', $message);

                $created = Carbon::today()->toDateTimeString();
                $updated = Carbon::today()->toDateTimeString();
                //  Time
                $now = Carbon::now('Africa/Nairobi');
                $bulk = DB::table('bulk')->insert(['notification_id' => Notification::RESULTS_RECEIVED, 'round_id' => $pt->enrolment->round->id, 'text' => $message, 'user_id' => $pt->enrolment->user->id, 'date_sent' => $now, 'created_at' => $created, 'updated_at' => $updated]);

                return response()->json('Saved.');

             }
         }
    }

    /**
     * Fetch pt with related components for editing
     *
     * @param ID of the selected pt -  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pt = Pt::find($id);
        $round = $pt->enrolment->round;
        $results = $pt->results;
        $response = [
            'pt' => $pt,
            'round' => $round,
            'results' => $results,
            'pt_id'=>$pt->id
        ];
        // dd($response);
        return response()->json($response);
    }
    /*
    *   verify the result after reviewing
    */
    public function verify(Request $request)
    {
        $id = $request->pt_id;
        $user_id = Auth::user()->id;

        $result = Pt::find($id);
        $result->verified_by = $user_id;
        $result->panel_status = Pt::CHECKED;
        if($request->comment)
            $result->comment = $request->comment;
        $result->save();
        // Send SMS
        $round = Round::find($result->enrolment->round->id)->description;
        $message = Notification::where('template', Notification::RESULTS_RECEIVED)->first()->message;
        $message = $this->replace_between($message, '[', ']', $round);
        $message = str_replace(' [', ' ', $message);
        $message = str_replace(']', ' ', $message);
        
        $created = Carbon::today()->toDateTimeString();
        $updated = Carbon::today()->toDateTimeString();
        //  Time
        $now = Carbon::now('Africa/Nairobi');
        $bulk = DB::table('bulk')->insert(['notification_id' => Notification::RESULTS_RECEIVED, 'round_id' => $result->enrolment->round->id, 'text' => $message, 'user_id' => $result->enrolment->user->id, 'date_sent' => $now, 'created_at' => $created, 'updated_at' => $updated]);
        
        //get the last id inserted and use it in the broadcast table
        $bulk_id = DB::getPdo()->lastInsertId(); 

        $recipients = NULL;
        $recipients = User::find($result->enrolment->user->id)->phone;
        //  Bulk-sms settings
        $api = DB::table('bulk_sms_settings')->first();
        $username   = $api->username;
        $apikey     = $api->api_key;
        if($recipients)
        {
            // Specified sender-id
            $from = $api->code; //Mapesa: this still picks the wrong code. Nat-HivPT instead of NPHL
            $from = "NPHL";
            // Create a new instance of Bulk SMS gateway.
            $sms    = new Bulk($username, $apikey);

            // use try-catch to filter any errors.
            try
            {
                // Send messages
                $send_messages = $sms->sendMessage($recipients, $message, $from);
                foreach($send_messages as $send_message)
                {
                    // status is either "Success" or "error message" and save.
                    $number = $send_message->number;
                    //  Save the results
                    DB::table('broadcast')->insert(['number' => $number, 'bulk_id' => $bulk_id]);
                }
            }
            catch ( AfricasTalkingGatewayException $e )
            {
                echo "Encountered an error while sending: ".$e->getMessage();
            }
 
       }
        return response()->json($result);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {       
        $pt = Pt::find($id);
        
        //  Proceed to form-fields
        foreach ($request->all() as $key => $value)
        {
            if((stripos($key, 'token') !==FALSE) || (stripos($key, 'method') !==FALSE))
                continue;
            else if(stripos($key, 'field') !==FALSE)
            {
                $fieldId = $this->strip($key);
                if(is_array($value))
                  $value = implode(', ', $value);

                $results = Result::where('pt_id', $pt->id)->get();

                foreach ($results as $result_key => $result) {

                   if ($result->field_id ==$fieldId) {
                        $result->response = $value;
                        $result->save();
                   }
                }
            }
            else if(stripos($key, 'comment') !==FALSE)
            {
                if($value)
                {
                    $result = Result::where('field_id', $key)->first();
                    $result->comment = $value;
                    $result->save();
                }
            }
        }    

        return response()->json(["Done"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Shipment::find($id)->delete();
        return response()->json(['done']);
    }

    /**
     * enable soft deleted record.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id) 
    {
        $shipment = Shipment::withTrashed()->find($id)->restore();
        return response()->json(['done']);
    }

    /**
     * Receive a shipment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function receive(Request $request)
    {
        $this->validate($request, [
            'date_received' => 'required',
            'panels_received' => 'required',
            'condition' => 'required',
            'receiver' => 'required'
        ]);

        $create = Receipt::create($request->all());

        return response()->json($create);
    }
    /**
  	 * Remove the specified begining of text to get Id alone.
  	 *
  	 * @param  int  $id
  	 * @return Response
  	 */
  	public function strip($field)
  	{
    		if(($pos = strpos($field, '_')) !== FALSE)
    		return substr($field, $pos+1);
  	}
    /**
     * Replace strings between two characters.
     *
     */
    function replace_between($str, $needle_start, $needle_end, $replacement) 
    {
        $pos = strpos($str, $needle_start);
        $start = $pos === false ? 0 : $pos + strlen($needle_start);

        $pos = strpos($str, $needle_end, $start);
        $end = $pos === false ? strlen($str) : $pos;

        return substr_replace($str, $replacement, $start, $end - $start);
    }
     /**
     * Verify results evaluted by a NON-Participant
     *
     * @param ID of the selected pt -  $id
     */
    public function evaluated_results($id)
    {
        //get actual results
        $pt = Pt::find($id);
        $round_id = $pt->enrolment->round->id;
        $pt_results = $pt->results;
        $option = new Option;

        $pt_panel_1_kit1_results = '';
        $pt_panel_2_kit1_results = '';
        $pt_panel_3_kit1_results = '';;
        $pt_panel_4_kit1_results = '';
        $pt_panel_5_kit1_results = '';
        $pt_panel_6_kit1_results = '';

        $pt_panel_1_kit2_results = '';
        $pt_panel_2_kit2_results = '';
        $pt_panel_3_kit2_results = '';;
        $pt_panel_4_kit2_results = '';
        $pt_panel_5_kit2_results = '';
        $pt_panel_6_kit2_results = '';

        $determine = '';
        $determine_lot_no = '';
        $determine_expiry_date = '';

        $firstresponse = '';
        $firstresponse_lot_no = '';
        $firstresponse_expiry_date = '';

        $date_received = '';
        $date_constituted = '';
        $date_tested = '';

        $expected_result_1 = '';
        $expected_result_2 = '';
        $expected_result_3 = '';
        $expected_result_4 = '';
        $expected_result_5 = '';
        $expected_result_6 = '';



        foreach ($pt_results as $rss) {
            //test kit 1 results
            if($rss->field_id == Field::idByUID('PT Panel 1 Test 1 Results')){
                $pt_panel_1_kit1_results = $option::nameByID($rss->response);
                $pt_panel_1_kit1_results_value = $rss->response;
            }
            if($rss->field_id == Field::idByUID('PT Panel 2 Test 1 Results')){
                $pt_panel_2_kit1_results = $option::nameByID($rss->response);
                $pt_panel_2_kit1_results_value = $rss->response;
            }
            if($rss->field_id == Field::idByUID('PT Panel 3 Test 1 Results')){
                $pt_panel_3_kit1_results = $option::nameByID($rss->response);
                $pt_panel_3_kit1_results_value = $rss->response;
            }
            if($rss->field_id == Field::idByUID('PT Panel 4 Test 1 Results')){
                $pt_panel_4_kit1_results = $option::nameByID($rss->response);
                $pt_panel_4_kit1_results_value = $rss->response;
            }
            if($rss->field_id == Field::idByUID('PT Panel 5 Test 1 Results')){
                $pt_panel_5_kit1_results = $option::nameByID($rss->response);
                $pt_panel_5_kit1_results_value = $rss->response;
            }
            if($rss->field_id == Field::idByUID('PT Panel 6 Test 1 Results')){
                $pt_panel_6_kit1_results = $option::nameByID($rss->response);
                $pt_panel_6_kit1_results_value = $rss->response;
            }

            //test kit 2 results
            if($rss->field_id == Field::idByUID('PT Panel 1 Test 2 Results')){
                $pt_panel_1_kit2_results = $option::nameByID($rss->response);
                $pt_panel_1_kit2_results_value = $rss->response;
            }
            if($rss->field_id == Field::idByUID('PT Panel 2 Test 2 Results')){
                $pt_panel_2_kit2_results = $option::nameByID($rss->response);
                $pt_panel_2_kit2_results_value = $rss->response;
            }
            if($rss->field_id == Field::idByUID('PT Panel 3 Test 2 Results')){
                $pt_panel_3_kit2_results = $option::nameByID($rss->response);
                $pt_panel_3_kit2_results_value = $rss->response;
            }
            if($rss->field_id == Field::idByUID('PT Panel 4 Test 2 Results')){
                $pt_panel_4_kit2_results = $option::nameByID($rss->response);
                $pt_panel_4_kit2_results_value = $rss->response;
            }
            if($rss->field_id == Field::idByUID('PT Panel 5 Test 2 Results')){
                $pt_panel_5_kit2_results = $option::nameByID($rss->response);
                $pt_panel_5_kit2_results_value = $rss->response;
            }
            if($rss->field_id == Field::idByUID('PT Panel 6 Test 2 Results')){
                $pt_panel_6_kit2_results = $option::nameByID($rss->response);
                $pt_panel_6_kit2_results_value = $rss->response;
            }
           
            //final results
            if($rss->field_id == Field::idByUID('PT Panel 1 Final Results')){
                $pt_panel_1_final_results = $option::nameByID($rss->response);
                $pt_panel_1_final_results_value = $rss->response;
            }
            if($rss->field_id == Field::idByUID('PT Panel 2 Final Results')){
                $pt_panel_2_final_results = $option::nameByID($rss->response);
                $pt_panel_2_final_results_value = $rss->response;
            }
            if($rss->field_id == Field::idByUID('PT Panel 3 Final Results')){
                $pt_panel_3_final_results = $option::nameByID($rss->response);
                $pt_panel_3_final_results_value = $rss->response;
            }
            if($rss->field_id == Field::idByUID('PT Panel 4 Final Results')){
                $pt_panel_4_final_results = $option::nameByID($rss->response);
                $pt_panel_4_final_results_value = $rss->response;
            }
            if($rss->field_id == Field::idByUID('PT Panel 5 Final Results')){
                $pt_panel_5_final_results = $option::nameByID($rss->response);
                $pt_panel_5_final_results_value = $rss->response;
            }
            if($rss->field_id == Field::idByUID('PT Panel 6 Final Results')){
                $pt_panel_6_final_results = $option::nameByID($rss->response);
                $pt_panel_6_final_results_value = $rss->response;
            }
           
            // //test kit 1 results
            if($rss->field_id == Field::idByUID('Test 1 Kit Name')){
                $determine = $option::nameByID($rss->response);
                $determine_value = $rss->response;
            }
            if($rss->field_id == Field::idByUID('Test 1 Lot No.'))
                $determine_lot_no = $rss->response;
            if($rss->field_id == Field::idByUID('Test 1 Expiry Date'))
                $determine_expiry_date = $rss->response;

            // //test kit 2 results
            if($rss->field_id == Field::idByUID('Test 2 Kit Name')){
                $firstresponse = $option::nameByID($rss->response);
                $firstresponse_value = $rss->response;
            }
            if($rss->field_id == Field::idByUID('Test 2 Lot No.'))
                $firstresponse_lot_no = $rss->response;
            if($rss->field_id == Field::idByUID('Test 2 Expiry Date'))
                $firstresponse_expiry_date = $rss->response;  

            //dates
            if($rss->field_id == Field::idByUID('Date PT Panel Received'))
                $date_received = $rss->response;
            if($rss->field_id == Field::idByUID('Date PT Panel Constituted'))
                $date_constituted = $rss->response;
            if($rss->field_id == Field::idByUID('Date PT Panel Tested'))
                $date_tested = $rss->response;

            //comment
             if($rss->field_id == Field::idByUID('Comments'))
                $tester_comments = $rss->response;
        }
        $actual_results = array();

        //get expected results
        $round = Round::find($round_id);
        $user = $pt->enrolment->user;
        $lot = $user->lot($round_id);
        $expected_results = $lot->panels()->get();
        // $material_id = $expected_results->first()->material_id;
        // $material = Material::find($material_id); 

        foreach ($expected_results as $ex_rslts) {

            if($ex_rslts->panel == 1)
                $expected_result_1 = $ex_rslts->result($ex_rslts->result);
                $sample_1 = "PT-".$round->name."-S1";
            if($ex_rslts->panel == 2)
                $expected_result_2 = $ex_rslts->result($ex_rslts->result);
                $sample_2 = "PT-".$round->name."-S2";
            if($ex_rslts->panel == 3)
                $expected_result_3 = $ex_rslts->result($ex_rslts->result);
                $sample_3 = "PT-".$round->name."-S3";
            if($ex_rslts->panel == 4)
                $expected_result_4 = $ex_rslts->result($ex_rslts->result);
                $sample_4 = "PT-".$round->name."-S4";
            if($ex_rslts->panel == 5)
                $expected_result_5 = $ex_rslts->result($ex_rslts->result);
                $sample_5 = "PT-".$round->name."-S5";
            if($ex_rslts->panel == 6)
                $expected_result_6 = $ex_rslts->result($ex_rslts->result);
                $sample_6 = "PT-".$round->name."-S6";
        }

        //get participant details
        $participant_id = $user->id;
        $user_name = $user->name;
        $first_name = $user->first_name;
        $middle_name = $user->middle_name;
        $last_name = $user->last_name;
        $phone_no = $user->phone;
        $tester_id = $user->username;
        $roleUser = $user->ru();
        $facility = Facility::find($roleUser->tier);
        try{$designation = $user->designation($roleUser->designation);}catch(\Exception $ex){$designation = "";}
        try{$program = Program::find($roleUser->program_id);}catch(\Exception $ex){$program = ['name' => ""];}

        $county = strtoupper($facility->subCounty->county->name);
        $sub_county = $facility->subCounty->name;
        $mfl = $facility->code;
        $facility_name = $facility->name;
        $facility_id = $facility->id;
        
        //combine expected and actual result into one array
        $all_results = array();
        $round_name = $round->name;
        $round_status = $round->status;
        $feedback = $pt->outcome($pt->feedback);
        $panel_status = $pt->panel_status;

        //get reasons for unsatisfactory
        $incorrect_results = $pt->incorrect_results;
        $incomplete_kit_data = $pt->incomplete_kit_data;
        $dev_from_procedure = $pt->dev_from_procedure;
        $incomplete_other_information = $pt->incomplete_other_information;
        $use_of_expired_kits = $pt->use_of_expired_kits;
        $invalid_results = $pt->invalid_results;
        $wrong_algorithm = $pt->wrong_algorithm;
        $incomplete_results = $pt->incomplete_results;

        if($pt->feedback == Pt::UNSATISFACTORY)
            $remark = $pt->unsatisfactory(); 
        else
            $remark = 'None';
        
        // dd($remark);
         $all_results = array( 
                    //user details
                    'round_name'=> $round_name,
                    'round_status'=>$round_status, 
                    'feedback' => $feedback, 
                    'remark' => $remark, 
                    'panel_status' => $panel_status, 
                    'pt_id' => $pt->id,
                    'pt_approved_comment' => $pt->approved_comment,
                    'date_approved' => $pt->date_approved,
                    'participant_id' => $participant_id,
                    'user_name' => $user_name,
                    'first_name' => $first_name,
                    'middle_name' => $middle_name,
                    'last_name' => $last_name,
                    'phone_no' => $phone_no,
                    'tester_id' => $tester_id,
                    'designation' => $designation,
                    'program' => $program,
                    'program_name' => isset($program->name)?$program->name:"",
                    'county' => $county,
                    'sub_county' => $sub_county,
                    'facility' => $facility_name,
                    'facility_id' => $facility_id,
                    'mfl' => $mfl,

                    //material details
                    'date_received' =>$date_received,
                    'date_constituted' =>$date_constituted,
                    'date_tested' =>$date_tested,

                    //panel info
                    'determine' =>$determine,
                    'determine_value' =>$determine_value,
                    'determine_lot_no' =>$determine_lot_no,
                    'determine_expiry_date' =>$determine_expiry_date,
                    'firstresponse' =>$firstresponse,
                    'firstresponse_value' =>$firstresponse_value,
                    'firstresponse_lot_no' =>$firstresponse_lot_no,
                    'firstresponse_expiry_date' =>$firstresponse_expiry_date,

                    //results
                    //test 1 results
                    //name
                    "pt_panel_1_kit1_results"=>$pt_panel_1_kit1_results, 
                    "pt_panel_2_kit1_results"=>$pt_panel_2_kit1_results,
                    "pt_panel_3_kit1_results"=>$pt_panel_3_kit1_results,
                    "pt_panel_4_kit1_results"=>$pt_panel_4_kit1_results,
                    "pt_panel_5_kit1_results"=>$pt_panel_5_kit1_results,
                    "pt_panel_6_kit1_results"=>$pt_panel_6_kit1_results,

                    //value 
                    "pt_panel_1_kit1_results_value"=>$pt_panel_1_kit1_results_value, 
                    "pt_panel_2_kit1_results_value"=>$pt_panel_2_kit1_results_value,
                    "pt_panel_3_kit1_results_value"=>$pt_panel_3_kit1_results_value,
                    "pt_panel_4_kit1_results_value"=>$pt_panel_4_kit1_results_value,
                    "pt_panel_5_kit1_results_value"=>$pt_panel_5_kit1_results_value,
                    "pt_panel_6_kit1_results_value"=>$pt_panel_6_kit1_results_value,

                    //test 2 results
                    //name
                    "pt_panel_1_kit2_results"=>$pt_panel_1_kit2_results, 
                    "pt_panel_2_kit2_results"=>$pt_panel_2_kit2_results,
                    "pt_panel_3_kit2_results"=>$pt_panel_3_kit2_results,
                    "pt_panel_4_kit2_results"=>$pt_panel_4_kit2_results,
                    "pt_panel_5_kit2_results"=>$pt_panel_5_kit2_results,
                    "pt_panel_6_kit2_results"=>$pt_panel_6_kit2_results,

                    //value
                    "pt_panel_1_kit2_results_value"=>$pt_panel_1_kit2_results_value, 
                    "pt_panel_2_kit2_results_value"=>$pt_panel_2_kit2_results_value,
                    "pt_panel_3_kit2_results_value"=>$pt_panel_3_kit2_results_value,
                    "pt_panel_4_kit2_results_value"=>$pt_panel_4_kit2_results_value,
                    "pt_panel_5_kit2_results_value"=>$pt_panel_5_kit2_results_value,
                    "pt_panel_6_kit2_results_value"=>$pt_panel_6_kit2_results_value,

                    //final tested results
                    //name
                    "pt_panel_1_final_results"=>$pt_panel_1_final_results, 
                    "pt_panel_2_final_results"=>$pt_panel_2_final_results,
                    "pt_panel_3_final_results"=>$pt_panel_3_final_results,
                    "pt_panel_4_final_results"=>$pt_panel_4_final_results,
                    "pt_panel_5_final_results"=>$pt_panel_5_final_results,
                    "pt_panel_6_final_results"=>$pt_panel_6_final_results,

                    //value
                    "pt_panel_1_final_results_value"=>$pt_panel_1_final_results_value, 
                    "pt_panel_2_final_results_value"=>$pt_panel_2_final_results_value,
                    "pt_panel_3_final_results_value"=>$pt_panel_3_final_results_value,
                    "pt_panel_4_final_results_value"=>$pt_panel_4_final_results_value,
                    "pt_panel_5_final_results_value"=>$pt_panel_5_final_results_value,
                    "pt_panel_6_final_results_value"=>$pt_panel_6_final_results_value,

                    //expected results
                    "expected_result_1"=>$expected_result_1, 
                    "expected_result_2"=>$expected_result_2,
                    "expected_result_3"=>$expected_result_3,
                    "expected_result_4"=>$expected_result_4,
                    "expected_result_5"=>$expected_result_5,
                    "expected_result_6"=>$expected_result_6,
                    
                    //sample name
                    "sample_1"=>$sample_1, 
                    "sample_2"=>$sample_2,
                    "sample_3"=>$sample_3,
                    "sample_4"=>$sample_4,
                    "sample_5"=>$sample_5,
                    "sample_6"=>$sample_6,

                    'incorrect_results' => $incorrect_results,
                    'incomplete_kit_data' => $incomplete_kit_data,
                    'dev_from_procedure' => $dev_from_procedure,
                    'incomplete_other_information' => $incomplete_other_information,
                    'use_of_expired_kits' => $use_of_expired_kits,
                    'invalid_results' => $invalid_results,
                    'wrong_algorithm' => $wrong_algorithm,
                    'incomplete_results' => $incomplete_results
                );

        // return response()->json($all_results); 
        return $all_results;       
    }

    /**
     * Verify results evaluted by a NON-Participant
     *
     * @param ID of the selected pt -  $id
     */
    public function show_evaluated_results($id)
    {
        $data = $this->evaluated_results($id);
        return response()->json($data);

    }    

     /**
     * Save the verification of evaluated results
     *
     * @param ID of the selected pt -  $id
     */
    public function verify_evaluated_results(Request $request, $id )
    {
        $pt_id = $request->pt_id;
        $user_id = Auth::user()->id; 

        $result = Pt::find($id);
        $result->panel_status = Pt::VERIFIED;
        $result->date_approved = Carbon::today()->toDateTimeString();
        if ($request) {
            $result->approved_comment = $request->comment;            
     
            if($request->comment)
                $result->approved_by = $user_id;
        }
        $result->save();

         //  Send SMS
        $round = Round::find($result->enrolment->round->id)->description;
        $message = Notification::where('template', Notification::FEEDBACK_RELEASE)->first()->message;
        $message = $this->replace_between($message, '[', ']', $round);
        $message = str_replace(' [', ' ', $message);
        $message = str_replace(']', ' ', $message);

        $created = Carbon::today()->toDateTimeString();
        $updated = Carbon::today()->toDateTimeString();
        //  Time
        $now = Carbon::now('Africa/Nairobi');
        $bulk = DB::table('bulk')->insert(['notification_id' => Notification::FEEDBACK_RELEASE, 'round_id' => $result->enrolment->round->id, 'text' => $message, 'user_id' => $result->enrolment->user->id, 'date_sent' => $now, 'created_at' => $created, 'updated_at' => $updated]);
        $recipients = NULL;
        $recipients = User::find($result->enrolment->user->id)->value('phone');
        $ptUser = User::find($result->enrolment->user->id);
        $ptUserName = $ptUser->first_name . " " . $ptUser->last_name;
        $message = str_replace("PT Participant", $ptUserName, $message);

        try
        {
            $smsHandler = new SmsHandler();
            $smsHandler->sendMessage($ptUser->phone, $message);
            \Log::info("Sent feedback report sms to $ptUserName ".$ptUser->phone." -- Performed by $user_id");
        }
        catch ( AfricasTalkingGatewayException $e )
        {
            echo "Encountered an error while sending: ".$e->getMessage();
        }

        return response()->json($result);
    }
     /**
     * Update the evaluated results
     *
     * @param ID of the selected pt -  $id
     */
    public function update_evaluated_results(Request $request, $id )
    {
        //Get participant

        $participant = User::find($request->participant_id);

        DB::table('role_user')->where('user_id', $request->participant_id)->update(['tier' => $request->facility_id, 'program_id' => $request->program_id]);
        
        //save pt details

        $pt = Pt::find($id);
        if ($request->incorrect_results) {
            $pt->incorrect_results = $request->incorrect_results;
        }else{
            $pt->incorrect_results = 0;
        }

        if ($request->incomplete_kit_data) {
            $pt->incomplete_kit_data = $request->incomplete_kit_data;
        }else{
            $pt->incomplete_kit_data = 0;
        }

        if ($request->dev_from_procedure) {
            $pt->dev_from_procedure = $request->dev_from_procedure;
        }else{
            $pt->dev_from_procedure = 0;
        }

        if ($request->incomplete_other_information) {
            $pt->incomplete_other_information = $request->incomplete_other_information;
        }else{
            $pt->incomplete_other_information = 0;
        }

        if ($request->use_of_expired_kits) {
            $pt->use_of_expired_kits = $request->use_of_expired_kits;
        }else{

            $pt->use_of_expired_kits = 0;
        }
        if ($request->invalid_results) {
            $pt->invalid_results = $request->invalid_results;
        }else{
            $pt->invalid_results = 0;
        }

        if ($request->wrong_algorithm) {
            $pt->wrong_algorithm = $request->wrong_algorithm;
        }else{
            $pt->wrong_algorithm = 0;
        }

        if ($request->incomplete_results) {
            $pt->incomplete_results = $request->incomplete_results;
        }else{
            $pt->incomplete_results =0;
        }

        if ($request->feedback==1) {        //cannot check if value is 0.    
            $pt->feedback = $request->feedback;
        }else{
            $pt->feedback = 0; 
        }
        
        $pt->save();

        //save result details

        foreach ($request->all() as $key => $value)
        {
            if((stripos($key, 'token') !==FALSE) || (stripos($key, 'method') !==FALSE))
                continue;
            else if(stripos($key, 'field') !==FALSE)
            { 
                $fieldId = $this->strip($key);
                if(is_array($value))
                  $value = implode(', ', $value);

                $results = Result::where('pt_id', $pt->id)->get();

                foreach ($results as $result_key => $result) {

                   if ($result->field_id ==$fieldId) {
                        $result->response = $value;
                        $result->save();
                   }
                }
            }            
        }

        //save previous data

        $evaluated = $this->evaluated_results($pt->id);
        $user_id = Auth::user()->id;

        $old_details = new EvaluatedResult; 
        $old_details->pt_id = $pt->id; 
        $old_details->participant_id = $evaluated['participant_id'];         
        $old_details->user_id = $user_id;         
        $old_details->reason_for_change = $request->reason_for_change;         
        $old_details->results = json_encode($evaluated); 
        $old_details->save();
        
        
        return response()->json($result);
    }
        
    /**
     * Fetch feedback for the given id
     *
     * @param ID of the selected pt -  $id
     */

    public function show_updated_evaluated_results($id){

        $current_evaluated = $this->evaluated_results($id);
        $old = EvaluatedResult::select('evaluated_results.*')->where('pt_id', $id)->orderBy('id', 'DESC')->first();

        $old_results = json_decode($old->results, true);
        $old_results['reason_for_change'] = $old->reason_for_change;
        $old_results['editing_user_name'] = User::find($old->user_id)->name;
        $old_results['editing_updated_at'] = date($old->updated_at);

        return response()->json($old_results);


    }

    /**
     * Fetch feedback for the given id
     *
     * @param ID of the selected pt -  $id
     */

    public function print_result($id){
      $data = $this->evaluated_results($id);

      //display final report when the round is over
      if ($data['round_status'] ==0) {      
          if(\request('type') == 0){//satisfactory

              $pdf = PDF::loadView('result/feedbackreports/final/satisfactory', compact('data'));
          }

            if(\request('type') == 1){//unsatisfactory

                $pt = Pt::where('id',$id)->first();

                $pdf = PDF::loadView('result/feedbackreports/final/unsatisfactory', compact('data','pt'));
            }
        }

        //display preliminary results
        else{
            if(\request('type') == 0){//satisfactory

              $pdf = PDF::loadView('result/feedbackreports/preliminary/satisfactory', compact('data'));
          }

            if(\request('type') == 1){//unsatisfactory

                $pt = Pt::where('id',$id)->first();

                $pdf = PDF::loadView('result/feedbackreports/preliminary/unsatisfactory', compact('data','pt'));
            }
        }

        $pt = Pt::find($id);
        $user = User::find($pt->enrolment->user_id)->id;
        if ($pt->download_status == 0 && Auth::user()->id==$user) {
            $pt->download_status = Pt::DOWNLOAD_STATUS;
            $pt->save();
        }

        //if request is a view report
        if (\request('view') == 1) {
            return view('result/feedbackreports/index', compact('data', 'pt'));

        }else{
        //if request is a download
            return $pdf->download('Round '.$data['round_name'].' Results.pdf');
        }

    }
    public function feedback($id)
    {
        $pt = Pt::find($id);
        $usr = User::find($pt->enrolment->user_id);
        $pt->uid = (string)$usr->uid;
        $pt->tester = $usr->first_name . " " . $usr->middle_name . " " . $usr->last_name;
        try {
            $ru = $usr->ru();
        } catch (Exception $e) {
            $ru['tier'] = "";
            $ru['program_id'] = "";
        }
        $pt->program = Program::find($ru->program_id)->name;
        $facility = Facility::find($ru->tier);
        $pt->county = strtoupper($facility->subCounty->county->name);
        $pt->sub_county = $facility->subCounty->name;
        $pt->facility = $facility->name;
        $pt->round = $pt->enrolment->round->name;
        $pt->verdict = $pt->outcome($pt->feedback);
        $pt->remark = '';
        if($pt->feedback == Pt::UNSATISFACTORY)
            $pt->remark = 'Reason: '.$pt->unsatisfactory();
        $pt->date_authorized = Carbon::parse($pt->updated_at)->toFormattedDateString();
        $response = [
            'data' => $pt
        ];

        return response()->json($response);
    }

    public function importResults(Request $request){

        // Expected CSV file headers: 
        // Round, ID_No, Form_Number, Panel_Tested_Date, Panel_recv_Date, Panel_Const_Date,
        // Test2_Name, Test3_Name, Test1_Name, Kit1_Lot_No, Kit2_Lot_No, Kit3_Lot_No, Kit1_Exp_Date, Kit3_Exp_Date, 
        // Kit2_Exp_Date, PT1FinalResults, PT1TEST1Results, PT1TEST2Results, PT1TEST3Results, PT2FinalResults, 
        // PT2TEST1Results, PT2TEST2Results, PT2TEST3Results, PT3TEST1Results, PT3TEST2Results, PT3TEST3Results, 
        // PT3FinalResults, PT4FinalResults, PT4TEST1Results, PT4TEST2Results, PT4TEST3Results, PT5FinalResults, 
        // PT5TEST1Results, PT5TEST2Results, PT5TEST3Results, PT6FinalResults, PT6TEST1Results, PT6TEST3Results, 
        // PT6TEST2Results

        \Log::info("--- Importing PT results --- (System UID: ".Auth::user()->id." Name: ".Auth::user()->first_name.")");

        $fileName = $request->importResultFile;
        $handle = @fopen($fileName, "r");
        $reply = ['total' => 0, 'passed' => 0, 'failed' => 0, 'exist' => 0, 'not_enrolled' => 0, 'no_user' => 0];

        $headers = [];
        $data = [];

        if ($handle) {

            while (($buffer = fgets($handle, 4096)) !== false) {

                $buffer = str_replace(", ", "|||", $buffer);
                $reply['total']++;

                if ($reply['total'] == 1) {
                    $headers = explode(",", $buffer);
                }else{
                    $data = explode(",", $buffer);
                }

                if (count($headers) == count($data) && count($headers) > 0) {
                    $fullResultSet = [];
                    for ($i=0; $i < count($headers); $i++) { 
                        $fullResultSet[str_replace("\"", "", $headers[$i])] = str_replace("\"", "", str_replace("NULL", "", str_replace("|||", ", ", $data[$i])));
                    }

                    $resultSet['uid'] = $fullResultSet["ID_No"];
                    $resultSet['round_id'] = Round::idByTitle($fullResultSet['Round']);
                    $resultSet['field_1'] = $this->reformat($fullResultSet['Panel_recv_Date']);
                    $resultSet['field_2'] = $this->reformat($fullResultSet['Panel_Const_Date']);
                    $resultSet['field_3'] = $this->reformat($fullResultSet['Panel_Tested_Date']);

                    $resultSet['field_4'] = $this->getResultOptionID($fullResultSet['Test1_Name']);
                    $resultSet['field_5'] = $fullResultSet['Kit1_Lot_No'];
                    $resultSet['field_6'] = $this->reformat($fullResultSet['Kit1_Exp_Date']);

                    $resultSet['field_7'] = $this->getResultOptionID($fullResultSet['Test2_Name']);
                    $resultSet['field_8'] = $fullResultSet['Kit2_Lot_No'];
                    $resultSet['field_9'] = $this->reformat($fullResultSet['Kit2_Exp_Date']);

                    $resultSet['field_10'] = $this->getResultOptionID($fullResultSet['Test3_Name']);
                    $resultSet['field_11'] = $fullResultSet['Kit3_Lot_No'];
                    $resultSet['field_12'] = $this->reformat($fullResultSet['Kit3_Exp_Date']);

                    $resultSet['field_13'] = $this->getResultOptionID($fullResultSet['PT1TEST1Results']);
                    $resultSet['field_14'] = $this->getResultOptionID($fullResultSet['PT1TEST2Results']);
                    $resultSet['field_15'] = $this->getResultOptionID($fullResultSet['PT1TEST3Results']);
                    $resultSet['field_16'] = $this->getResultOptionID($fullResultSet['PT1FinalResults']);

                    $resultSet['field_17'] = $this->getResultOptionID($fullResultSet['PT2TEST1Results']);
                    $resultSet['field_18'] = $this->getResultOptionID($fullResultSet['PT2TEST2Results']);
                    $resultSet['field_19'] = $this->getResultOptionID($fullResultSet['PT2TEST3Results']);
                    $resultSet['field_20'] = $this->getResultOptionID($fullResultSet['PT2FinalResults']);

                    $resultSet['field_21'] = $this->getResultOptionID($fullResultSet['PT3TEST1Results']);
                    $resultSet['field_22'] = $this->getResultOptionID($fullResultSet['PT3TEST2Results']);
                    $resultSet['field_23'] = $this->getResultOptionID($fullResultSet['PT3TEST3Results']);
                    $resultSet['field_24'] = $this->getResultOptionID($fullResultSet['PT3FinalResults']);

                    $resultSet['field_25'] = $this->getResultOptionID($fullResultSet['PT4TEST1Results']);
                    $resultSet['field_26'] = $this->getResultOptionID($fullResultSet['PT4TEST2Results']);
                    $resultSet['field_27'] = $this->getResultOptionID($fullResultSet['PT4TEST3Results']);
                    $resultSet['field_28'] = $this->getResultOptionID($fullResultSet['PT4FinalResults']);

                    $resultSet['field_29'] = $this->getResultOptionID($fullResultSet['PT5TEST1Results']);
                    $resultSet['field_30'] = $this->getResultOptionID($fullResultSet['PT5TEST2Results']);
                    $resultSet['field_31'] = $this->getResultOptionID($fullResultSet['PT5TEST3Results']);
                    $resultSet['field_32'] = $this->getResultOptionID($fullResultSet['PT5FinalResults']);

                    $resultSet['field_33'] = $this->getResultOptionID($fullResultSet['PT6TEST1Results']);
                    $resultSet['field_34'] = $this->getResultOptionID($fullResultSet['PT6TEST2Results']);
                    $resultSet['field_35'] = $this->getResultOptionID($fullResultSet['PT6TEST3Results']);
                    $resultSet['field_36'] = $this->getResultOptionID($fullResultSet['PT6FinalResults']);

                    $resultSet['field_37'] = $fullResultSet['Comments'];

                    $stored = $this->storeImportedResult($resultSet);
                    switch ($stored) {
                        case 1:
                            $reply['passed']++; break;
                        case -1:
                            $reply['failed']++; break;
                        case -2:
                            $reply['not_enrolled']++; break;
                        case -3:
                            $reply['exist']++; break;
                        case -5:
                            $reply['no_user']++; break;
                    }

                }else{
                    $reply['failed']++;
                }
            }

            if($reply['total'] > 1)$reply['total']--;

            if (!feof($handle)) {
                $reply['failed']++;
                \Log::info("Error: unexpected fgets() fail\n");
            }
            fclose($handle);
        }

        return response()->json($reply);
    }

    function reformat($date){
        $returnDate = ""; 
        $parts = explode("/", $date);

        try {
            $returnDate = $parts[2]."-".str_pad($parts[1], 2, "0", STR_PAD_LEFT)."-".str_pad($parts[0], 2, "0", STR_PAD_LEFT);
        } catch (\ErrorException $e) {}

        return $returnDate;
    }

    function getResultOptionID($result){
        // Should really read from options db table
        $options = ['determine' => 1, 'first_response' => 2, 'sdbioline' => 3, 'other' => 4, 'reactive' => 5,
                'non_reactive' => 6, 'invalid' => 7, 'not_done' => 8, 'positive' => 9, 'negative' => 10, 'inconclusive' => 11
            ];
        $result = strtolower($result);
        $resultID = "";

        if (strlen($result) > 0) {
            try {
                $resultID = $options[$result];
            } catch (\ErrorException $e) {}
        }

        return $resultID;
    }

    /**
     * Store imported result in storage.
     *
     * @param  $resultSet
     * @return \Illuminate\Http\Response
     */
    function storeImportedResult($resultSet)
    {
        $success = -4; //error parsing the data

        try {
            
            //Check if round is set         
            if ($resultSet['round_id'] =="") {
                $success = -1;            
                \Log::info("UID: ".$resultSet['uid']." - Round information missing!");
            } else {   
                //  Save pt first then proceed to save form fields
                $roundID = $resultSet['round_id'];
                $user = User::where('uid', $resultSet['uid'])->first();

                if (!isset($user->id)) {
                    \Log::info("UID: ".$resultSet['uid']." - User does not exist in the database! Check soft deletes / disabled user!");
                    $success = -5;
                }else{
                    $userID = $user->id;
                    $enrolment = Enrol::where('user_id', $userID)->where('round_id', $roundID)->first();
                
                    //Validation: Check if the enrolment results have been submitted
                    if (!isset($enrolment->id)) {
                        $success = -2;
                        \Log::info("UID: ".$resultSet['uid']." - User is not enrolled to specified round ($roundID)!");
                    }else{
                        // Check if a result already exists
                        $pt = Pt::where('enrolment_id', $enrolment->id)->first();

                        if (!isset($pt->enrolment_id)) {
                            $pt = new Pt;
                            $pt->enrolment_id = $enrolment->id;
                            $pt->panel_status = Pt::NOT_CHECKED;
                            $pt->save();

                            //update enrollment status to 1
                            $enrolment->status = Enrol::DONE;
                            $enrolment->save();
                           
                            //  Proceed to form-fields
                            // get all fields and insert into results
                            $fields = Field::all();
                            $response = '';

                            foreach ($fields as $field) {
                                $result = new Result;
                                $result->pt_id = $pt->id;
                                $result->field_id = $field->id;
                               
                               //loop through the results entered and get the response for each field
                                foreach ($resultSet as $key => $value)
                                {
                                    if((stripos($key, 'token') !==FALSE) || (stripos($key, 'method') !==FALSE))
                                        continue;
                                    else if(stripos($key, 'field') !==FALSE)
                                    {
                                        $fieldId = (int)$this->strip($key);
                                        if(is_array($value))
                                          $value = implode(', ', $value);

                                        if ($field->id == $fieldId) {
                                            $response = $value;
                                            break;
                                        }else if ($field->id != $fieldId) {
                                            $response = '';
                                        }                      
                                    }                        
                                } 
                                // save the response for respective field
                                $result->response = $response;
                                $result->save();   
                            }

                            $success = 1;
                        }else{
                            $success = -3;
                            \Log::info("UID: ".$resultSet['uid']." - User already has a result for this round ($roundID)!");
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::info("Error parsing data row:");
            \Log::error($e);
            \Log::info(json_encode($resultSet));
        }

        return $success;
    }
}
