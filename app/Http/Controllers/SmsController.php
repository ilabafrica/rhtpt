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
         $users = '';

        if ($request->user_type == 0) {
            $users = User::all();
        }
        else if ($request->user_type == 2) {
            
            if ($request->participant == 1) {
                if ($request->facility_id) {

                    $users = Facility::find($request->facility_id)->users()->get();

                }else if ($request->sub_county_id) {

                    $users = SubCounty::find($request->sub_county_id)->users()->get();

                }else if ($request->county_id) {

                    $users = County::find($request->county_id)->users()->get();
                }

            } else if ($request->participant == 2) {
                //convert to array for looping
                $users = User::find($request->participant_id);
              
            }else{            
                $user = new User;
                $users = $user->participants()->get();
            }
        }
        else if ($request->user_type == 3) {
            $user = new User;
           if ($request->partner == 1) {

                $users = $user->partners($request->partner_id)->get();

           } else{            
                $users = $user->partners()->get();
           }
        }
        else if ($request->user_type == 4) {
            $user = new User;
            if ($request->user_group == 1) {

                $users = $user->county_coordinators($request->partner_id)->get();

            } else{            
                $users = $user->county_coordinators()->get();
            }
        }

        else if ($request->user_type == 7) {
            $user = new User;
           if ($request->user_group == 1) {

                $users = $user->sub_county_coordinators($request->sub_county_id)->get();

           } else{            
                $users = $user->sub_county_coordinators()->get();
           }
        } 
        //get message to be sent
        $message = Notification::find($request->message_id)->message;
        $api = DB::table('bulk_sms_settings')->first();
        $from   = $api->username;
        $response =[
            'users' =>$users,
            'message' => $message,
            'from' => $from,
            'to' => 'All Users'
        ];

        return $users->count() > 0 ? response()->json($response) : $error;

        //send SMS
        // $api = DB::table('bulk_sms_settings')->first();
        // $from   = $api->username;
        // $apikey = $api->api_key;
        // foreach ($users as $user) {                
        //     try
        //     {                           
        //         print($user->phone .' '.$message.'<br/>');
        //       /*$sms->sendMessage($user->phone, $message, $from);*/
        //     }                
        //     catch ( AfricasTalkingGatewayException $e )
        //     {
        //         echo "Encountered an error while sending: ".$e->getMessage();
        //     }
        // }               
    }
}
