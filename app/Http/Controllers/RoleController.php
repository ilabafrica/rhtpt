<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Role;

class RoleController extends Controller
{

    public function manageRole()
    {
        return view('role.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $roles = Role::latest()->withTrashed()->paginate(5);

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
            'name' => 'required',
            'description' => 'required',
        ]);

        $create = Role::create($request->all());

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
            'description' => 'required',
        ]);

        $edit = Role::find($id)->update($request->all());

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
        Role::find($id)->delete();
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
        $role = Role::withTrashed()->find($id)->restore();
        return response()->json(['done']);
    }

    /**
    *   Controller function for making view for assigning roles to users
    *
    *   @return Response
    */
    Public function assign()
    {
        $users = User::all();
        $roles = Role::all();
        $userRoleData = array('users'=>$users, 'roles'=>$roles);
        $counties = County::lists('name', 'id')->toArray();
        $subCounties = SubCounty::lists('name', 'id')->toArray();
        $facilities = Facility::lists('name', 'id')->toArray();
        $partners = Shipper::where('shipper_type', Shipper::PARTNER)->lists('name', 'id')->toArray();
        $programs = Program::lists('name', 'id')->toArray();

        return view('role.assign', $userRoleData, compact('counties', 'subCounties', 'facilities', 'partners', 'programs'));
    }

    /**
    *   Saving the mapping for user to role assignment
    *
    *   @return Response
    */
    public function saveUserRoleAssignment()
    {
        $arrayUserRoleMapping = Input::get('userRoles');
        $users = User::all();
        $roles = Role::all();

        foreach ($users as $userkey => $user)
        {
      			foreach ($roles as $roleKey => $role)
            {
        				$county = Input::get('county'.$user->id);
        				$facility = Input::get('facility'.$user->id);
                $partner = Input::get('partner'.$user->id);
        				$program = Input::get('program'.$user->id);
        				//If checkbox is clicked attach the role
        				if(!empty($arrayUserRoleMapping[$userkey][$roleKey]))
        				{
          					$user->detachRole($role);
          					$user->attachRole($role);
          					if(($county || $facility || $partner) && $role != Role::getAdminRole())
                    {
                        $program_id = NULL;
                        if($county)
                            $tier_id = $county;
                        else if($partner)
                            $tier_id = $partner;
                        else if($county)
                            $tier_id = $county;
                        else if($facility)
                        {
                            $tier_id = $facility;
                            $program_id = $program;
                        }
            						$tier = Tier::where('user_id', $user->id)
            											->where('role_id', $role->id)
            											->first();
            						if($tier)
                        {
              							$userTier = Tier::find($tier->id);
              							$userTier->user_id = $user->id;
              							$userTier->role_id = $role->id;
              							$userTier->tier = $tier_id;
              							$userTier->program_id = $program_id;
              							$userTier->save();
            						}
            						else
                        {
              							$userTier = new Tier;
              							$userTier->user_id = $user->id;
              							$userTier->role_id = $role->id;
              							$userTier->tier = $tier_id;
              							$userTier->program_id = $program_id;
              							$userTier->save();
            						}
          					}
        				}
        				//If checkbox is NOT clicked detatch the role
        				else if(empty($arrayUserRoleMapping[$userkey][$roleKey]))
                {
          					$tier = Tier::where('user_id', $user->id)
          											->where('role_id', $role->id)
          											->first();
          					if($tier)
          							$tier->delete();
          					$user->detachRole($role);
        				}
      			}
    		}
        $url = session('SOURCE_URL');
        return redirect()->to($url)->with('message', trans('messages.record-successfully-updated'));
    }
}