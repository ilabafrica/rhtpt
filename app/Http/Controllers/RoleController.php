<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Requests\RoleRequest;

use App\Models\Role;
use App\Models\User;

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

        return view('role.assign', $userRoleData);
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
    			foreach ($roles as $roleKey => $role) {

    				$county = Input::get('county'.$user->id);
    				$sub_county = Input::get('sub_county'.$user->id);
    				//If checkbox is clicked attach the role
    				if(!empty($arrayUserRoleMapping[$userkey][$roleKey]))
    				{
    					$user->detachRole($role);
    					$user->attachRole($role);
    					/*if(($county || $sub_county) && $role != Role::getAdminRole()){
    						$county?$tier_id=$county:$tier_id=$sub_county;
    						$tier = RoleUserTier::where('user_id', $user->id)
    											->where('role_id', $role->id)
    											->first();
    						if($tier){
    							$userTier = RoleUserTier::find($tier->id);
    							$userTier->user_id = $user->id;
    							$userTier->role_id = $role->id;
    							$userTier->tier = $tier_id;
    							$userTier->save();
    						}
    						else{
    							$userTier = new RoleUserTier;
    							$userTier->user_id = $user->id;
    							$userTier->role_id = $role->id;
    							$userTier->tier = $tier_id;
    							$userTier->save();
    						}
    					}*/
    				}
    				//If checkbox is NOT clicked detatch the role
    				else if(empty($arrayUserRoleMapping[$userkey][$roleKey]))
            {
    					/*$tier = RoleUserTier::where('user_id', $user->id)
    											->where('role_id', $role->id)
    											->first();
    					if($tier)
    							$tier->delete();*/
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
