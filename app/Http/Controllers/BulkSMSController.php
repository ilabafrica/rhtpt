<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Round;
use App\Notification;
use App\County;
use App\SubCounty;
use App\Facility;
use App\Tier;
use App\User;
use App\Role;

use App\Libraries\AfricasTalkingGateway as Bulk;
use Config;
use Input;
use Jenssegers\Date\Date as Carbon;
use Session;
use DB;
use Auth;

class BulkSMSController extends Controller
{

    public function manageBroadcast()
    {
        return view('broadcast.index');
    }

    public function manageSettings()
    {
        return view('bulk.index');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $bulks = DB::table('bulk')->latest()->paginate(5);

        foreach($bulks as $bulk)
        {
            $bulk->rnd = Round::find($bulk->round_id)->name;
            $note = Notification::find($bulk->notification_id);
            $bulk->ntfctn = $note->notification($note->template);
        }

        $response = [
            'pagination' => [
                'total' => $bulks->total(),
                'per_page' => $bulks->perPage(),
                'current_page' => $bulks->currentPage(),
                'last_page' => $bulks->lastPage(),
                'from' => $bulks->firstItem(),
                'to' => $bulks->lastItem()
            ],
            'data' => $bulks
        ];

        return response()->json($response);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'round_id' => 'required',
            'notification_id' => 'required',
            'text' => 'required',
        ]);
        $message = $request->text;
        $round_id = $request->round_id;
        $notification_id = $request->notification_id;
        $user_id = Auth::user()->id;
        $created = Carbon::today()->toDateTimeString();
        $updated = Carbon::today()->toDateTimeString();
        //  Time
        $now = Carbon::now('Africa/Nairobi');
        $bulk = DB::table('bulk')->insert(['notification_id' => $notification_id, 'round_id' => $round_id, 'text' => $message, 'user_id' => $user_id, 'date_sent' => $now, 'created_at' => $created, 'updated_at' => $updated]);
        //  Prepare to send SMS
        // Retrieve login credentials
        $api = DB::table('bulk_sms_settings')->first();
        $username   = $api->username;
        $apikey     = $api->api_key;
        //  TO DO: Use query to retrieve -- number to send messages
        # Prepare to fetch list of phone numbers from the selected counties.
        $recipients = NULL;
        if($request->county)
        {
            $counties = [];
            foreach(Input::get('county') as $key => $value)
            {
                array_push($counties, $value);
            }
            $pRole = Role::idByName('Participant');
            $subCounties = SubCounty::whereIn('county_id', $counties)->pluck('id');
            $facilities = Facility::whereIn('sub_county_id', $subCounties)->pluck('id');
            $tiers = Tier::where('role_id', $pRole)->whereIn('tier', $facilities)->pluck('user_id');
            $phone_numbers = User::whereIn('id', $tiers)->whereNotNull('phone')->pluck('phone')->toArray();
            $recipients = implode(",", $phone_numbers);
        }
        else if($request->usrs)
        {
            $id = $request->usrs;
            $phone_numbers = User::find($id)->phone;
            $recipients = $phone_numbers;
        }
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
              if(env('ALLOW_SENDING_SMS', true)){
                $results = $sms->sendMessage($recipients, $message, $from);
                foreach($results as $result)
                {
                    // status is either "Success" or "error message" and save.
                    $number = $result->number;
                    //  Save the results
                    DB::table('broadcast')->insert(['number' => $number, 'bulk_id' => $bulk->id]);
                }
              }
            }
            catch ( AfricasTalkingGatewayException $e )
            {
            echo "Encountered an error while sending: ".$e->getMessage();
            }
        }

        return response()->json('Sent');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index2()
    {
      //  Prepare to send SMS
      // Retrieve login credentials
      $username   = Config::get('username');
      $apikey     = Config::get('api-key');
      //  TO DO: Use query to retrieve -- number to send messages
      $recipients = "+254711XXXYYY,+254733YYYZZZ";
      // Specify your AfricasTalking shortCode or sender id
      $from = "shortCode or senderId";
      //  TO DO: Use query to retrieve -- Message entered in textarea
      $message    = "";
      // Create a new instance of Bulk SMS gateway.
      $sms    = new Bulk($username, $apikey);
      // use try-catch to filter any errors.
      try
      {
        // Send messages
        if(env('ALLOW_SENDING_SMS', true)){
          $results = $sms->sendMessage($recipients, $message);
          foreach($results as $result) {
            // status is either "Success" or "error message" and save.
            echo " Number: " .$result->number;
            echo " Status: " .$result->status;
            echo " MessageId: " .$result->messageId;
            echo " Cost: "   .$result->cost."\n";
          }
        }
      }
      catch ( AfricasTalkingGatewayException $e )
      {
        echo "Encountered an error while sending: ".$e->getMessage();
      }
    }
    /**
     * Show the form for updating api-key.
     *
     */
    public function key()
    {
        $api = DB::table('bulk_sms_settings')->latest()->paginate(5);
        $response = [
            'pagination' => [
                'total' => $api->total(),
                'per_page' => $api->perPage(),
                'current_page' => $api->currentPage(),
                'last_page' => $api->lastPage(),
                'from' => $api->firstItem(),
                'to' => $api->lastItem()
            ],
            'data' => $api
        ];
        return response()->json($response);
    }
    /**
     * Update api-key.
     *
     */
    public function api(Request $request)
    {
        $code = $request->code;
        $username = $request->username;
        $key = $request->api_key;
        $updated = Carbon::today()->toDateTimeString();
        $update = DB::table('bulk_sms_settings')->update(['code' => $code, 'username' => $username, 'api_key' => $key, 'updated_at' => $updated]);
        return response()->json($update);
    }
    /**
     * Show the form for composing message.
     *
     */
    public function compose()
    {
        $rounds = Round::pluck('name', 'id')->toArray();
        $users = User::whereNotIn('id', [1, 2])->whereNotNull('phone')->get();
        return view('sms.compose', compact('rounds', 'users'));
    }
    /**
     * Update api-key.
     *
     */
    public function broadcast(BroadcastRequest $request)
    {
        $message = $request->message;
        $round_id = $request->round;
        $user_id = Auth::user()->id;
        $created = Carbon::today()->toDateTimeString();
        $updated = Carbon::today()->toDateTimeString();
        DB::table('bulk')->insert(['message' => $message, 'round_id' => $round_id, 'user_id' => $user_id, 'created_at' => $created, 'updated_at' => $updated]);
        $msg = DB::table('bulk')->where('message', $message)->where('round_id', $round_id)->first();
        //  Prepare to send SMS
        // Retrieve login credentials
        $api = DB::table('bulk_sms_settings')->first();
        $username   = $api->username;
        $apikey     = $api->api_key;
        //  TO DO: Use query to retrieve -- number to send messages
        $recipients = implode(",", $request->participant);
        // Specified sender-id
        $from = $api->code;
        // Create a new instance of Bulk SMS gateway.
        $sms    = new Bulk($username, $apikey);
        //  Time
        $now = Carbon::now('Africa/Nairobi');
        // use try-catch to filter any errors.
        try
        {
          // Send messages
          if(env('ALLOW_SENDING_SMS', true)){
            $results = $sms->sendMessage($recipients, $message, $from);
            foreach($results as $result)
            {
              // status is either "Success" or "error message" and save.
              $number = $result->number;
              $status = $result->status;
              $msg_id = $result->messageId;
              $cost = $result->cost;
              $created_at = $now;
              $updated_at = $now;
              //  Save the results
              DB::table('broadcast')->insert(['number' => $number, 'bulk_id' => $msg->id, 'msg_id' => $msg_id, 'cost' => $cost, 'date_sent' => $created, 'created_at' => $created_at, 'updated_at' => $updated_at]);
            }
          }
        }
        catch ( AfricasTalkingGatewayException $e )
        {
          echo "Encountered an error while sending: ".$e->getMessage();
        }
        $url = session('SOURCE_URL');
        return redirect()->to($url)->with('message', trans('messages.message-successfully-sent'));
    }
    /**
     * Fetch sent messages - grouped.
     *
     */
    public function bulk()
    {
        $bulk = DB::table('bulk')->get();
        return view('sms.broadcast', compact('bulk'));
    }
    /**
     * Fetch sent messages - per group.
     *
     */
    public function sms($id)
    {
        $bulk = DB::table('bulk')->where('id', $id)->first();
        $round = Round::where('id', $bulk->round_id)->first()->name;
        $messages = DB::table('broadcast')->where('bulk_id', $bulk->id)->get();
        return view('sms.sms', compact('bulk', 'round', 'messages'));
    }
}