<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\County;
// use App\Role;
use App\SmsHandler;
use App\SubCounty;
use App\Facility;
use App\ImplementingPartner;
// use App\Designation;
use App\Notification;
use \stdClass;

use App\Libraries\AfricasTalkingGateway as Bulk;

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
        
        $error = ['error' => 'No results found, please try with different keywords.'];        
        $messages = Notification::latest()->withTrashed()->paginate(5);
        if($request->has('q')) 
        {
            
            $messages = Notification::where('message', $request->get('q'))->latest()->withTrashed()->paginate(5);           
           
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
        $create = Notification::create($request->all());

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

    // public function roles()
    // {
    //     $error = ['errpr' => 'No results found.'];
    //     $roles = DB::table('roles')
    //                 ->select ('name', 'id')
    //                 ->where('display_name','participant')
    //                 ->orWhere('display_name', 'County Coordinator')
    //                 ->orWhere('display_name', 'Sub-County Coordinator')
    //                 ->orWhere('display_name', 'Partner')
    //                 ->orWhere('display_name', 'All Users')
    //                 ->latest()->paginate(5);
                  
    //      $response = [
    //         'pagination' => [
    //             'total' => $roles->total(),
    //             'per_page' => $roles->perPage(),
    //             'current_page' => $roles->currentPage(),
    //             'last_page' => $roles->lastPage(),
    //             'from' => $roles->firstItem(),
    //             'to' => $roles->lastItem()
    //         ],
    //         'data' => $roles
    //     ];
       
    //    return $roles->count() > 0 ? response()->json($response) : $error;
    // }


    public function select_users_message(Request $request)
    {           
         $users = array();
         $participant_phone = '';
         $to = '';
         $phone_numbers = array();
         $error =  ['error' => 'No Users Found'];

        if ($request->user_type == 0) {
            $users = User::all();
            $to = 'All Users';
        }
        else if ($request->user_type == 2) {
            
            if ($request->participant == 1) {
                if ($request->facility_id) {
                    $facility = Facility::find($request->facility_id);

                    $users = $facility->users()->get();
                    $to = 'Participants in '. $facility->name;

                }else if ($request->sub_county_id) {
                    $sub_county = SubCounty::find($request->sub_county_id);

                    $users = $sub_county->users()->get();
                    $to = 'Participants in '. $sub_county->name;

                }else if ($request->county_id) {

                    $county = County::find($request->county_id);
                    $users = $county->users()->get();
                    $to = 'Participants in '. $county->name;
                }

            } else if ($request->participant == 2) {
                //convert to array for looping
                $participant = User::find($request->participant_id);
                array_push($users, $participant);
                $to = $participant->name;
              
            }else{            
                $user = new User;
                $users = $user->participants()->get();
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
        // get users phone numbers

        foreach ($users as $key => $user) {
            array_push($phone_numbers ,$user->phone);
        }

        //get message to be sent
        $message = Notification::find($request->message_id)->message;
        $api = DB::table('bulk_sms_settings')->first();
        $from   = $api->username;
        $response =[
            'phone_numbers' =>$phone_numbers,
            'message' => $message,
            'from' => $from,
            'to' => $to
        ];

        return count($users) > 0 ? response()->json($response) : $error;           
    }

    /*
    * Send the SMS
    */
    public function sendMessage(Request $request){

        $message = $request->message_to_send;

        // send SMS
        $api = DB::table('bulk_sms_settings')->first();
        $from   = $api->username;
        $apikey = $api->api_key;

        //phone numbers come as one array, loop throug it
        foreach ($request->phone_numbers as $phone) {   
            
            //convert the phone number string to array then send sms by looping through the array
            $phone_number = explode(',', $phone);

            foreach ($phone_number as  $number) {
                
                try
                {                           
                    print($number .' '.$message.'<br/>');
                  /*$sms->sendMessage($number, $message, $from);*/
                }                
                catch ( AfricasTalkingGatewayException $e )
                {
                    echo "Encountered an error while sending: ".$e->getMessage();
                }
            }
        }       
    }
}
