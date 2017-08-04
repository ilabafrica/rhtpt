<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Permission;
use App\Role;

use Response;
use Auth;
use Session;
use Lang;
use Input;

class PermissionController extends Controller
{
    public function managePermissions()
    {
        return view('permission.index');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $permissions = Permission::all();
        $roles = Role::all();
        foreach($permissions as $permission)
        {
            foreach($roles as $role)
            {
                $checks[$permission->id][$role->id]['checked'] = $permission->hasRole($role->name);
            }
        }
        $response = [
            'roles' => $roles,
            'permissions' => $permissions,
            'checks' => $checks
        ];
        return response()->json($response);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $arrayPermissionRoleMapping = $request->permissionRoles;
        $permissions = Permission::all();
        $roles = Role::all();
        foreach ($permissions as $permission) 
        {
            foreach ($roles as $role) 
            {
                //If checkbox is clicked attach the permission
                if(!empty($arrayPermissionRoleMapping[$permission->id][$role->id]))
                {   $role->detachPermission($permission);
                    $role->attachPermission($permission);
                }
                //If checkbox is NOT clicked detatch the permission
                elseif (empty($arrayPermissionRoleMapping[$permission->id][$role->id]))
                {
                    $role->detachPermission($permission);
                }
            }
        }
        return response()->json($arrayPermissionRoleMapping);
    }
}
