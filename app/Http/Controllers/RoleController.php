<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Requests\RoleRequest;

use App\Models\Role;
use App\Models\User;
use App\Models\County;
use App\Models\SubCounty;
use App\Models\Facility;
use App\Models\Shipper;
use App\Models\Program;
use App\Models\Tier;

use Config;
use Response;
use Auth;
use Session;
use Lang;
use Input;

class RoleController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $roles = Role::paginate(Config::get('kblis.page-items'));
        return view('role.index', compact('roles'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('role.create');
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

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(RoleRequest $request)
    {
        $role = new Role;
        $role->name = $request->name;
        $role->display_name = $request->display_name;
        $role->description = $request->description;
        $role->save();
        $url = session('SOURCE_URL');

        return redirect()->to($url)->with('message', trans('messages.record-successfully-saved'))->with('active_role', $role ->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $role = Role::find($id);
        return view('role.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $role = Role::find($id);
        return view('role.edit', compact('role'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(RoleRequest $request, $id)
    {
        $role = Role::find($id);
        $role->name = $request->name;
        $role->display_name = $request->display_name;
        $role->description = $request->description;
        $role->save();
        $url = session('SOURCE_URL');

        return redirect()->to($url)->with('message', trans('messages.record-successfully-updated'))->with('active_role', $role ->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function delete($id)
    {
        //Soft delete the role
        $role = Role::find($id);
        $role->delete();
        // redirect
        $url = session('SOURCE_URL');

        return redirect()->to($url)->with('message', trans('messages.record-successfully-deleted'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
