<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\County;
use App\SmsHandler;
use App\SubCounty;
use App\Facility;
use App\ImplementingPartner;
use App\Notification;
use \stdClass;

use Auth;
use DB;

class SmsController extends Controller
{


    public function manageSms()
    {
        return view('sms.index');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $items_per_page = 100;
        $error = ['error' => 'No results found, please try with different keywords.'];        
        $messages = Notification::latest()->withTrashed()->paginate($items_per_page);
        if($request->has('q')) 
        {
            
            $messages = Notification::where('message', $request->get('q'))->latest()->withTrashed()->paginate($items_per_page);           
           
        }

        $response = [
            'pagination' => [
                'total' => $messages->total(),
                'per_page' => $messages->perPage(),
                'current_page' => $messages->currentPage(),
                'last_page' => $messages->lastPage(),
                'from' => $messages->firstItem(),
                'to' => $messages->lastItem()
            ],
            'data' => $messages,
            
        ];

        return $messages->count() > 0 ? response()->json($response) : $error;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $create = new Notification;
        $create->message = $request->message;
        $create->description = $request->description;
        $create->save();

        return response()->json($create);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $message = Notification::find($id);
        $message->message = $request->message;
        $message->description = $request->description;
        $message->save();

        return response()->json($message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Notification::find($id)->delete();
        return response()->json(['done']);
    }


    public function restore($id)
    {
        $message = Notification::withTrashed()->find($id)->restore();
        return response()->json(['done']); 
    }

    public function select_users_message(Request $request)
    {           
         $users = array();
         $participant_phone = '';
         $to = '';
         $phone_numbers = array();
         $error =  ['error' => 'No Users Found'];
         $message = '';

        if ($request->user_type == 0) {
            $users = User::all();
            $to = 'All Users';
        }
        else if ($request->user_type == 2) {

            $roundID = $request->round_id;
            
            if ($request->participant == 1) {
                if ($request->facility_id) {
                    $facility = Facility::find($request->facility_id);

                    $users = $facility->enrolledUsers($roundID)->get();
                    $to = count($users).' participants in '. $facility->name;

                }else if ($request->sub_county_id) {
                    $sub_county = SubCounty::find($request->sub_county_id);

                    $users = $sub_county->enrolledUsers($roundID)->get();
                    $to = count($users).' participants in '. $sub_county->name;

                }else if ($request->county_id) {

                    $county = County::find($request->county_id);
                    $users = $county->enrolledUsers($roundID)->get();
                    $to = count($users).' participants in '. $county->name;
                }

            } else{            
                $user = new User;
                $users = $user->participants($roundID)->get();
                $to = 'All Participants';
            }
        }
        else if ($request->user_type == 3) {
            $user = new User;
           if ($request->partner == 1) {

                $partner_name = ImplementingPartner::find($request->partner_id)->name;
                $users = $user->partners($request->partner_id)->get();
                $to = 'Partners in '.$partner_name;

           } else{            
                $users = $user->partners()->get();
                $to = 'All Partners';
           }
        }
        else if ($request->user_type == 4) {
            $user = new User;
            if ($request->user_group == 1) {

                $county_name = County::find($request->county_id)->name;
                $users = $user->county_coordinators($request->county_id)->get();
                $to = 'County Coordinators in '.$county_name;

            } else{            
                $users = $user->county_coordinators()->get();
                $to = 'All County Coordinators';
            }
        }

        else if ($request->user_type == 7) {
            $user = new User;
           if ($request->user_group == 1) {

                $sub_county_name = SubCounty::find($request->sub_county_id)->name;
                $users = $user->sub_county_coordinators($request->sub_county_id)->get();
                $to = 'Sub County Coordinators in '.$sub_county_name;

           } else{            
                $users = $user->sub_county_coordinators()->get();
                $to = 'All Sub County Coordinators';
           }
        } 
        else if ($request->user_type == 8) {
                //convert to array for looping
                $user = User::find($request->participant_id);
                array_push($users, $user);
                $to = $user->name;
              
            }
        // get users phone numbers

        foreach ($users as $key => $user) {
            array_push($phone_numbers ,$user->phone);
        

            //get message to be sent
            if($request->message_id ){
                $message_details = Notification::find($request->message_id);
                //resend activation code
                if ($message_details->template ==Notification::ACTIVATION_CODE) {                    
                    // Send SMS
                    $message = Notification::where('template', Notification::ACTIVATION_CODE)->first()->message;
                    $message = $message .$user->sms_code;
                }
                //resend enabled account
                else if ($message_details->template ==Notification::USER_ENABLED) {                    
                    // Send SMS
                    $message = Notification::where('template', Notification::USER_ENABLED)->first()->message;
                    $message = $this->replace_between($message, '[', ']', $user->name);
                    $message = str_replace(' [', ' ', $message);
                    $message = str_replace(']', ' ', $message);   

                    $message = $this->replace_between($message, '{', '}', $user->uid);
                    $message = str_replace(' {', ' ', $message);
                    $message = str_replace('}', ' ', $message);        
                }

                //resend created account
                else if ($message_details->template ==Notification::USER_REGISTRATION) {                    
                    // Send SMS
                    $message = Notification::where('template', Notification::USER_REGISTRATION)->first()->message;
                    $message = $this->replace_between($message, '[', ']', $user->name);
                    $message = str_replace(' [', ' ', $message);
                    $message = str_replace(']', ' ', $message);   
                }
                else{

                    $message = Notification::find($request->message_id)->message;
                }               
            }
        }

        //process message
        $api = DB::table('bulk_sms_settings')->first();
        $response =[
            'phone_numbers' =>$phone_numbers,
            'message' => $message,
            'from' => $api->code,
            'to' => $to
        ];
        \Log::info("Users found: ".count($users));

        return count($users) > 0 ? response()->json($response) : $error;           
    }

    /*
    * Send the SMS
    */
    public function sendMessage(Request $request){

        $message = $request->message_to_send;
        $sms = new SmsHandler;

        //phone numbers come as one array, loop through it
        foreach ($request->phone_numbers as $phone) {   
            
            //convert the phone number string to array then send sms by looping through the array
            $phone_number = explode(',', $phone);

            foreach ($phone_number as  $number) {
                
                $sms->sendMessage($number, $message);
            }
        }       
    }
}
