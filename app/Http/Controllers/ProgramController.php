<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Program;

class ProgramController extends Controller
{

    public function manageProgram()
    {
        return view('program.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $error = ['error' => 'No results found, please try with different keywords.'];
        $programs = Program::latest()->withTrashed()->paginate(5);
        if($request->has('q')) 
        {
            $search = $request->get('q');
            $programs = Program::where('name', 'LIKE', "%{$search}%")->latest()->withTrashed()->paginate(5);
        }

        $response = [
            'pagination' => [
                'total' => $programs->total(),
                'per_page' => $programs->perPage(),
                'current_page' => $programs->currentPage(),
                'last_page' => $programs->lastPage(),
                'from' => $programs->firstItem(),
                'to' => $programs->lastItem()
            ],
            'data' => $programs
        ];

        return $programs->count() > 0 ? response()->json($response) : $error;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $create = Program::create($request->all());

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
        $edit = Program::find($id)->update($request->all());

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
        Program::find($id)->delete();
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
        $program = Program::withTrashed()->find($id)->restore();
        return response()->json(['done']);
    }

    //Display programs in an array, used in assigning  a role
    public function programs()
    {
        $programs = Program::lists('name', 'id');
        $response = [];
        foreach($programs as $key => $value)
        {
            $response[] = ['id' => $key, 'value' => $value];
        }
        return $response;
    }
}