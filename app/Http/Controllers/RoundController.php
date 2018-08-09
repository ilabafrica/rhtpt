<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Round;
use App\Enrol;
use App\Program;
use App\Facility;
use App\Notification;
use App\User;
use App\County;
use App\Role;
use App\SmsHandler;
use App\SubCounty;
use App\Designation;
use App\Pt;
use App\ImplementingPartner;

use App\Libraries\AfricasTalkingGateway as Bulk;

use Auth;
use DB;
use Jenssegers\Date\Date as Carbon;
use Excel;
use App;
use File;
use Hash;
//  Notification
use App\Notifications\WelcomeNote;
use App\Notifications\EnrollmentNote;

class RoundController extends Controller
{

    public function manageRound()
    {
        return view('round.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $error = ['error' => 'No results found, please try with different keywords.'];
        $today = Carbon::today();
        $rounds = Round::latest()->withTrashed()->paginate(5);
        if($request->has('q')) 
        {
            $search = $request->get('q');
            $rounds = Round::where('name', 'LIKE', "%{$search}%")->latest()->withTrashed()->paginate(5);
        }

        $response = [
            'pagination' => [
                'total' => $rounds->total(),
                'per_page' => $rounds->perPage(),
                'current_page' => $rounds->currentPage(),
                'last_page' => $rounds->lastPage(),
                'from' => $rounds->firstItem(),
                'to' => $rounds->lastItem()
            ],
            'data' => $rounds,
            'today'=>$today
        ];

        return $rounds->count() > 0 ? response()->json($response) : $error;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {      
        $rounds = Round::where('name', 'LIKE', "{$request->name}")->withTrashed()->get();

        if ($rounds->count() > 0) {

            return response()->json('1');

        }else if ($request->start_date > $request->end_date) {
           
            return response()->json('2');

        }else
        {        
            //save round
            $request->request->add(['user_id' => Auth::user()->id]);

            // $create = Round::create($request->all());
            $round = new Round;
            $round->name =$request->name;
            $round->description =$request->description;
            $round->start_date =$request->start_date;
            $round->enrollment_date =$request->enrollment_date;
            $round->end_date =$request->end_date;
            $round->user_id =$request->user_id;
            $round->save();

            //send sms
            $users = new User;
            $county_coordinators = $users->county_coordinators()->pluck('phone')->toArray();
            $subcounty_coordinators = $users->sub_county_coordinators()->pluck('phone')->toArray();
            $phone_numbers = array_merge($county_coordinators, $subcounty_coordinators);
            
            $recipients = NULL;
            $recipients = implode(",", $phone_numbers);
            //  Send SMS
            $message = 'Dear County/SubCounty Coordinator, NPHL has created Round'.$round->name.'. You have until'.$round->enrollment_date.' to enrol participants into this round.';            
            //  Bulk-sms settings
            $api = DB::table('bulk_sms_settings')->first();
            $username   = $api->username;
            $apikey     = $api->api_key;
            if($recipients)
            {
                // Specified sender-id
                $from = $api->code;
                // Create a new instance of Bulk SMS gateway.
                // use try-catch to filter any errors.
                try
                {
                    // Send messages
                    $sms    = new Bulk($username, $apikey);
                    $results = $sms->sendMessage($recipients, $message, $from);
                
                }
                catch ( AfricasTalkingGatewayException $e )
                {
                echo "Encountered an error while sending: ".$e->getMessage();
                }
            }
            return response()->json($round);
        }
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
        $request->request->add(['user_id' => Auth::user()->id]);

        $round = Round::find($id);
        $round->name =$request->name;
        $round->description =$request->description;
        $round->start_date =$request->start_date;
        $round->enrollment_date =$request->enrollment_date;
        $round->end_date =$request->end_date;
        $round->user_id =$request->user_id;
        $round->save();

        //send sms
        $users = new User;
        $county_coordinators = $users->county_coordinators()->pluck('phone')->toArray();
        $subcounty_coordinators = $users->sub_county_coordinators()->pluck('phone')->toArray();
        $phone_numbers = array_merge($county_coordinators, $subcounty_coordinators);
        // $phone_numbers = array(0=>'0723763026', 1=>'0723763026');
        
        $recipients = NULL;
        $recipients = implode(",", $phone_numbers);
        //  Send SMS
        $message = 'Dear County/SubCounty Coordinator, Round '.$round->name.' is now open for enrollment. Please enroll your participants between '.$round->start_date.' and '.$round->enrollment_date.'. Deadline is 5pm '.$round->enrollment_date;
        //  Bulk-sms settings
        $api = DB::table('bulk_sms_settings')->first();
        $username   = $api->username;
        $apikey     = $api->api_key;
        if($recipients)
        {
            // Specified sender-id
            $from = $api->code;
            // Create a new instance of Bulk SMS gateway.
            // use try-catch to filter any errors.
            try
            {
                // Send messages
                $sms    = new Bulk($username, $apikey);
                $results = $sms->sendMessage($recipients, $message, $from);
            
            }
            catch ( AfricasTalkingGatewayException $e )
            {
            echo "Encountered an error while sending: ".$e->getMessage();
            }
        }

        return response()->json($round);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Round::find($id)->delete();
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
        $round = Round::withTrashed()->find($id)->restore();
        return response()->json(['done']);
    }
    /**
     * Function to return list of rounds.
     *
     */
    public function rounds()
    {
        $rounds = Round::pluck('description', 'id');
        $categories = [];
        foreach($rounds as $key => $value)
        {
            $categories[] = ['id' => $key, 'value' => $value];
        }
        return $categories;
    }
    /**
     * Function to return list of rounds already done by a user.
     *
     */
    public function roundsDone()
    {
        // get enrolments with no submissions
        $ids = Auth::user()->enrol()->where('status', 0)->pluck('round_id');
        // fetch rounds details 
        $rounds = Round::whereIn('id', $ids)->pluck('description', 'id');
        // format to match dropdown values
        // dd($rounds);
        $categories = [];
        foreach($rounds as $key => $value)
        {
            $categories[] = ['id' => $key, 'value' => $value];
        }
        return $categories;
    }
    /**
     * Enrol a user(s).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function enrol(Request $request)
    {
        $roundId = $request->round_id;
        $phone_numbers = [];
        foreach($request->usrs as $key => $value)
        {
            $enrol = new Enrol;
            $enrol->user_id = (int)$value;
            $enrol->round_id = $roundId;
            $enrol->save();
            $user = User::find($enrol->user_id);
            if($user->phone)
            {
                array_push($phone_numbers, $user->phone);
            }
        }
        $recipients = NULL;
        $recipients = implode(",", $phone_numbers);
        //  Send SMS
        $round = Round::find($roundId)->name;
        $message = Notification::where('template', Notification::ENROLMENT)->first()->message;
        $message = ApiController::replace_between($message, '[', ']', $round);
        $message = str_replace(' [', ' ', $message);
        $message = str_replace('] ', ' ', $message);
        //  Bulk-sms settings
        $api = DB::table('bulk_sms_settings')->first();
        $username   = $api->username;
        $apikey     = $api->api_key;
        if($recipients)
        {
            // Specified sender-id
            $from = $api->code;
            // Create a new instance of Bulk SMS gateway.
            $sms    = new Bulk($username, $apikey);
            // use try-catch to filter any errors.
            try
            {
            // Send messages
            $results = $sms->sendMessage($recipients, $message, $from);
            foreach($results as $result)
            {
                // status is either "Success" or "error message" and save.
                $number = $result->number;
                //  Save the results
//                DB::table('broadcast')->insert(['number' => $number, 'bulk_id' => $bulk->id]);
            }
            }
            catch ( AfricasTalkingGatewayException $e )
            {
            echo "Encountered an error while sending: ".$e->getMessage();
            }
        }
        return response()->json('Enrolled.');
    }
    /**
     * Function to return possible durations of rounds.
     *
     */
    public function durations()
    {
        $response = [];
        $data = [
                    Round::ONE => "1 Month", 
                    Round::TWO => "2 Months", 
                    Round::THREE => "3 Months", 
                    Round::FOUR => "4 Months", 
                    Round::FIVE => "5 Months", 
                    Round::SIX => "6 Months",
                    Round::SEVEN => "7 Months", 
                    Round::EIGHT => "8 Months"
                ];
        foreach($data as $key => $value)
        {
            $response[] = ['id' => $key, 'value' => $value];
        }
        return $response;
    }

    /**
     * Function for load the view of participants to be enrolled
     *
     */
    public function manageEnrolParticipant()
    {
        return view('round.enrolparticipantlist');
    }

    //get the list of users to be enrolled
     public function loadparticipants(Request $request, $round=null)
    {
        if ($request->has('round')) {
            $round = $request->get('round');
        }
        $enrol_status=0;
        $error = ['error' => 'No results found, please try with different keywords.'];
        $participants = User::whereNotNull('uid')->get();
        if(Auth::user()->isCountyCoordinator())
        {
            $participants = County::find(Auth::user()->ru()->tier)->users()->get();
        }
        else if(Auth::user()->isSubCountyCoordinator())
        {
           $participants = SubCounty::find(Auth::user()->ru()->tier)->users()->get();
        }
        else if(Auth::user()->isFacilityInCharge())
        {
           $participants = Facility::find(Auth::user()->ru()->tier)->users()->get();
        }
        if($request->has('q')) 
        {
            $search = $request->get('q');
            $participants = User::where('name', 'LIKE', "%{$search}%")->orWhere('uid', 'LIKE', "%{$search}%")->get();
            if(Auth::user()->isCountyCoordinator())
            {
                $participants = County::find(Auth::user()->ru()->tier)->users()->where('users.name', 'LIKE', "%{$search}%")->orWhere('uid', 'LIKE', "%{$search}%")->get();
            }
            else if(Auth::user()->isSubCountyCoordinator())
            {
                $participants = SubCounty::find(Auth::user()->ru()->tier)->users()->where('users.name', 'LIKE', "%{$search}%")->orWhere('uid', 'LIKE', "%{$search}%")->get();
            }
            else if(Auth::user()->isFacilityInCharge())
            {
               $participants = Facility::find(Auth::user()->ru()->tier)->users()->where('users.name', 'LIKE', "%{$search}%")->orWhere('uid', 'LIKE', "%{$search}%")->get();
            }
        }
        //filter users by region
        if($request->has('county')) 
        {            
            $participants = County::find($request->get('county'))->users()->whereNull('uid')->latest()->withTrashed()->paginate(100);             
        }
         if($request->has('sub_county')) 
        {
            $participants = SubCounty::find($request->get('sub_county'))->users()->whereNull('uid')->latest()->withTrashed()->paginate(100);
        }
        if($request->has('facility')) 
        {
            $participants = Facility::find($request->get('facility'))->users()->whereNull('uid')->latest()->withTrashed()->paginate(100);
        }

        $enrolled_users = Enrol::where('round_id', $round)->pluck('user_id')->toArray();
       

        if ($request->has('enrolled')) {
            $participants = $participants->filter(function ($participant) use($enrolled_users){
                if (in_array($participant->id, $enrolled_users) ) {  
                    return $participant;
                }
            });

            $enrol_status = 1;
        } else {
            $participants = $participants->filter(function ($participant) use($enrolled_users){
                if (!in_array($participant->id, $enrolled_users) ) {  
                    return $participant;
                }
            });
        }
        
        foreach($participants as $participant)
        {   
            if(!empty($participant->ru()->tier))
            {
                $participant->facility = $participant->ru()->tier;
                $participant->program = $participant->ru()->program_id;
                $participant->gndr = $participant->maleOrFemale((int)$participant->gender);
                $facility = Facility::find($participant->ru()->tier);
                try{
                    $participant->mfl = $facility->code;
                    $participant->fac = $facility->name;

                    $participant->sub_county = $facility->subCounty->id;
                    $participant->county = $facility->subCounty->county->id;
                    $participant->sub = $facility->subCounty->name;
                    $participant->kaunti = $facility->subCounty->county->name;
                }catch(\Exception $ex){
                    \Log::error("Missing facility information!");
                    \Log::error($participant);
                    \Log::error($ex->getMessage());
                }
                try{
                    $participant->prog = Program::find($participant->ru()->program_id)->name;
                }catch(\Exception $ex){
                    \Log::error("Participant does not have a program!");
                    \Log::error($participant);
                    \Log::error($ex->getMessage());
                }
                try{
                    $participant->des = $participant->designation($participant->ru()->designation);
                    $participants->designation = $participant->ru()->designation;
                }catch(\Exception $ex){
                    \Log::error("Participant does not have a designation!");
                    \Log::error($participant);
                    \Log::error($ex->getMessage());
                }
            }
            else
            {
                $participant->facility = '';
                $participant->program = '';
            }
            !empty($participant->ru())?$participant->role = $participant->ru()->role_id:$participant->role = '';
            !empty($participant->ru())?$participant->rl = Role::find($participant->ru()->role_id)->name:$participant->rl = '';
            
        }
       
        $response = [           
            'data' => $participants,
            'role' => Auth::user()->ru()->role_id,
            'tier' => Auth::user()->ru()->tier,
            'enrol_status' =>$enrol_status
        ];

        return $participants->count() > 0 ? response()->json($response) : $error;   

    }
    /**
     * Function for load the view of participants to be enrolled
     *
     */
    public function manage_participant_info()
    {
        return view('round.participantinfo');
    }

    //get the list of users to be enrolled
     public function participants_info(Request $request, $round=null)
    {        
        $result_status='';
        $participants = '';
        $items_per_page = 100;
        $error = ['error' => 'No results found, please try with different keywords.'];
        
        //Get the enrolled users
        $enrolled_users = Enrol::where('round_id', $round)->pluck('user_id')->toArray();
        $users = new User;
        $all_participants = $users->participants();
        $active_participants = $all_participants->count();
        $total_participants = $all_participants->withTrashed()->count();

        //compare list of participants id  to enrolled users id-
        $participants = $all_participants->limit(1000)->get()->filter(function ($participant) use($enrolled_users){
                if (in_array($participant->id, $enrolled_users) ) {  
                    return $participant;
                   
                }
            });

        $enrolled_participants = count(array_unique($enrolled_users));
        
       
        if(Auth::user()->isCountyCoordinator())
        {
            $all_participants = County::find(Auth::user()->ru()->tier)->users();
            $active_participants = $all_participants->count();
            $total_participants = $all_participants->withTrashed()->count();
            $participants = $all_participants->get()->filter(function ($participant) use($enrolled_users){
                if (in_array($participant->id, $enrolled_users) ) {  
                    return $participant;                   
                }
            });

            $enrolled_participants = $participants->count();
        }
        else if(Auth::user()->isSubCountyCoordinator())
        {
            $all_participants = SubCounty::find(Auth::user()->ru()->tier)->users();
            $active_participants = $all_participants->count();
            $total_participants = $all_participants->withTrashed()->count();
            $participants = $all_participants->get()->filter(function ($participant) use($enrolled_users){
                if (in_array($participant->id, $enrolled_users) ) {  
                    return $participant;                   
                }
            });

            $enrolled_participants = $participants->count();
        }
        else if(Auth::user()->isPartner())
        {
            $all_participants = ImplementingPartner::find(Auth::user()->ru()->tier)->users();
            $active_participants = $all_participants->count();
            $total_participants = $all_participants->withTrashed()->count();
            $participants = $all_participants->get()->filter(function ($participant) use($enrolled_users){
                if (in_array($participant->id, $enrolled_users) ) {  
                    return $participant;                   
                }
            });

            $enrolled_participants = $participants->count();
        }
        else if(Auth::user()->isFacilityInCharge())
        {
            $all_participants = Facility::find(Auth::user()->ru()->tier)->users();
            $active_participants = $all_participants->count();
            $total_participants = $all_participants->withTrashed()->count();
            $participants = $all_participants->get()->filter(function ($participant) use($enrolled_users){
                if (in_array($participant->id, $enrolled_users) ) {  
                    return $participant;                   
                }
            });

            $enrolled_participants = $participants->count();
        }
        if($request->has('q')) 
        {
            $search = $request->get('q');
            $participants = $users->participants()->where('name', 'LIKE', "%{$search}%")->orWhere('uid', 'LIKE', "%{$search}%")->whereIn('users.id', $enrolled_users)->latest()->withTrashed()->paginate($items_per_page);
            if(Auth::user()->isCountyCoordinator())
            {
                $participants = County::find(Auth::user()->ru()->tier)->users()->where('users.name', 'LIKE', "%{$search}%")->orWhere('uid', 'LIKE', "%{$search}%")->whereIn('users.id', $enrolled_users)->latest()->withTrashed()->paginate($items_per_page);
            }
            else if(Auth::user()->isSubCountyCoordinator())
            {
                $participants = SubCounty::find(Auth::user()->ru()->tier)->users()->where('users.name', 'LIKE', "%{$search}%")->orWhere('uid', 'LIKE', "%{$search}%")->whereIn('users.id', $enrolled_users)->latest()->withTrashed()->paginate($items_per_page);
            }
            else if(Auth::user()->isFacilityInCharge())
            {
               $participants = Facility::find(Auth::user()->ru()->tier)->users()->where('users.name', 'LIKE', "%{$search}%")->orWhere('uid', 'LIKE', "%{$search}%")->whereIn('users.id', $enrolled_users)->latest()->withTrashed()->paginate($items_per_page);
            }
        }
        //filter users by region
        if($request->has('county')) {                 
            $all_participants = County::find($request->get('county'))->users();
            $total_participants = $all_participants->withTrashed()->count();
            $active_participants = $all_participants->count();
            $participants = $all_participants->get()->filter(function ($participant) use($enrolled_users){
                if (in_array($participant->id, $enrolled_users) ) {  
                    return $participant;                   
                }
            });

            $enrolled_participants = $participants->count();
        
        }
        
         if($request->has('sub_county')) 
        {
            $all_participants = SubCounty::find($request->get('sub_county'))->users();
            $active_participants = $all_participants->count();
            $total_participants = $all_participants->withTrashed()->count();            
            $participants = $all_participants->get()->filter(function ($participant) use($enrolled_users){
                if (in_array($participant->id, $enrolled_users) ) {  
                    return $participant;                   
                }
            });

            $enrolled_participants = $participants->count();
        }
        if($request->has('facility')) 
        {
            $all_participants = Facility::find($request->get('facility'))->users();
            $active_participants = $all_participants->count();
            $total_participants = $all_participants->withTrashed()->count();            
            $participants = $all_participants->get()->filter(function ($participant) use($enrolled_users){
                if (in_array($participant->id, $enrolled_users) ) {  
                    return $participant;                   
                }
            });

            $enrolled_participants = $participants->count();
        }
     
        foreach($participants as $participant)
        {   
            if(!empty($participant->ru()->tier))
            {
                $facility = Facility::find($participant->ru()->tier);
                //$participant->facility = $participant->ru()->tier;
                //$participant->sub_county = $facility->subCounty->id;
                //$participant->county = $facility->subCounty->county->id;
                
    		if ($facility) {
    		    $participant->facility_name = $facility->name;
            	    $participant->sub_county_name = $facility->subCounty->name;
                        $participant->county_name = $facility->subCounty->county->name;               
    		}else{
                    $participant->facility = '';
                    $participant->sub_county = '';
                    $participant->county = '';   
                } 
            }else
            {
                $participant->facility = '';
            }            

            $enrollment = Enrol::where('round_id', $round)->where('user_id', $participant->id)->first();
            if ($enrollment) {            
                $pt = Pt::where('enrolment_id', $enrollment->id)->first();

                if ($pt) {
                    $participant->result_status = $pt->panel_status;                
                }else{

                    $participant->result_status = 'N/A';                
                }
            }else
            {
                $participant->result_status = 'N/A';                
            }            
        }
       
        $response = [                      
            'data' => $participants->sortBy('name'),
            'role' => Auth::user()->ru()->role_id,
            'tier' => Auth::user()->ru()->tier,
            'total_participants'=>$total_participants,
            'active_participants' => $active_participants,
            'enrolled_participants' => $enrolled_participants
        ];

        return $participants->count() > 0 ? response()->json($response) : $error;
    }
    /**
     * Function to download the participants in the past round
     *
     */
    public function testerSummary(Request $request)
    {
        $rId = $request->id;
        $roundId = Round::find($rId)->name;
        $suffix = "ROUND ".$roundId." PARTICIPANTS SUMMARY";
        $title = "";
        $users = NULL;
        $roleId = Role::idByName('Participant');        
        //check if anyone registered for that round is enrolled to tha round
        $p = Enrol::where('round_id',$rId)->get();
        if(count($p) == 0){
            Session()->flash ('message',"The round has no participants please add participants to the round");
            return back();
        }
        //  workbook title
        if(Auth::user()->isCountyCoordinator())
            $title = County::find(Auth::user()->ru()->tier)->name." COUNTY ".$suffix;
        else
            $title = "KENYA RAPID HIV PT ".$suffix;
        return Excel::create($title, function($excel) use ($rId, $roundId, $users, $roleId, $request) 
        {
            $round = Round::find($rId);
            if(Auth::user()->isCountyCoordinator())
            {
                $countyId = Auth::user()->ru()->tier;
                $county = County::find($countyId)->name;
                //  sub-counties and facilities
                $fIds = County::find($countyId)->facilities()->pluck('id');
                $ids = DB::table('role_user')->where('role_id', $roleId)->whereIn('tier', $fIds)->pluck('user_id');
                if($request->status)
                    $ids = User::whereIn('id', $ids)->whereBetween('date_registered', [$round->start_date, $round->end_date])->pluck('id');
                $testers = Enrol::where('round_id', $rId)->whereIn('user_id', $ids)->pluck('user_id')->toArray();
                $testers = implode(",", $testers);               
                $summary = [];
                $sheetTitle = $county;
                if (empty($testers)) {
                    $summary[] = [
                        'County' => '',
                        'Sub County' => '',
                        'Facility' => '',
                        'MFL Code' => '',
                        'Tester Enrollment ID' => '',
                        'Tester First Name' => '',
                        'Tester Surname' => '',
                        'Tester Other Name' => '',
                        'Gender' => '',
                        'Tester Mobile Number' => '',
                        'Tester Email' => '',
                        'Tester Address' => '',
                        'Designation' => '',
                        'Program' => '',
                        'In Charge' => '',
                        'In Charge Email' => '',
                        'In Charge Phone' => '',
                    ];
                }else{
                    $data = DB::select(
                        "SELECT
                        u.first_name AS 'Tester First Name',
                        u.last_name AS 'Tester Surname',
                        u.middle_name AS 'Tester Other Name',
                        u.uid AS 'Tester Enrollment ID',
                        u.gender AS 'Gender',
                        u.phone AS 'Tester Mobile Number',
                        u.email AS 'Tester Email',
                        u.address AS 'Tester Address',
                        p.name AS 'Program',
                        ru.designation AS 'Designation',
                        f.name AS 'Facility',
                        f.code AS 'MFL Code',
                        f.in_charge AS 'In Charge',
                        f.in_charge_phone AS 'In Charge Phone',
                        f.in_charge_email AS 'In Charge Email'
                        FROM users u, facilities f, role_user ru, programs p
                        WHERE u.id = ru.user_id
                            AND ru.program_id = p.id
                            AND ru.tier = f.id
                            AND ru.program_id = p.id
                            AND u.id
                        IN (".$testers.") ORDER BY u.uid ASC;");
                    
                    foreach($data as $key => $value)
                    {
                        $tfirst_name = NULL;
                        $tmiddle_name = NULL;
                        $tlast_name = NULL;
                        $tuid = NULL;
                        $tphone = NULL;
                        $temail = NULL;
                        $tprog = NULL;
                        $tdes = NULL;
                        $facility = NULL;
                        $mfl = NULL;
                        $icharge = NULL;
                        $iphone = NULL;
                        $iemail = NULL;
                        $tcounty = NULL;
                        $tsub_county = NULL;
                        $tgender = NULL;
                        $taddress = NULL;
                        foreach($value as $mike => $ross)
                        {
                            if(strcasecmp("County", $mike) == 0)
                                $tcounty = $ross;
                            if(strcasecmp("Sub County", $mike) == 0)
                                $tsub_county = $ross;
                            if(strcasecmp("Facility", $mike) == 0)
                                $facility = $ross;
                            if(strcasecmp("MFL Code", $mike) == 0)
                                $mfl = $ross;
                            if(strcasecmp("Tester Enrollment ID", $mike) == 0)
                                $tuid = $ross;
                            if(strcasecmp("Tester First Name", $mike) == 0)
                                $tfirst_name = $ross;
                            if(strcasecmp("Tester Surname", $mike) == 0)
                                $tlast_name = $ross;
                            if(strcasecmp("Tester Other Name", $mike) == 0)
                                $tmiddle_name = $ross;
                            if(strcasecmp("Gender", $mike) == 0)
                                $tgender = $ross;
                            if(strcasecmp("Tester Mobile Number", $mike) == 0)
                                $tphone = $ross;
                            if(strcasecmp("Tester Email", $mike) == 0)
                                $temail = $ross;
                            if(strcasecmp("Tester Address", $mike) == 0)
                                $taddress = $ross;
                            if(strcasecmp("Designation", $mike) == 0)
                                $tdes = $ross;
                            if(strcasecmp("Program", $mike) == 0)
                                $tprog = $ross;
                            if(strcasecmp("In Charge", $mike) == 0)
                                $icharge = $ross;
                            if(strcasecmp("In Charge Email", $mike) == 0)
                                $iemail = $ross;
                            if(strcasecmp("In Charge Phone", $mike) == 0)
                                $iphone = $ross;
                        }
                        $summary[] = [
                            
			     // 'County' => $tcounty,
                            // 'Sub County' => $tsub_county,
                            'County' => Facility::where('code', $mfl)->orderBy('name', 'asc')->first()->subCounty->county->name,
                            'Sub County' => Facility::where('code', $mfl)->orderBy('name', 'asc')->first()->subCounty->name,
                            'Facility' => $facility,
                            'MFL Code' => $mfl,
                            'Tester Enrollment ID' => $tuid,
                            'Tester First Name' => $tfirst_name,
                            'Tester Surname' => $tlast_name,
                            'Tester Other Name' => $tmiddle_name,
                            'Gender' => $tgender,
                            'Tester Mobile Number' => $tphone,
                            'Tester Email' => $temail,
                            'Tester Address' => $taddress,
                            'Designation' => $tdes,
                            'Program' => $tprog,
                            'In Charge' => $icharge,
                            'In Charge Email' => $iemail,
                            'In Charge Phone' => $iphone,
                        ];
                    }
                }
                $excel->sheet($sheetTitle, function($sheet) use ($summary) {
                    $sheet->fromArray($summary);
                });
            }
            else
            {
                $counties = County::all();
                foreach($counties as $county)
                {
                    $fIds = $county->facilities()->pluck('id');
                    $ids = DB::table('role_user')->where('role_id', $roleId)->whereIn('tier', $fIds)->pluck('user_id');
                    if($request->status)
                        $ids = User::whereIn('id', $ids)->whereBetween('date_registered', [$round->start_date, $round->end_date])->pluck('id');
                    
                    $testers = Enrol::where('round_id', $round->id)->whereIn('user_id', $ids)->pluck('user_id')->toArray();
                    if(count($testers) > 0)
                    {
                        $testers = implode(",", $testers);
                        // dd($testers);
                        $sheetTitle = $county->name;
                        if (count($testers)>0) {
                    $data = DB::select(
                        "SELECT
                        u.first_name AS 'Tester First Name',
                        u.last_name AS 'Tester Surname',
                        u.middle_name AS 'Tester Other Name',
                        u.uid AS 'Tester Enrollment ID',
                        u.gender AS 'Gender',
                        u.phone AS 'Tester Mobile Number',
                        u.email AS 'Tester Email',
                        u.address AS 'Tester Address',
                        p.name AS 'Program',
                        ru.designation AS 'Designation',
                        f.name AS 'Facility',
                        f.code AS 'MFL Code',
                        f.in_charge AS 'In Charge',
                        f.in_charge_phone AS 'In Charge Phone',
                        f.in_charge_email AS 'In Charge Email'
                        FROM users u, facilities f, role_user ru, programs p
                        WHERE u.id = ru.user_id
                            AND ru.program_id = p.id
                            AND ru.tier = f.id
                            AND ru.program_id = p.id
                            AND u.id
                        IN (".$testers.") ORDER BY u.uid ASC;");

                            // dd($data);
                            //  create associative array
                            $summary = [];
                            foreach($data as $key => $value)
                            {
                                $tfirst_name = NULL;
                                $tmiddle_name = NULL;
                                $tlast_name = NULL;
                                $tuid = NULL;
                                $tphone = NULL;
                                $temail = NULL;
                                $tprog = NULL;
                                $tdes = NULL;
                                $facility = NULL;
                                $mfl = NULL;
                                $icharge = NULL;
                                $iphone = NULL;
                                $iemail = NULL;
                                $tcounty = NULL;
                                $tsub_county = NULL;
                                $tgender = NULL;
                                $taddress = NULL;
                                foreach($value as $mike => $ross)
                                {
                                    if(strcasecmp("County", $mike) == 0)
                                        $tcounty = $ross;
                                    if(strcasecmp("Sub County", $mike) == 0)
                                        $tsub_county = $ross;
                                    if(strcasecmp("Facility", $mike) == 0)
                                        $facility = $ross;
                                    if(strcasecmp("MFL Code", $mike) == 0)
                                        $mfl = $ross;
                                    if(strcasecmp("Tester Enrollment ID", $mike) == 0)
                                        $tuid = $ross;
                                    if(strcasecmp("Tester First Name", $mike) == 0)
                                        $tfirst_name = $ross;
                                    if(strcasecmp("Tester Surname", $mike) == 0)
                                        $tlast_name = $ross;
                                    if(strcasecmp("Tester Other Name", $mike) == 0)
                                        $tmiddle_name = $ross;
                                    if(strcasecmp("Gender", $mike) == 0)
                                        $tgender = $ross;
                                    if(strcasecmp("Tester Mobile Number", $mike) == 0)
                                        $tphone = $ross;
                                    if(strcasecmp("Tester Email", $mike) == 0)
                                        $temail = $ross;
                                    if(strcasecmp("Tester Address", $mike) == 0)
                                        $taddress = $ross;
                                    if(strcasecmp("Designation", $mike) == 0)
                                        $tdes = $ross;
                                    if(strcasecmp("Program", $mike) == 0)
                                        $tprog = $ross;
                                    if(strcasecmp("In Charge", $mike) == 0)
                                        $icharge = $ross;
                                    if(strcasecmp("In Charge Email", $mike) == 0)
                                        $iemail = $ross;
                                    if(strcasecmp("In Charge Phone", $mike) == 0)
                                        $iphone = $ross;
                                }
                                $summary[] = [
                                    'County' => Facility::where('code', $mfl)->orderBy('name', 'asc')->first()->subCounty->name,
                                    'Sub County' => Facility::where('code', $mfl)->orderBy('name', 'asc')->first()->subCounty->county->name,
                                    'Facility' => $facility,
                                    'MFL Code' => $mfl,
                                    'Tester Enrollment ID' => $tuid,
                                    'Tester First Name' => $tfirst_name,
                                    'Tester Surname' => $tlast_name,
                                    'Tester Other Name' => $tmiddle_name,
                                    'Gender' => ($tgender == 1) ? 'Female' : 'Male',
                                    'Tester Mobile Number' => $tphone,
                                    'Tester Email' => $temail,
                                    'Tester Address' => $taddress,
                                    //'Designation' => Designation::find($tdes)->name,
                                    'Program' => $tprog,
                                    'In Charge' => $icharge,
                                    'In Charge Email' => $iemail,
                                    'In Charge Phone' => $iphone,
                                ];
                            }
                        }
                        $excel->sheet($sheetTitle, function($sheet) use ($summary) {
                            $sheet->fromArray($summary);
                        });
                    }
                }
            }
        })->download('xlsx');
    }
    
    /**
     * Batch registration and enrollment
     *
     */
    public function batchRegisterAndEnrol(Request $request)
    {
        $rId = $request->id;
        $id = Round::find($rId)->name;
        $exploded = explode(',', $request->excel);
        $decoded = base64_decode($exploded[1]);
        if(str_contains($exploded[0], 'sheet'))
            $extension = 'xlsx';
        else
            $extension = 'xls';
        $fileName = uniqid().'.'.$extension;
        $county = 0;
        if(Auth::user()->isCountyCoordinator())
        {
            $county = County::find(Auth::user()->ru()->tier)->name;
            $folder = '/batch/'.$id.'/'.$county.'/';
        }
        else
            $folder = '/batch/'.$id.'/nphls/';
        if(!is_dir(public_path().$folder))
            File::makeDirectory(public_path().$folder, 0777, true);
        file_put_contents(public_path().$folder.$fileName, $decoded);

        //  get today's date
        $today = Carbon::today();
        //  Handle the import
        //  Get the results
        //  Import a user provided file
        //  Convert file to csv
        if(Auth::user()->isCountyCoordinator())
            $loadedFile = Excel::load('public/batch/'.$id.'/'.$county.'/'.$fileName, function($reader) {$reader->ignoreEmpty();})->get();
        else
            $loadedFile = Excel::load('public/batch/'.$id.'/nphls/'.$fileName, function($reader) {$reader->ignoreEmpty();})->get();
        if(!empty($loadedFile) && $loadedFile->count())
        {
            $duplicates = array();
            foreach($loadedFile as $sheet){
                foreach ($sheet->toArray() as $harvey => $specter)
                {
                    if(!empty($specter))
                    {
                        $county = NULL;
                        $sub_county = NULL;
                        $facility = NULL;
                        $mfl = NULL;
                        $uid = NULL;
                        $tfname = NULL;
                        $tsname = NULL;
                        $toname = NULL;
                        $tgender = NULL;
                        $tphone = NULL;
                        $temail = NULL;
                        $taddress = NULL;
                        $tdes = NULL;
                        $tprog = NULL;
                        $incharge = NULL;
                        $iphone = NULL;
                        $iemail = NULL;
                        foreach ($specter as $mike => $ross) 
                        {
                            if(strcmp($mike, "county") === 0)
                                $county = $ross;
                            if(strcmp($mike, "sub_county") === 0)
                                $sub_county = $ross;
                            if(strcmp($mike, "facility") === 0)
                                $facility = $ross;
                            if(strcmp($mike, "mfl_code") === 0)
                                $mfl = $ross;
                            if(strcmp($mike, "tester_enrollment_id") === 0)
                                $uid = $ross;
                            if(strcmp($mike, "tester_first_name") === 0)
                                $tfname = $ross;
                            if(strcmp($mike, "tester_surname") === 0)
                                $tsname = $ross;
                            if(strcmp($mike, "tester_other_name") === 0)
                                $toname = $ross;
                            if(strcmp($mike, "gender") === 0)
                                $tgender = $ross;
                            if(strcmp($mike, "tester_mobile_number") === 0)
                                $tphone = $ross;
                            if(strcmp($mike, "tester_email") === 0)
                                $temail = $ross;
                            if(strcmp($mike, "tester_address") === 0)
                                $taddress = $ross;
                            if(strcmp($mike, "designation") === 0)
                                $tdes = $ross;
                            if(strcmp($mike, "program") === 0)
                                $tprog = $ross;
                            if(strcmp($mike, "in_charge") === 0)
                                $incharge = $ross;
                            if(strcmp($mike, "in_charge_email") === 0)
                                $iemail = $ross;
                            if(strcmp($mike, "in_charge_phone") === 0)
                                $iphone = $ross;
                        }
                        //  process gender
                        // if(strcmp($tgender, "Male") === 0)
                        //     $tgender = User::MALE;
                        // else
                        //     $tgender = User::FEMALE;
                        //  process designation
                        $testerDes = $tdes;
                        if($testerDes)
                        {
                            $tdes = Designation::idByTitle($tdes);
                            if(!$tdes)
                            {
                                $des = new Designation;
                                $des->name = $testerDes;
                                $des->save();
                                $tdes = $des->id;
                            }
                        }

                        if(strcmp($county, "MURANGA") === 0)
                            $county = "Murang'a";
                        if(strcmp($county, "HOMABAY") === 0)
                            $county = "Homa Bay";
                        if(strcmp($county, "THARAKA-NITHI") === 0)
                            $county = "Tharaka Nithi";

                            if ($mfl == 'NULL'&& ''){
                                // $missing_facilities[] = array($tfname, $tsname, $mfl, 'Missing Facility');
                                $facilityId = 0;
                                $missing_facilities = array($tfname, $tsname, $mfl, 'Missing Facility');
                                array_push($duplicates, $missing_facilities);
                            }
                            else{
                                $facilityId = Facility::idByCode($mfl);
                            }

                         //    if(!$facilityId)
                         //    {   
                         //        $missing_facilities = array($tfname, $tsname, $mfl, 'Missing Facility');
                         //        array_push($duplicates, $missing_facilities);

                         //    }else{
                                //  process user details only if the name exists
                                if($uid)
                                {
                                    $user = User::find(User::idByUid($uid));
                                    if(!$user)
                                    {
                                        $user = new User;
                                        $user->uid = $uid;
                                    }
                                }
                                else
                                {
                                    $userId = User::idByPhone($tphone);
                                    if($userId)
                                    {
                                        $user = User::find($userId);
                                        $user->uid = $user->uid;
                                    }
                                    else
                                    {
                                        $user = new User;
                                        $user->date_registered = $today;
                                        $count = count(User::where('uid', User::MIN_UNIQUE_ID)->get());
                                        if($count > 0)
                                            $user->uid = DB::table('users')->where('uid', '>=', User::MIN_UNIQUE_ID)->max('uid')+1;
                                        else
                                            $user->uid = User::MIN_UNIQUE_ID;
                                        
                                    }
                                }
                                //  process user details
                                if($tfname && $tsname && $tphone && $temail && $tprog && $tdes)
                                {   
                                    if ($tphone == 'NULL' || $tphone == '') {
                                        $duplicateParticapant = array($tfname, $tsname, $tphone, 'Missing Phone Number');
                                        $duplicates[] = $duplicateParticapant;
                                    }
                                    if(count(User::where('phone', $tphone)->get()) > 0){
                                        $duplicateParticapant = array($tfname, $tsname, $tphone, 'Phone already taken');
                                        $duplicates[] = $duplicateParticapant;
                                        continue;
                                    }
                                    $user->name = $tfname. " ".$toname. " ".$tsname;
                                    $user->first_name = $tfname;
                                    $user->middle_name = $toname;
                                    $user->last_name = $tsname;
                                    $user->gender = $tgender;
                                    $user->email = $temail;
                                    $user->phone = $tphone;
                                    $user->address = $taddress;
                                    $user->username = uniqid();
                                    $user->phone_verified = 1;
                                    $user->email_verified = 1;
                                    $user->save();
                                    $user->username = $user->uid;
                                    $user->password = Hash::make(User::DEFAULT_PASSWORD);
                                    $user->save();
                                    $userId = $user->id;

                                    //  Prepare to save facility details
                                    if ($facilityId >0) {
                                        
                                        $fc = Facility::find($facilityId);
                                        $fc->in_charge = $incharge;
                                        $fc->in_charge_phone = $iphone;
                                        $fc->in_charge_email = $iemail;
                                        $fc->save();
                                    }
                                    //  Prepare to save role-user details
                                    $roleId = Role::idByName('Participant');
                                    $user->detachAllRoles();
                                    DB::table('role_user')->insert(['user_id' => $userId, 'role_id' => $roleId, 'tier' => $facilityId, 'program_id' => Program::idByTitle($tprog), "designation" => $tdes]);
                        }
                            
                            //  send email and sms for registration
/*                   
                            if($user->date_registered)
                            {
                                //  send email and sms
                                $token = app('auth.password.broker')->createToken($user);
                                $user->token = $token;
                                // $user->notify(new WelcomeNote($user));
                                $message    = "Dear ".$user->name.", NPHL has approved your request to participate in PT. Your tester ID is ".$user->uid.". Go to rhtpt.or.ke to get started.";
                                try 
                                {
                                    $smsHandler = new SmsHandler();
                                    $smsHandler->sendMessage($user->phone, $message);
                                }
                                catch ( AfricasTalkingGatewayException $e )
                                {
                                    echo "Encountered an error while sending: ".$e->getMessage();
                                }
                            }                            
*/                            
                        // }
                    }
                }
            }
            return response()->json(array('errors' => $duplicates));
        }
    }
}
$excel = App::make('excel');
