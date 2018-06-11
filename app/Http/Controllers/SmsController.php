<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\County;
use App\Role;
use App\SmsHandler;
use App\SubCounty;
use App\Designation;
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

    public function roles()
    {
        $error = ['errpr' => 'No results found.'];
        $roles = DB::table('roles')
                    ->select ('name', 'id')
                    ->where('display_name','participant')
                    ->orWhere('display_name', 'County Coordinator')
                    ->orWhere('display_name', 'Sub-County Coordinator')
                    ->orWhere('display_name', 'Partner')
                    ->orWhere('display_name', 'All Users')
                    ->latest()->paginate(5);
                  
         $response = [
            'pagination' => [
                'total' => $roles->total(),
                'per_page' => $roles->perPage(),
                'current_page' => $roles->currentPage(),
                'last_page' => $roles->lastPage(),
                'from' => $roles->firstItem(),
                'to' => $roles->lastItem()
            ],
            'data' => $roles
        ];
       
       return $roles->count() > 0 ? response()->json($response) : $error;
    }


    public function sendMessage(Request $request)

    {                    
        $message_to_send = '';
        $message =  $request->message;
        $users = $request->users;
        $county = $request->county_ids;
        $cnt = $request->county;
        $participant = $request->participant;
        $type_of_message = $request->message_type;
        $selected_county_id = $request->select_county;
        $subcounty_checked =$request->check;

        if($type_of_message == 7)
        {
          
             if ($users == 3 )
           {     
                       $partners = DB::table('role_user')
                                 ->leftJoin('users','role_user.user_id', '=', 'users.id')
                                 ->select('users.phone')
                                 ->where('role_user.role_id', '3')->get();

                                 $api = DB::table('bulk_sms_settings')->first();
                                $username   = $api->username;
                                $apikey     = $api->api_key;
                 foreach ($partners as $value) {
                        
                        try
                        {                           
                        
                          /*$sms->sendMessage($value->phone, $message, $from);*/
                       }
                        
                        catch ( AfricasTalkingGatewayException $e )
                        {
                        echo "Encountered an error while sending: ".$e->getMessage();
                        }
                      }
          }
          else if ($users ==2 &&  $participant== 1 )
          {
             $participants = DB::table('role_user')
                                 ->leftJoin('users','role_user.user_id', '=', 'users.id')
                                 ->select('users.phone','users.sms_code')
                                 ->where('role_user.role_id', '2')->get();

                                 $api = DB::table('bulk_sms_settings')->first();
                                $username   = $api->username;
                                $apikey     = $api->api_key;
                 foreach ($participants as $value) {
                        
                        try
                        {                           
                            $message_to_send = $message . $value->sms_code;
                          /*$sms->sendMessage($value->phone, $message_to_send, $from);*/
                       }
                        
                        catch ( AfricasTalkingGatewayException $e )
                        {
                        echo "Encountered an error while sending: ".$e->getMessage();
                        }
                      }
          }
           else if ($users ==2 &&  $participant== 7 )
           {
            
             $participants = DB::table('role_user')
                                  ->leftjoin('users','role_user.user_id', '=', 'users.id')
                                  ->select ('users.phone','sms_code')
                                  
                                  ->where('role_user.tier', $selected_county_id)->get();
             
                foreach ($participants as $value) {                    

                        try
                        {                           
                            $message_to_send = $message . $value->sms_code; 

                          /*$sms->sendMessage($value->phone, $message_to_send, $from);*/
                       }
                        
                        catch ( AfricasTalkingGatewayException $e )
                        {
                        echo "Encountered an error while sending: ".$e->getMessage();
                        }
                      }                   
           
             /*$sms->sendMessage($value->phone, $message_to_send, $from);*/
            
           }
           else if($users == 2 && $participant == 7 && $subcounty_checked == 1)
           {
           
            $participants = SubCounty::find($request->sub_id)->pluck('name');
            dd($participants);
            

           }

        }

        /* foreach($request->part as $key => $value)
        {
            $phone = User::where('id', $value)->pluck('phone');
        }*/
       
        
                                                                                                                                                                                                     
    }
}
