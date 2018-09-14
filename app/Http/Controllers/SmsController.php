<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Registered;
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
         $message = '';

        if ($request->user_type == 0) {
            $users = User::all();
            $to = 'All Users';
        }
        else if ($request->user_type == 2) {
            
            if ($request->participant == 1) {
                if ($request->facility_id) {
                    $facility = Facility::find($request->facility_id);

                    $users = $facility->users()->get();
                    $to = count($users).' participants in '. $facility->name;

                }else if ($request->sub_county_id) {
                    $sub_county = SubCounty::find($request->sub_county_id);

                    $users = $sub_county->users()->get();
                    $to = count($users).' participants in '. $sub_county->name;

                }else if ($request->county_id) {

                    $county = County::find($request->county_id);
                    $users = $county->enrolledUsers()->get();
                    $to = count($users).' participants in '. $county->name;
                }

            } else{            
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
        //$from   = $api->username;
	$from = "NPHL";
        $response =[
            'phone_numbers' =>$phone_numbers,
            'message' => $message,
            'from' => $from,
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

        // send SMS
        $api = DB::table('bulk_sms_settings')->first();
        $username   = $api->username;
        $apikey = $api->api_key;
        //$from = $api->code;
	$from = "NPHL";
        $sms    = new Bulk($username, $apikey);

        //phone numbers come as one array, loop throug it
        foreach ($request->phone_numbers as $phone) {   
            
            //convert the phone number string to array then send sms by looping through the array
            $phone_number = explode(',', $phone);

            foreach ($phone_number as  $number) {
                
                try
                {                           
                    //print($number .' '.$message.'<br/>');
                    $sms->sendMessage($number, $message, $from);
                }                
                catch ( AfricasTalkingGatewayException $e )
                {
                    echo "Encountered an error while sending: ".$e->getMessage();
                }
            }
        }       
    }
   
   /* Register Controller registration code*/
    public function RegistrationVerificationCodeSms($user)
    {
      
     
       $message = Notification::where('template', 7)->withTrashed()->first();
       $message_to_send = $message->message .$user->sms_code;

       if ($message->deleted_at == NULL) 
       {
             
           
            try 
            {
                $smsHandler = new SmsHandler();
                //print_r($user->phone .' -> '.$message_to_send);
                $smsHandler->sendMessage($user->phone, $message_to_send);
            }
            catch ( AfricasTalkingGatewayException $e )
            {
                DB::table('role_user')->where('user_id', $user->id)->forceDelete();
                $user->forceDelete();
                abort(500, 'Encountered an error while sending verification code. Please try again later.');
            echo "Encountered an error while sending: ".$e->getMessage();
            } 

         try
           {
            //  Do Email verification for email address
            $user->email_verification_code = Str::random(60);
            $user->save();

           /* $usr = $user->toArray();
            Mail::send('auth.verification', $usr, function($message) use ($usr) {
                $message->to($usr['email']);
                $message->subject('National HIV PT - Email Verification Code');
            });*/
            event(new Registered($usr = $user));
          //  $this->guard()->login($user);
        }
        catch(Exception $e)
        {
            DB::table('role_user')->where('user_id', $user->id)->forceDelete();
            $user->forceDelete();
            abort(500, 'Encountered an error while sending verification code. Please try again later.');
        }
            
            
        }
        else {

            return response()->json(['done']);       
        }
    }
  /*To test*/
    public function ForgotPasswordResetVerificationCodeSms($user)
    {
      $message = Notification::where('template', 16)->first();
      $message_to_send = $message->message .$user->sms_code;

       if ($message->deleted_at != NULL) 
       {
       
            return response()->json(['done']);
        }
        else {
         
            $smsHandler = new SmsHandler();
            //Replace +254 prefix (if it exists) with 0
            $userPhone = str_replace("+254", "0", $user->phone);
          print_r($user->phone .' -> '.$message_to_send);            
            // $smsHandler->sendMessage($userPhone, $message_to_send);
        
       }

    }
  
   
    /* Participant Controller Approval Sms*/  
    public function ParticipantApprovalSms ($user)
    {
         
         $message = Notification::where('template', 8)->first();
         $smswithname = str_replace('[user->name]', $user->name, $message->message);
         $sms_to_send = str_replace('{user->tester id}', $user->id, $smswithname); 

       if ($message->deleted_at == NULL) 
       {
        
           try 
             {
                $smsHandler = new SmsHandler();
                //print_r($user->phone . '->' .$sms_to_send);
                $smsHandler->sendMessage($user->phone, $sms_to_send);
             }
             catch ( AfricasTalkingGatewayException $e )
             {
                echo "Encountered an error while sending: ".$e->getMessage();
            }
                
        }
        else {

            return response()->json(['done']);
         }
    }
    /*  Results verification and evaluated sms*/    
    public function ResultVerifySms($ptUser, $ptUserName, $round)
    {
         $sms = Notification::where('template', 2)->withTrashed()->first();

         $sms_with_username = str_replace("PT Participant" , $ptUserName , $sms->message);
         $sms_to_send = str_replace('[round]', $round, $sms_with_username);

       if ($sms->deleted_at == NULL) 
       {
       
       try
        {
            $smsHandler = new SmsHandler();
            //print_r($sms_to_send);
            $smsHandler->sendMessage($ptUser->phone, $sms_to_send);
         
        }
        catch ( AfricasTalkingGatewayException $e )
        {
            echo "Encountered an error while sending: ".$e->getMessage();
        }
            
        }
        else {

        return response()->json(['done']);
     }
    }

   /* User Controller Store function sms*/   
    public function UserCreatedSms($create)
    {
       $message = Notification::where('template', 9)->first();
       $message_to_send = str_replace('[user->name]', $create->name, $message->message);


       if ($message->deleted_at == NULL) 
       {
        
         try 
            {
                $smsHandler = new SmsHandler();
                //print_r($create->phone . '->' . $message_to_send);
                $smsHandler->sendMessage($create->phone, $message_to_send);
            }
            catch ( AfricasTalkingGatewayException $e )
            {
                echo "Encountered an error while sending: ".$e->getMessage();
            }
            
        }
        else {
          
          return response()->json(['done']);
        }

    }

   /*UserController Update function sms*/
  
    public function UserUpdateSms($user)
    {
         $message = Notification::where('template', 11)->first();
     

         $smswithname = str_replace('[user->name]', $user->name, $message->message);
         $sms_to_send = str_replace('[user->username]', $user->username, $smswithname);
         
         if ($message->deleted_at == NULL) 
        { 

            try 
            {
                $smsHandler = new SmsHandler();
                //print_r($user->phone . '->' . $sms_to_send);
                $smsHandler->sendMessage($user->phone, $sms_to_send);
            }
            catch ( AfricasTalkingGatewayException $e )
            {
                echo "Encountered an error while sending: ".$e->getMessage();
            }

        }
        else { 
         
             return response()->json(['done']);
        }
    }
  
   /*User controller destroy function sms*/
 
    public function UserDisableSms ($user)
    {
      
        $message = Notification::where('template', 12)->withTrashed()->first();
        $message_to_send = str_replace('[user->name]', $user->name,  $message->message);
          
        if ($message->deleted_at == NULL) 
        {
             try 
            {
                $smsHandler = new SmsHandler();
                //print_r($user->phone . '->' . $message_to_send);
                $smsHandler->sendMessage($user->phone, $message_to_send);
            }
            catch ( AfricasTalkingGatewayException $e )
            {
                echo "Encountered an error while sending: ".$e->getMessage();
             }
             
        }
          else { 
           
            return response()->json(['done']);
          
       }
    }

   /*User controller re-enable function sms*/
   
    public function UserRestoreSms ($user)
    {
     
          $message = Notification::where('template', 13)->withTrashed()->first();
          $message_to_send = str_replace('[user->name]', $user->name, $message->message);
        
        if ($message->deleted_at == NULL) 
        {
          try 
            {
                $smsHandler = new SmsHandler();
                $smsHandler->sendMessage($user->phone, $message_to_send);
            }
            catch ( AfricasTalkingGatewayException $e )
            {
                echo "Encountered an error while sending: ".$e->getMessage();
            }   
           
        }
        else { 
           
          return response()->json(['done']);
         
      }
    }
   
   /*RoundController sms sent to Sub/County Coordinators on round creation*/
    public function RoundCreationSms ($recipients, $round, $from, $apikey, $username)
    {
        $message = Notification::where('template', 10)->withTrashed()->first();
        $sms_to_send = str_replace('[round->name]', $round->name, $message->message);

        $message_to_send = str_replace('{round->enrollment_date}', $round->enrollment_date, $sms_to_send);

        if ($message->deleted_at == NULL) 
        {
           try
            {
                // Send messages
                $sms    = new Bulk($username, $apikey);
                //print_r($recipients . $round->enrollment_date . '->' .$message_to_send);
                $results = $sms->sendMessage($recipients, $message_to_send, $from);
            
            }
            catch ( AfricasTalkingGatewayException $e )
            {
            echo "Encountered an error while sending: ".$e->getMessage();
            }
        }else
        {
          return response()->json(['done']);            
        }
    }
 
    /*public function PanelDispatchSms($round)
    {
        $message = Notification::where('template', 1)->withTrashed()->first();
        $sms_to_send = str_replace('[round->name]', $round->name, $message->message);

      

        if ($message->deleted_at != NULL) 
        {
           try
            {
                // Send messages
                $sms    = new Bulk($username, $apikey);
                print_r($round . '->' .$message_to_send);

                // $results = $sms->sendMessage($recipients, $message_to_send, $from);
            
            }
            catch ( AfricasTalkingGatewayException $e )
            {
            echo "Encountered an error while sending: ".$e->getMessage();
            }
        }else
        {
          return response()->json(['done']);            
        }
    }*/
}
