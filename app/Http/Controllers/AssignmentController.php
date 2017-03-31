<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Role;
use App\User;
use App\Tier;
use App\County;
use Input;
use DB;


class AssignmentController extends Controller
{

    public function manageAssignments()
    {
        return view('assign.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $users = User::latest()->paginate(10);

        $users = User::select('*')->whereNotIn('id', function($q){
        $q->select('user_id')->from('role_user')->where('role_id', '2');
        })->latest()->paginate(10);
        
        $roles = Role::all();
        foreach($users as $user)
        {
            foreach($roles as $role)
            {
                $checks[$user->id][$role->id]['checked'] = $user->hasRole($role->name);
            }
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
            'roles' => $roles,
            'users' => $users,
            'checks' => $checks
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
        $arrayUserRoleMapping = $request->get('userRoles');

        foreach($arrayUserRoleMapping as $user => $users){
           
            foreach($users as $role){
              
                $county = $request->get('county_'.$user.'_'.$role);
                $partner = $request->get('partner_'.$user.'_'.$role);

                //If checkbox is clicked attach the role
                if(!empty($arrayUserRoleMapping[$user][$role]))
                {
                    // $user->detachRole($role);
                    // $user->attachRole($role);
                    if(($county  || $partner) && $role != Role::getAdminRole())                    {
                        
                        if($county && $role ==4){
                            $tier_id = $county;
                        }
                        else if($partner&& $role ==3 ){
                            $tier_id = $partner;
                        }

                        $tier = Tier::where('user_id', $user)->first();
                        
                        if($tier)
                        {
                            $userTier = Tier::find($tier->id);
                            $userTier->user_id = $user;
                            $userTier->role_id = $role;
                            $userTier->tier = $tier_id;
                            $userTier->save();
                        }
                        else
                        {
                            $userTier = new Tier;
                            $userTier->user_id = $user;
                            $userTier->role_id = $role;
                            $userTier->tier = $tier_id;
                            $userTier->save();
                        }
                    }
                }
                // //If checkbox is NOT clicked detatch the role
                else if(empty($arrayUserRoleMapping[$user][$role]))
                {
                    $tier = Tier::where('user_id', $user)->where('role_id', $role)->first();
                    if($tier)
                        $tier->delete();
                    $user->detachRole($role);
                }
            }
    	}
        return response()->json($arrayUserRoleMapping);
    }
    public function assignParticipantRole(Request $request){
        dd($request->all());
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
}