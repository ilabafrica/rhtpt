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
        $response = [
            'roles' => $roles,
            'permissions' => $permissions
        ];

        return response()->json($response);
    }
}
