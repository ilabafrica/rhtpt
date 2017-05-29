<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        foreach($users as $user)
        {
            if(!empty($user->uid) && count($user->tier)>0)
            {
                $user->facility = $user->tier->tier;
                $user->program = $user->tier->program_id;
            }
            $user->facility = '';
            $user->program = '';
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
        $usrs = Role::find(Role::idByName('Participant'))->users()->latest()->paginate(5);
        if($request->has('q')) 
        {
            $search = $request->get('q');
            $usrs = Role::find(Role::idByName('Participant'))->users()->where('name', 'LIKE', "%{$search}%")->latest()->paginate(5);
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
        $ids = Round::find($id)->enrolments->lists('user_id')->toArray();
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
        $this->validate($request, [
            'name' => 'required',
            'gender' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'designation' => 'required',
            'program' => 'required',
            'county' => 'required',
            'sub_county' => 'required',
            'mfl_code' => 'required',
            'facility' => 'required',
            'in_charge' => 'required',
            'in_charge_email' => 'required',
            'in_charge_phone' => 'required'
        ]);
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
        return response()->json('Registered.');
    }
    /**
     * Import the data in the worksheet
     *
     */
    public function batchRegistration(Request $request)
    {
        //  Handle the import
        //  Get the data
        //  Import a user provided file
        $file = $request->worksheet;
        $ext = $file->getClientOriginalExtension();
        $excel = uniqid().'.'.$ext;
        $filename = $file->move('uploads/', $excel);
        dd();
        /*//  Convert file to csv
        Excel::load('/public/uploads/'.$excel, function($reader) use(){
            $worksheet = $reader->get()[0];
            //  Initialize variables
            $labName = $reader->first()[0]->value;
            $lab_id = Lab::labIdName($labName);
            $review = Review::where('lab_id', $lab_id)->where('audit_type_id', $audit_type_id)->first();
            //  Check if review exists
            if(!$review){
                //  Create new review
                $review = new Review;
                $review->lab_id = $lab_id;
                $review->audit_type_id = $audit_type_id;
                $review->status = Review::INCOMPLETE;
                $review->user_id = Auth::user()->id;
                $review->update_user_id = Auth::user()->id;
                try{
                    $review->save();
                    //$url = Session::get('SOURCE_URL');
                }
                catch(QueryException $e){
                    Log::error($e);
                }
            }
            //  Get review id
            $review_id = $review->id;
            $reader->each(function($sheet) use($review_id, $laboratory_profile, $staffing_summary, $organizational_structure, $slmta_information, $assessment, $scores, $summary, $action_plan){
                $review = Review::find(1);
                $sheetTitle = $sheet->getTitle();
                if($sheetTitle == Lang::choice('messages.lab-info', 2)){
                    $counter = count($sheet);
                    $head = NULL;
                    $head_personal_telephone = NULL;
                    $head_work_telephone = NULL;
                    //  Check if Lab Info exists for the review
                    $lab_profile = $review->laboratory;
                    if(!count($lab_profile)){
                        $lab_profile = new ReviewLabProfile;
                        $lab_profile->review_id = $review_id;
                        $lab_profile->created_at = date('Y-m-d H:i:s');
                        $lab_profile->save();
                    }
                    for($i=0;$i<$counter;$i++){
                        //  Save Laboratory Profile         
                        if($sheet[$i]->field == Lang::choice('messages.lab-head', 1)){
                            $lab_profile->head = $sheet[$i]->value;
                        }
                        if($sheet[$i]->field == Lang::choice('messages.lab-head-telephone-personal', 1)){
                            $lab_profile->head_personal_telephone = $sheet[$i]->value;
                        }
                        if($sheet[$i]->field == Lang::choice('messages.lab-head-telephone-work', 1)){
                            $lab_profile->head_work_telephone = $sheet[$i]->value;
                        }
                    }
                    $lab_profile->updated_at = date('Y-m-d H:is:s');
                    $lab_profile->save();
                }
                //  Staffing Summary
                else if($sheetTitle == Lang::choice('messages.staffing-summary', 1)){
                    //  Initialize counter
                    $counter = count($staffing_summary);
                    //dd($counter);
                    //  Variables
                    $degree = NULL;
                    $degree_adequate = NULL;
                    $diploma = NULL;
                    $diploma_adequate = NULL;
                    $certificate = NULL;
                    $certificate_adequate = NULL;
                    $microscopist = NULL;
                    $microscopist_adequate = NULL;
                    $data_clerk = NULL;
                    $data_clerk_adequate = NULL;
                    $phlebotomist = NULL;
                    $phlebotomist_adequate = NULL;
                    $cleaner = NULL;
                    $cleaner_adequate = NULL;
                    $cleaner_dedicated = NULL;
                    $cleaner_trained = NULL;
                    $driver = NULL;
                    $driver_adequate = NULL;
                    $driver_dedicated = NULL;
                    $driver_trained = NULL;
                    $other_staff = NULL;
                    $other_staff_adequate = NULL;
                    //  Check lab profile
                    $lab_profile = $review->laboratory;
                    if(!count($lab_profile)){
                        $lab_profile = new ReviewLabProfile;
                        $lab_profile->review_id = $review_id;
                        $lab_profile->created_at = date('Y-m-d H:i:s');
                        $lab_profile->save();
                    }
                    //  Begin saving
                    for($i=0;$i<$counter;$i++){
                        if($staffing_summary[$i]->profession == Lang::choice('messages.degree', 1)){
                            $lab_profile->degree_staff = $staffing_summary[$i]->employees;
                            $lab_profile->degree_staff_adequate = Answer::adequate($staffing_summary[$i]->adequate);
                        }
                        if($staffing_summary[$i]->profession == Lang::choice('messages.diploma', 1)){
                            $lab_profile->diploma_staff = $staffing_summary[$i]->employees;
                            $lab_profile->diploma_staff_adequate = Answer::adequate($staffing_summary[$i]->adequate);
                        }
                        if($staffing_summary[$i]->profession == Lang::choice('messages.certificate', 1)){
                            $lab_profile->certificate_staff = $staffing_summary[$i]->employees;
                            $lab_profile->certificate_staff_adequate = Answer::adequate($staffing_summary[$i]->adequate);
                        }
                        if($staffing_summary[$i]->profession == Lang::choice('messages.microscopist', 1)){
                            $lab_profile->microscopist = $staffing_summary[$i]->employees;
                            $lab_profile->microscopist_adequate = Answer::adequate($staffing_summary[$i]->adequate);
                        }
                        if($staffing_summary[$i]->profession == Lang::choice('messages.data-clerk', 1)){
                            $lab_profile->data_clerk = $staffing_summary[$i]->employees;
                            $lab_profile->data_clerk_adequate = Answer::adequate($staffing_summary[$i]->adequate);
                        }
                        if($staffing_summary[$i]->profession == Lang::choice('messages.phlebotomist', 1)){
                            $lab_profile->phlebotomist = $staffing_summary[$i]->employees;
                            $lab_profile->phlebotomist_adequate = Answer::adequate($staffing_summary[$i]->adequate);
                        }
                        if($staffing_summary[$i]->profession == Lang::choice('messages.cleaner', 1)){
                            $lab_profile->cleaner = $staffing_summary[$i]->employees;
                            $lab_profile->cleaner_adequate = Answer::adequate($staffing_summary[$i]->adequate);
                        }
                        if($staffing_summary[$i]->profession == Lang::choice('messages.cleaner-dedicated', 1)){
                            $lab_profile->cleaner_dedicated = Answer::adequate($staffing_summary[$i]->employees);
                        }
                        if($staffing_summary[$i]->profession == Lang::choice('messages.cleaner-trained', 1)){
                            $lab_profile->cleaner_trained = Answer::adequate($staffing_summary[$i]->employees);
                        }
                        if($staffing_summary[$i]->profession == Lang::choice('messages.driver', 1)){
                            $lab_profile->driver = $staffing_summary[$i]->employees;
                            $lab_profile->driver_adequate = Answer::adequate($staffing_summary[$i]->adequate);
                        }
                        if($staffing_summary[$i]->profession == Lang::choice('messages.driver-dedicated', 1)){
                            $lab_profile->driver_dedicated = Answer::adequate($staffing_summary[$i]->employees);
                        }
                        if($staffing_summary[$i]->profession == Lang::choice('messages.driver-trained', 1)){
                            $lab_profile->driver_trained = Answer::adequate($staffing_summary[$i]->employees);
                        }
                        if($staffing_summary[$i]->profession == Lang::choice('messages.other', 1)){
                            $lab_profile->other_staff = $staffing_summary[$i]->employees;
                            $lab_profile->other_staff_adequate = Answer::adequate($staffing_summary[$i]->adequate);
                        }
                    }
                    $lab_profile->updated_at = date('Y-m-d H:i:s');
                    $lab_profile->save();
                }
                //  Organizational structure
                else if($sheetTitle == Lang::choice('messages.org-structure', 2)){
                    $counter = count($organizational_structure);
                    //  Declare variables
                    $sufficient_space = NULL;
                    $equipment = NULL;
                    $supplies = NULL;
                    $personnel = NULL;
                    $infrastructure = NULL;
                    $other = NULL;
                    $other_description = NULL;
                    //  Check lab profile
                    $lab_profile = $review->laboratory;
                    if(!count($lab_profile)){
                        $lab_profile = new ReviewLabProfile;
                        $lab_profile->review_id = $review_id;
                        $lab_profile->created_at = date('Y-m-d H:i:s');
                        $lab_profile->save();
                    }
                    //  Begin saving
                    for($i=0;$i<$counter;$i++){
                        if($organizational_structure[$i]->field == Lang::choice('messages.sufficient-space', 1)){
                            $lab_profile->sufficient_space = Answer::adequate($organizational_structure[$i]->value);
                        }
                        if($organizational_structure[$i]->field == Lang::choice('messages.equipment', 1)){
                            $lab_profile->equipment = Answer::adequate($organizational_structure[$i]->value);
                        }
                        if($organizational_structure[$i]->field == Lang::choice('messages.supplies', 1)){
                            $lab_profile->supplies = Answer::adequate($organizational_structure[$i]->value);
                        }
                        if($organizational_structure[$i]->field == Lang::choice('messages.personnel', 1)){
                            $lab_profile->personnel = Answer::adequate($organizational_structure[$i]->value);
                        }
                        if($organizational_structure[$i]->field == Lang::choice('messages.infrastructure', 1)){
                            $lab_profile->infrastructure = Answer::adequate($organizational_structure[$i]->value);
                        }
                        if(strpos($organizational_structure[$i]->field, Lang::choice('messages.other-specify', 1)) !== FALSE){
                            $lab_profile->other = Answer::adequate($organizational_structure[$i]->value);
                            if(($pos = strpos($organizational_structure[$i]->field, ':')) !== FALSE)
                                $other_description = substr($organizational_structure[$i]->field, $pos+2);
                            $lab_profile->other_description = trim($other_description);
                        }
                    }
                    $lab_profile->updated_at = date('Y-m-d H:i:s');
                    $lab_profile->save();
                }
                //  SLMTA Information
                else if($sheetTitle == Lang::choice('messages.slmta-info', 2)){
                    $counter = count($slmta_information);
                    //  Variables declaration
                    $official_slmta = NULL;
                    $assessment_id = NULL;
                    $tests_before_slmta = NULL;
                    $tests_this_year = NULL;
                    $cohort_id = NULL;
                    $baseline_audit_date = NULL;
                    $slmta_workshop_date = NULL;
                    $exit_audit_date = NULL;
                    $baseline_score = NULL;
                    $baseline_stars_obtained = NULL;
                    $exit_score = NULL;
                    $exit_stars_obtained = NULL;
                    $last_audit_date = NULL;
                    $last_audit_score = NULL;
                    $prior_audit_status = NULL;
                    $audit_start_date = NULL;
                    $audit_end_date = NULL;
                    $array = array();
                    $assessors = array();
                    //  Check SLMTA Info
                    //  Check if SLMTA Info exists for the review
                    $slmta = $review->slmta;
                    if(!count($slmta)>0)
                        $slmta = new ReviewSlmtaInfo;
                    //  Begin saving
                    for($i=0;$i<$counter;$i++){
                        if($slmta_information[$i]->field == Lang::choice('messages.slmta-audit-type', 1)){
                            $slmta->assessment_id = Assessment::idByName($slmta_information[$i]->value);
                        }
                        if($slmta_information[$i]->field == Lang::choice('messages.official-slmta', 1)){
                            $slmta->official_slmta = Answer::adequate($slmta_information[$i]->value);
                        }
                        if($slmta_information[$i]->field == Lang::choice('messages.audit-start-date', 1)){
                            $slmta->audit_start_date = $slmta_information[$i]->value;
                        }
                        if($slmta_information[$i]->field == Lang::choice('messages.audit-end-date', 1)){
                            $slmta->audit_end_date = $slmta_information[$i]->value;
                        }
                        if($slmta_information[$i]->field == Lang::choice('messages.tests-before-slmta', 1)){
                            $slmta->tests_before_slmta = $slmta_information[$i]->value;
                        }
                        if($slmta_information[$i]->field == Lang::choice('messages.tests-this-year', 1)){
                            $slmta->tests_this_year = $slmta_information[$i]->value;
                        }
                        if($slmta_information[$i]->field == Lang::choice('messages.cohort-id', 1)){
                            $slmta->cohort_id = $slmta_information[$i]->value;
                        }
                        if($slmta_information[$i]->field == Lang::choice('messages.baseline-audit-date', 1)){
                            $slmta->baseline_audit_date = $slmta_information[$i]->value;
                        }
                        if($slmta_information[$i]->field == Lang::choice('messages.slmta-workshop-date', 1)){
                            $slmta->slmta_workshop_date = $slmta_information[$i]->value;
                        }
                        if($slmta_information[$i]->field == Lang::choice('messages.exit-audit-date', 1)){
                            $slmta->exit_audit_date = $slmta_information[$i]->value;
                        }
                        if($slmta_information[$i]->field == Lang::choice('messages.baseline-score', 1)){
                            $slmta->baseline_score = $slmta_information[$i]->value;
                        }
                        if($slmta_information[$i]->field == Lang::choice('messages.baseline-stars', 1)){
                            $slmta->baseline_stars = $slmta_information[$i]->value;
                        }
                        if($slmta_information[$i]->field == Lang::choice('messages.exit-score', 1)){
                            $slmta->exit_score = $slmta_information[$i]->value;
                        }
                        if($slmta_information[$i]->field == Lang::choice('messages.exit-stars', 1)){
                            $slmta->exit_stars = $slmta_information[$i]->value;
                        }
                        if($slmta_information[$i]->field == Lang::choice('messages.last-audit-date', 1)){
                            $slmta->last_audit_date = $slmta_information[$i]->value;
                        }
                        if($slmta_information[$i]->field == Lang::choice('messages.last-audit-score', 1)){
                            $slmta->last_audit_score = $slmta_information[$i]->value;
                        }
                        if($slmta_information[$i]->field == Lang::choice('messages.prior-audit-status', 1)){
                            $slmta->prior_audit_status = $slmta_information[$i]->value;
                        }
                        if($slmta_information[$i]->field == Lang::choice('messages.names-affiliations-of-auditors', 1)){
                            foreach(explode(',', $slmta_information[$i]->value) as $assessor){
                                $assessors = array_push($assessors, User::userIdName($assessor));
                            }
                        }
                    }
                    Review::find($review_id)->setAssessors([$assessors]);
                    $slmta->created_at = date('Y-m-d H:i:s');
                    $slmta->updated_at = date('Y-m-d H:i:s');
                    $slmta->save();
                }
                //  Summary of Audit Findings
                else if($sheetTitle == 'Summary of Assessment Findings'){
                    $counter = count($summary);
                    $commendations = NULL;
                    $challenges = NULL;
                    $recommendations = NULL;
                    $array = array();
                    $review = Review::find($review_id);
                    //  Update review data
                    for($i=0; $i<$counter; $i++){
                        if($summary[$i]->field == Lang::choice('messages.commendations', 1)){
                            $review->summary_commendations = $summary[$i]->value;
                        }
                        if($summary[$i]->field == Lang::choice('messages.challenges', 1)){
                            $review->summary_challenges = $summary[$i]->value;
                        }
                        if($summary[$i]->field == Lang::choice('messages.recommendations', 1)){
                            $review->recommendations = $summary[$i]->value;
                        }
                    }
                    $review->updated_at = date('Y-m-d H:i:s');
                    $review->save();
                }
                //  Action Plan
                else if($sheetTitle == 'Action Plan'){
                    $counter = count($summary);
                    $array = array();
                    if($counter>0){
                        for($i=0; $i<$counter; $i++){
                            $plans = Review::find($review_id)->plans->where('action', $summary[$i]->action)->where('responsible_person', $summary[$i]->incharge)->where('timeline', $summary[$i]->timeline)->first();
                            if(count($plans==0)){
                                if($summary[$i]->action!=NULL || $summary[$i]->incharge!=NULL || $summary[$i]->timeline!=NULL){
                                    $plan = new ReviewActPlan;
                                    $plan->review_id = $review_id;
                                    $plan->action = $summary[$i]->action;
                                    $plan->responsible_person = $summary[$i]->incharge;
                                    $plan->timeline = $summary[$i]->timeline;
                                    $plan->created_at = date('Y-m-d H:i:s');
                                    $plan->updated_at = date('Y-m-d H:i:s');
                                    $plan->save();
                                }
                            }
                            else{
                                $plan = ReviewActPlan::find($plans->id);
                                $plan->review_id = $review_id;
                                $plan->action = $summary[$i]->action;
                                $plan->responsible_person = $summary[$i]->incharge;
                                $plan->timeline = $summary[$i]->timeline;
                                $plan->updated_at = date('Y-m-d H:i:s');
                                $plan->save();
                            }
                        }
                    }
                }
                //  Assessment data
                else if($sheetTitle == 'Assessment Details'){
                    $counter = count($assessment);
                    if($counter>0){
                        for($i=0; $i<$counter; $i++){
                            $rq = ReviewQuestion::where('review_id', $review_id)->where('question_id', $assessment[$i]->question)->first();
                            if(!$rq){
                                //  Create review-question
                                $rq = new ReviewQuestion;
                                $rq->review_id = $review_id;
                                $rq->question_id = $scores[$i]->question;
                                $rq->created_at = date('Y-m-d H:i:s');
                                $rq->updated_at = date('Y-m-d H:i:s');
                                $rq->save();
                            }
                            $rqa = $rq->qa;
                            $rqn = $rq->qn;
                            $question = Question::find((int)$assessment[$i]->question);
                            if(count($question->children)>0){
                                continue;
                            }
                            else{
                                if(!$rqa){
                                    //  Create review-question-answer
                                    $rqa = new ReviewQAnswer;
                                    $rqa->review_question_id = $rq->id;
                                    $rqa->answer = Answer::idByName($assessment[$i]->response);
                                    $rqa->created_at = date('Y-m-d H:i:s');
                                    $rqa->updated_at = date('Y-m-d H:i:s');
                                    $rqa->save();
                                }
                                else{
                                    //  Update review-question-answer
                                    $rqa = ReviewQAnswer::find($rqa->id);
                                    $rqa->review_question_id = $rq->id;
                                    $rqa->answer = Answer::idByName($assessment[$i]->response);
                                    $rqa->updated_at = date('Y-m-d H:i:s');
                                    $rqa->save();
                                }
                            }
                            if(!$rqn){
                                //  Create review-note
                                $rn = new ReviewNote;
                                $rn->review_question_id = $rq->id;
                                $rn->note = $assessment[$i]->notes;
                                $rn->non_compliance = Answer::adequate($assessment[$i]->compliance);
                                $rn->created_at = date('Y-m-d H:i:s');
                                $rn->updated_at = date('Y-m-d H:i:s');
                                $rn->save();
                            }
                            else{
                                //  Update review-notes
                                $rn = ReviewNote::find($rqn->id);
                                $rn->review_question_id = $rq->id;
                                $rn->note = $assessment[$i]->notes;
                                $rn->non_compliance = Answer::adequate($assessment[$i]->compliance);
                                $rn->updated_at = date('Y-m-d H:i:s');
                                $rn->save();
                            }
                        }
                    }
                }
                //  Question Scores
                else if($sheetTitle == 'Scores'){
                    $counter = count($scores);
                    if($counter>0){
                        for($i=0; $i<$counter; $i++){
                            $rq = ReviewQuestion::where('review_id', $review_id)->where('question_id', $scores[$i]->question)->first();
                            if(!$rq){
                                //  Create review-question
                                $rq = new ReviewQuestion;
                                $rq->review_id = $review_id;
                                $rq->question_id = $scores[$i]->question;
                                $rq->created_at = date('Y-m-d H:i:s');
                                $rq->updated_at = date('Y-m-d H:i:s');
                                $rq->save();
                            }
                            $rqs = $rq->qs;
                            if(!$rqs){
                                //  Create review-question-score
                                $rqs = new ReviewQScore;
                                $rqs->review_question_id = $rq->id;
                                $rqs->audited_score = $scores[$i]->points;
                                $rqs->created_at = date('Y-m-d H:i:s');
                                $rqs->updated_at = date('Y-m-d H:i:s');
                                $rqs->save();
                            }
                            else{
                                //  Update review-question-score
                                $rqs = ReviewQScore::find($rqs->id);
                                $rqs->review_question_id = $rq->id;
                                $rqs->audited_score = $scores[$i]->points;
                                $rqs->updated_at = date('Y-m-d H:i:s');
                                $rqs->save();
                            }
                        }
                    }
                }
            });
        });*/
        return redirect('/home')->with('message', Lang::choice('messages.success-import', 1));/*
        else
            return redirect()->back()->with('message', Lang::choice('messages.failure-import', 1));;*/
    }
}