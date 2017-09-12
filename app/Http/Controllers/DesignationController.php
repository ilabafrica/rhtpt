<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Designation;

class DesignationController extends Controller
{

    public function manageDesignation()
    {
        return view('designation.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $error = ['error' => 'No results found, please try with different keywords.'];
        $designations = Designation::latest()->withTrashed()->paginate(5);
        if($request->has('q')) 
        {
            $search = $request->get('q');
            $designations = Designation::where('name', 'LIKE', "%{$search}%")->latest()->withTrashed()->paginate(5);
        }

        $response = [
            'pagination' => [
                'total' => $designations->total(),
                'per_page' => $designations->perPage(),
                'current_page' => $designations->currentPage(),
                'last_page' => $designations->lastPage(),
                'from' => $designations->firstItem(),
                'to' => $designations->lastItem()
            ],
            'data' => $designations
        ];

        return $designations->count() > 0 ? response()->json($response) : $error;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $designations = Designation::where('name', 'LIKE', "{$request->name}")->withTrashed()->get();

        if ($designations->count() > 0) {

            return response()->json('error');

        }else{
            $create = Designation::create($request->all());

            return response()->json($create);
        }
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
        $edit = Designation::find($id)->update($request->all());

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
        Designation::find($id)->delete();
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
        $designation = Designation::withTrashed()->find($id)->restore();
        return response()->json(['done']);
    }

    //Display designations in an array, used in assigning  a role
    public function designations()
    {
        $designations = Designation::pluck('name', 'id');
        $response = [];
        foreach($designations as $key => $value)
        {
            $response[] = ['id' => $key, 'value' => $value];
        }
        return $response;
    }
}