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

use App\Libraries\AfricasTalkingGateway as Bulk;

use Auth;
use DB;
use Jenssegers\Date\Date as Carbon;
use Excel;
use App;
use File;

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
            'data' => $rounds
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
        $request->request->add(['user_id' => Auth::user()->id]);

        $create = Round::create($request->all());

        return response()->json($create);
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

        $edit = Round::find($id)->update($request->all());

        return response()->json($edit);
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
        $rounds = Round::lists('description', 'id');
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
                DB::table('broadcast')->insert(['number' => $number, 'bulk_id' => $bulk->id]);
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
     * Function to enrol participants using excel sheet uploaded
     *
     */
    public function batchEnrolment(Request $request)
    {
        $id = Round::find($request->id)->name;
        $exploded = explode(',', $request->excel);
        $decoded = base64_decode($exploded[1]);
        if(str_contains($exploded[0], 'sheet'))
            $extension = 'xlsx';
        else
            $extension = 'xls';
        $fileName = uniqid().'.'.$extension;
        $county = County::find(1)->name;    // Remember to change this
        $folder = '/batch/'.$id.'/enrolment/'.$county.'/';
        $path = File::makeDirectory(public_path().$folder, 0777, true);
        file_put_contents(public_path().$folder.$fileName, $decoded);
        $ext = 'csv';
        $fName = uniqid().'.'.$ext;
        file_put_contents(public_path().$folder.$fName, $decoded);
        // dd();
        //  Handle the import
        //  Get the results
        //  Import a user provided file
        //  Convert file to csv
        Excel::load('/public/batch/'.$id.'/enrolment/'.$county.'/'.$fileName, function($reader) use($fileName){
            // Getting all results
            $reader->each(function($sheet){
                $sheetTitle = $sheet->getTitle();                
                $counter = count($sheet);
                
                for($i=0; $i<$counter; $i++)
                {
                    $mfl = NULL;
                    $uid = NULL;
                    $tphone = NULL;
                    $temail = NULL;
                    $taddress = NULL;
                    $tdes = NULL;
                    $tprog = NULL;
                    $incharge = NULL;
                    $iphone = NULL;
                    $iemail = NULL;
                    foreach($sheet[$i] as $key => $value)
                    {
                        if(strcmp($key, "MFL Code") === 0)
                            $mfl = $value;
                        if(strcmp($key, "Tester Unique ID") === 0)
                            $uid = $value;
                        if(strcmp($key, "Tester Mobile Number") === 0)
                            $tphone = $value;
                        if(strcmp($key, "Tester Email") === 0)
                            $temail = $value;
                        if(strcmp($key, "Tester Address") === 0)
                            $taddress = $value;
                        if(strcmp($key, "Designation") === 0)
                            $tdes = $value;
                        if(strcmp($key, "Program") === 0)
                            $tprog = $value;
                        if(strcmp($key, "In Charge") === 0)
                            $incharge = $value;
                        if(strcmp($key, "In Charge Email") === 0)
                            $iemail = $value;
                        if(strcmp($key, "In Charge Phone") === 0)
                            $iphone = $value;
                    }
                    //  Update user details where necessary
                    $user = User::find(User::idByUid($u));
                    $user->phone = $tphone;
                    $user->email = $temail;
                    $user->address = $taddress;
                    $user->save();                    
                    //  Update facility details where applicable
                    $facility = Facility::find(Facility::idByCode((int)$mfl));
                    $facility->in_charge = $incharge;
                    $facility->in_charge_email = $iemail;
                    $facility->in_charge_phone = $iphone;
                    $facility->save();
                    //  Update role-user
                    $
                    //  Enrol the participant to the pt round
                    $userId = $user->id;
                    $roundId = $id;
                    $enrol = Enrol::where('round_id', $roundId)->where('user_id', $userId)->get();
                    if(count($enrol) == 0)
                    {
                        $enrol = new Enrol;
                        $enrol->round_id = $roundId;
                        $enrol->user_id = $userId;
                        $enrol->save();
                    }
                    //  Send SMS notification    
                    $phone = ltrim($user->phone, '0');
                    $recipients = "+254".$phone;          
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
                            DB::table('broadcast')->insert(['number' => $number, 'bulk_id' => $bulk->id]);
                        }
                        }
                        catch ( AfricasTalkingGatewayException $e )
                        {
                        echo "Encountered an error while sending: ".$e->getMessage();
                        }
                    }
                }
            });
        });
    }
}
$excel = App::make('excel');