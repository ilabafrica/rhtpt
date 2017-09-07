<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Role;
use Hash;

class ProfileController extends Controller
{
    

    public function manageProfile()
    {
        return view('profile.index');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $user->sex = $user->maleOrFemale($user->gender);
        $user->rl = $user->ru()->role_id;
        $user->participant = Role::idByName("Participant");
        $user->image?$user->image=$user->image:$user->image='default.png';

        return response()->json($user);
    }
    /**
     * Show the form for updating user-profile.
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
     * Update user-profile.
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
    	$exploded = explode(',', $request->image);
		$decoded = base64_decode($exploded[1]);
		if(str_contains($exploded[0], 'jpeg'))
			$extension = 'jpg';
		else
			$extension = 'png';
		$fileName = uniqid().'.'.$extension;
        $folder = '/images/profiles/';
        file_put_contents(public_path().$folder.$fileName, $decoded);
        $user = auth()->user();
    	$this->validate($request, [
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required'
        ]);
    	$user = auth()->user();
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->email = $request->email;
        $user->address = $request->address;
        $user->image = $fileName;
        $user->save();
        if($request->role)
        {
            $role = $request->rl;
            $tier = NULL;
            $program_id = NULL;
            $designation = NULL;
            if($role == Role::idByName("Participant"))
            {
                $tier = $request->facility_id;
                $program_id = $request->program_id;
                $designation = $request->designation;
            }
            $user->detachAllRoles();
            $ru = DB::table('role_user')->insert(["user_id" => $id, "role_id" => $role, "tier" => $tier, "program_id" => $program_id, 'designation' => $designation]);
        }
        return response()->json($user);
    }
    /**
     * Update user-profile.
     *
     */
    public function updatePassword(Request $request)
    {
    	// dd($request->all());
    	$this->validate($request, [
	        'old'     => 'required',
	        'new'     => 'required|min:6',
	        'confirm' => 'required|same:new',
	    ]);
	    $user = auth()->user();
	    if(!Hash::check($request->old, $user->password))
	    {
         	return response()->json(['error' => 'You entered a wrong current password.']);
    	}
        else if(Hash::check($request->new, $user->password))
        {
            return response()->json(['error' => 'Your new password should not match the current password.']);
        }
    	else
    	{
    		$user->password = Hash::make($request->new);
    		$user->save();
    		return response()->json($user);
    	}
    }
    /**
     * Transfer user to a new facility.
     *
     */
    public function transferUser(Request $request)
    {
    	$user = auth()->user();
    	$tier = $request->mfl_code;
    	$program_id = $request->program;
    	$designation = $request->designation;
        $userTier = Tier::where('user_id', $user->id)->first();
        $userTier->tier = $tier;
        $userTier->program_id = $program_id;
        $userTier->designation = $designation;
        $userTier->save();
        return response()->json($userTier);
    }
    /**
     * Blank function to rid console errors.
     *
     */
    public function blank()
    {

    }
}