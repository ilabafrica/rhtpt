<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Notifications\Notification;
use App\Http\Controllers\Controller;
use App\User;
use App\Role;
use App\Facility;
use App\SubCounty;
use App\County;
use App\Program;
use App\Round;

use DB;
use Hash;
use Auth;
use Mail;
use App\Libraries\AfricasTalkingGateway as Bulk;
//  Carbon - for use with dates
use Jenssegers\Date\Date as Carbon;

class UserController extends Controller
{

    public function manageUser()
    {
        return view('user.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $error = ['error' => 'No results found, please try with different keywords.'];
        $users = User::latest()->withTrashed()->paginate(5);
        if(Auth::user()->isCountyCoordinator())
        {
            $users = County::find(Auth::user()->ru()->tier)->users()->latest()->withTrashed()->paginate(5);
        }
        else if(Auth::user()->isSubCountyCoordinator())
        {
           $users = SubCounty::find(Auth::user()->ru()->tier)->users()->latest()->withTrashed()->paginate(5);
        }
        else if(Auth::user()->isFacilityInCharge())
        {
           $users = Facility::find(Auth::user()->ru()->tier)->users()->latest()->withTrashed()->paginate(5);
        }
        if($request->has('q')) 
        {
            $search = $request->get('q');
            $users = User::where('name', 'LIKE', "%{$search}%")->latest()->withTrashed()->paginate(5);
            if(Auth::user()->isCountyCoordinator())
            {
                $users = County::find(Auth::user()->ru()->tier)->users()->where('users.name', 'LIKE', "%{$search}%")->latest()->withTrashed()->paginate(5);
            }
            else if(Auth::user()->isSubCountyCoordinator())
            {
                $users = SubCounty::find(Auth::user()->ru()->tier)->users()->where('users.name', 'LIKE', "%{$search}%")->latest()->withTrashed()->paginate(5);
            }
            else if(Auth::user()->isFacilityInCharge())
            {
               $users = Facility::find(Auth::user()->ru()->tier)->users()->where('users.name', 'LIKE', "%{$search}%")->latest()->withTrashed()->paginate(5);
            }
        }
        if($request->has('filter')) 
        {
            $search = $request->get('q');
            $users = User::whereNotNull('sms_code')->latest()->withTrashed()->paginate(5);
            if(Auth::user()->isCountyCoordinator())
            {
                $users = County::find(Auth::user()->ru()->tier)->users()->whereNotNull('sms_code')->latest()->withTrashed()->paginate(5);
            }
            else if(Auth::user()->isSubCountyCoordinator())
            {
                $users = SubCounty::find(Auth::user()->ru()->tier)->users()->whereNotNull('sms_code')->latest()->withTrashed()->paginate(5);
            }
            else if(Auth::user()->isFacilityInCharge())
            {
               $users = Facility::find(Auth::user()->ru()->tier)->users()->whereNotNull('sms_code')->latest()->withTrashed()->paginate(5);
            }
        }
        foreach($users as $user)
        {
            if((!empty($user->uid) || $user->ru()->tier))
            {
                $facility = Facility::find($user->ru()->tier);
                $user->facility = $user->ru()->tier;
                $user->program = $user->ru()->program_id;
                $user->sub_county = $facility->subCounty->id;
                $user->county = $facility->subCounty->county->id;

                $user->fac = $facility->name;
                $user->prog = Program::find($user->ru()->program_id)->name;
                $user->sub = $facility->subCounty->name;
                $user->kaunti = $facility->subCounty->county->name;
            }
            else
            {
                $user->facility = '';
                $user->program = '';
            }
            $user->ru()?$user->role = Role::find($user->ru()->role_id)->name:$user->role = '';
            $user->ru()?$user->rl = $user->ru()->role_id:$user->role = '';
        }
        $response = [
            'pagination' => [
                'total' => $users->total(),
                'per_page' => $users->perPage(),
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'from' => $users->firstItem(),
                'to' => $users->lastItem()
            ],
            'data' => $users
        ];

        return $users->count() > 0 ? response()->json($response) : $error;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request);
        $this->validate($request, [
            'name' => 'required',
            'gender' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'address' => 'required',
            'username' => 'required'
        ]);
        $request->merge(['password' => Hash::make(User::DEFAULT_PASSWORD)]);
        $create = User::create($request->all());
        if($request->role)
        {
            $role = $request->role;
            $tier = NULL;
            $program_id = NULL;
            if($role == Role::idByName("Partner"))
            {
                $tier = implode(", ", $request->jimbo);
            }
            else if($role == Role::idByName("County Coordinator"))
            {
                $tier = $request->county_id;
            }
            else if($role == Role::idByName("Sub-County Coordinator"))
            {
                $tier = $request->sub_id;
            }
            else if($role == Role::idByName("Participant"))
            {
                $tier = $request->facility_id;
                $program_id = $request->program_id;
            }
            else if($role == Role::idByName("Facility Incharge"))
            {
                $tier = $request->facility_id;
            }
            $ru = DB::table('role_user')->insert(["user_id" => $create->id, "role_id" => $role, "tier" => $tier, "program_id" => $program_id]);
        }
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
        $this->validate($request, [
            'name' => 'required',
            'gender' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'address' => 'required',
            'username' => 'required'
        ]);

        $edit = User::find($id)->update($request->all());
        if($request->role)
        {
            $role = $request->role;
            $tier = NULL;
            $program_id = NULL;
            if($role == Role::idByName("Partner"))
            {
                $tier = implode(", ", $request->jimbo);
            }
            else if($role == Role::idByName("County Coordinator"))
            {
                $tier = $request->county_id;
            }
            else if($role == Role::idByName("Sub-County Coordinator"))
            {
                $tier = $request->sub_id;
            }
            else if($role == Role::idByName("Participant"))
            {
                $tier = $request->facility_id;
                $program_id = $request->program_id;
            }
            else if($role == Role::idByName("Facility Incharge"))
            {
                $tier = $request->facility_id;
            }
            $ru = DB::table('role_user')->insert(["user_id" => $id, "role_id" => $role, "tier" => $tier, "program_id" => $program_id]);
        }
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
        User::find($id)->delete();
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
        $user = User::withTrashed()->find($id)->restore();
        return response()->json(['done']);
    }
    /**
     * Function to return list of tester-ranges.
     *
     */
    public function ranges()
    {
        $ranges = [
            User::ZERO_TO_TWO => '0 - 2',
            User::THREE_TO_FIVE => '3 - 5',
            User::SIX_TO_EIGHT => '6 - 8',
            User::NINE => '9'
        ];
        $categories = [];
        foreach($ranges as $key => $value)
        {
            $categories[] = ['id' => $key, 'value' => $value];
        }
        return $categories;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function participant(Request $request)
    {
        $error = ['error' => 'No results found, please try with different keywords.'];
        $participant = Role::idByName('Participant');
        $users = [];
        if($request->has('q')) 
        {
            $search = $request->get('q');
            $users = User::join('role_user', 'users.id', '=', 'role_user.user_id')->where('role_id', $participant)
                        ->where('name', 'LIKE', "%{$search}%")->orWhere('uid', 'LIKE', "%{$search}%")
                        ->orWhere('phone', 'LIKE', "%{$search}%")->latest()->paginate(5);
        }
        $response = [
            'pagination' => [
                'total' => $users->total(),
                'per_page' => $users->perPage(),
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'from' => $users->firstItem(),
                'to' => $users->lastItem()
            ],
            'data' => $users
        ];

        return $users->count() > 0 ? response()->json($response) : $error;
    }
    /**
     * Transfer the specified participant.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function transfer(Request $request, $id)
    {
        $this->validate($request, [
            'facility_id' => 'required',
            'program_id' => 'required',
        ]);
        $tier = Tier::where('user_id', $id)->first();
        $prog = NULL;
        $fac = NULL;
        if($request->facility_id)
        {
            $fac = $request->facility_id;
            $tier->tier = $fac;
        }
        if($request->program_id)
        {
            $prog = $request->program_id;
            $tier->program_id = $prog;
        }
        $response = $tier->save();

        return response()->json($response);
    }
    /**
     * Function for enrolling users to a round of testing
     *
     * @return \Illuminate\Http\Response
     */
    public function forEnrol(Request $request)
    {
        $error = ['error' => 'No results found, please try with different keywords.'];
        $role_id = Role::idByName('Participant');
        $ids = DB::table('role_user')->where('role_id', $role_id)->pluck('user_id');
        $usrs = User::whereIn('id', $ids)->whereNotNull('uid')->latest()->paginate(5);
        if($request->has('q')) 
        {
            $search = $request->get('q');
            $usrs = User::whereIn('id', $ids)->where('name', 'LIKE', "%{$search}%")->whereNotNull('uid')->latest()->paginate(5);
        }
        if(count($usrs)>0)
        {
            foreach($usrs as $user)
            {
                //dd($user->ru());
                $user->facility = Facility::find($user->ru()->tier)->name;
                $user->program = Program::find($user->ru()->program_id)->name;
            }
        }
        $response = [
            'pagination' => [
                'total' => $usrs->total(),
                'per_page' => $usrs->perPage(),
                'current_page' => $usrs->currentPage(),
                'last_page' => $usrs->lastPage(),
                'from' => $usrs->firstItem(),
                'to' => $usrs->lastItem()
            ],
            'data' => $usrs
        ];

        return !empty($usrs) ? response()->json($response) : $error;
    }
    /**
     * Get enrolled user(s).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function enrolled($id)
    {
        $error = ['error' => 'No results found, please try with different keywords.'];
        $ids = Round::find($id)->enrolments->pluck('user_id')->toArray();
        $usrs = User::whereIn('id', $ids)->latest()->paginate(5);
        //dd($usrs);
        if(count($usrs)>0)
        {
            foreach($usrs as $enrol)
            {
                $facility = Facility::find($enrol->ru()->tier);
                $enrol->facility = $facility->name;
                $enrol->mfl = $facility->code;
                $enrol->program = Program::find($enrol->ru()->program_id)->name;
            }
        }
        $response = [
            'pagination' => [
                'total' => $usrs->total(),
                'per_page' => $usrs->perPage(),
                'current_page' => $usrs->currentPage(),
                'last_page' => $usrs->lastPage(),
                'from' => $usrs->firstItem(),
                'to' => $usrs->lastItem()
            ],
            'data' => $usrs
        ];
        return !empty($usrs) ? response()->json($response) : $error;
    }
    /**
     * Function to return list of sexes.
     *
     */
    public function sex()
    {
        $sexes = [
            User::MALE => 'Male',
            User::FEMALE => 'Female'
        ];
        $categories = [];
        foreach($sexes as $key => $value)
        {
            $categories[] = ['title' => $value, 'name' => $key];
        }
        return $categories;
    }
    /**
     * Function to return list of designations.
     *
     */
    public function designations()
    {
        $designations = [
            0 => '',
            User::NURSE => 'Nurse',
            User::LABTECH => 'Lab Tech.',
            User::COUNSELLOR => 'Counsellor',
            User::RCO => 'RCO',
        ];
        $categories = [];
        foreach($designations as $key => $value)
        {
            $categories[] = ['title' => $value, 'name' => $key];
        }
        return $categories;
    }

    /**
     * Function to register new participants
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $now = Carbon::now('Africa/Nairobi');
        //  Prepare to save user details
        //  Check if user exists
        $userId = User::idByName($request->name);
        if(!$userId)
            $userId = User::idByEmail($request->email);
        if(!$userId)
        {
            $user = new User;
            $user->name = $request->name;
            $user->gender = $request->gender;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->address = $request->address;
            $user->designation = $request->designation;
            $user->username = $request->name;
            $user->deleted_at = $now;
            $user->save();
            $userId = $user->id;
        }
        //  Prepare to save facility details
        $facilityId = Facility::idByCode($request->mfl_code);
        if(!$facilityId)
            $facilityId = Facility::idByName($request->facility);
        if($facilityId)
            $facility = Facility::find($facilityId);
        else
            $facility = new Facility;
        $facility = new Facility;
        $facility->code = $request->mfl_code;
        $facility->name = $request->facility;
        $facility->in_charge = $request->in_charge;
        $facility->in_charge_phone = $request->in_charge_phone;
        $facility->in_charge_email = $request->in_charge_email;
        //  Get sub-county
        $sub_county = SubCounty::idByName($request->sub_county);
        if(!$sub_county)
        {
            $sb = new SubCounty;
            $sb->name = $request->sub_county;
            $sb->county_id = $request->county;
            $sb->save();
            $sub_county = $sb->id;
        }
        $facility->sub_county_id = $sub_county;
        $facility->save();
        $facilityId = $facility->id;
        //  Prepare to save role-user details
        $roleId = Role::idByName('Participant');
        DB::table('role_user')->insert(['user_id' => $userId, 'role_id' => $roleId, 'tier' => $facilityId, 'program_id' => $request->program]);
        /*
        *  Do SMS Verification for phone number
        */
        //  Bulk-sms settings
        $api = DB::table('bulk_sms_settings')->first();
        $username   = $api->username;
        $apikey     = $api->api_key;
        //  Remove beginning 0 and append +254
        $phone = ltrim($user->phone, '0');
        $recipient = "+254".$phone;
        // Generate code and store it in the database then send to participant
        $token = mt_rand(100000, 999999);
        $user->sms_code = $token;
        $user->save();
        $message    = "Your Verification Code is: ".$token;
        // Create a new instance of our awesome gateway class
        $gateway    = new Bulk($username, $apikey);
        try 
        { 
            // Specified sender-id
            $from = $api->code;
            // Send message
            // $result = $gateway->sendMessage($recipient, $message);
        }
        catch ( AfricasTalkingGatewayException $e )
        {
            echo "Encountered an error while sending: ".$e->getMessage();
        }
        //  Do Email verification for email address
        $user->email_verification_code = Str::random(60);
        $user->save();
        $user->notify(new SendVerificationCode($user));
        /*$usr = $user->toArray();

        Mail::send('auth.verification', $usr, function($message) use ($usr) {
            $message->to($usr['email']);
            $message->subject('National HIV PT - Email Verification Code');
        });*/

        return response()->json(['phone' => $user->phone]);        
    }
    /**
     * Import the data in the worksheet
     *
     */
    public function batchRegistration(Request $request)
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
        $folder = '/batch/'.$id.'/registration/'.$county.'/';
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
        Excel::load('/public/batch/'.$id.'/registration/'.$county.'/'.$fileName, function($reader) use($fileName){
            // Getting all results
            $reader->each(function($sheet){
                $sheetTitle = $sheet->getTitle();                
                $counter = count($sheet);
                for($i=0; $i<$counter; $i++)
                {
                    //  Facility details
                    $county = NULL;
                    $subCounty = NULL;
                    $facility = NULL;
                    $mfl = NULL;
                    //  Participant details
                    $tname = NULL;
                    $tgender = NULL;
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
                        if(strcmp($key, "County") === 0)
                            $county = $value;
                        if(strcmp($key, "Sub County") === 0)
                            $subCounty = $value;
                        if(strcmp($key, "Facility") === 0)
                            $facility = $value;
                        if(strcmp($key, "MFL Code") === 0)
                            $mfl = $value;
                        if(strcmp($key, "Tester Name") === 0)
                            $tname = $value;
                        if(strcmp($key, "Gender") === 0)
                            $tgender = $value;
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
                    //  Check if Facility exits. If not create one.
                    $fclty = Facility::find(Facility::idByCode((int)$mfl));
                    if(!$fclty)
                    {
                        $fclty = new Facility;
                        $fclty->name = $facility;
                    }
                    $fclty->sub_county_id = SubCounty::idByName($subCounty);
                    if(!$fclty->sub_county_id)
                    {
                        $sb = new SubCounty;
                        $sb->name = $subCounty;
                        $sb->county_id = County::idByName($county);
                        $sb->save();
                        $fclty->sub_county_id = $sb->id;
                    }
                    $fclty->in_charge = $incharge;
                    $fclty->in_charge_phone = $iphone;
                    $fclty->in_charge_email = $iemail;
                    $fclty->save();
                    //  Save new user
                    $user = new User;
                    $user->name = $tname;
                    if(strcmp($tgender, "Male") === 0)
                        $user->gender = User::MALE;
                    else
                        $user->gender = User::FEMALE;
                    $user->email = $temail;
                    $user->phone = $tphone;
                    $user->address = $taddress;
                    $user->designation = $tdes;
                    $user->save();
                    //  Insert into role-user
                    $userId = $user->id;
                    $roleId = Role::idByName('Participant');
                    $programId = Program::idByName($tprog);
                    DB::table('role_user')->insert(['user_id' => $userId, 'role_id' => $roleId, 'tier' => $fclty->id, 'program_id' => $programId]);
                    //  Send SMS verification code
                    $api = DB::table('bulk_sms_settings')->first();
                    $username   = $api->username;
                    $apikey     = $api->api_key;
                    //  Remove beginning 0 and append +254
                    $phone = ltrim($user->phone, '0');
                    $recipient = "+254".$phone;
                    // Generate code and store it in the database then send to participant
                    $token = mt_rand(100000, 999999);
                    $user->sms_code = $token;
                    $user->save();
                    $message    = "Your Verification Code is: ".$token;
                    // Create a new instance of our awesome gateway class
                    $gateway    = new Bulk($username, $apikey);
                    try 
                    { 
                        // Specified sender-id
                        $from = $api->code;
                        // Send message
                        // $result = $gateway->sendMessage($recipient, $message);
                    }
                    catch ( AfricasTalkingGatewayException $e )
                    {
                        echo "Encountered an error while sending: ".$e->getMessage();
                    }
                    //  Send email verification code
                }
            });
        });
    }
    /**
     * Check for user phone verification code
     *
     * @param  array  $data
     * @return User
     */
    public function phoneVerification(Request $request)
    {
        $token = $request->code;
        // dd($token);
        $check = User::where("sms_code", $token)->withTrashed()->first();
        
        if(!is_null($check)){
            $user = User::withTrashed()->find($check->id);

            if($user->phone_verified == 1){
                return response()->json(["info" => "Your phone number is already verified."]);
            }

            $user->phone_verified = 1;
            $user->save();

            return response()->json(["success" => "Phone number successfully verified. Your ID will be sent to you shortly."]);
        }
        return response()->json(["warning" => "Your token is invalid."]);
    }
    /**
     * Check for user Activation Code
     *
     * @param  array  $data
     * @return User
     */
    public function emailVerification($token)
    {
        $check = User::where('email_verification_code', $token)->first();

        if(!is_null($check)){
            $user = User::find($check->id);

            if($user->email_verified == 1){
                return redirect()->to('login')
                    ->with('success', "Your email is already verified.");                
            }

            $user->update(['email_verified' => 1]);

            return redirect()->to('login')
                ->with('success', "Email successfully verified.");
        }
        return redirect()->to('login')
                ->with('warning', "Your token is invalid.");
    }
}