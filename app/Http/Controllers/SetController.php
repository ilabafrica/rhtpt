<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Set;

class SetController extends Controller
{

    public function manageSet()
    {
        return view('set.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $error = ['error' => 'No results found, please try with different keywords.'];
        $sets = Set::latest()->withTrashed()->paginate(5);
        if($request->has('q')) 
        {
            $search = $request->get('q');
            $sets = Set::where('title', 'LIKE', "%{$search}%")->latest()->withTrashed()->paginate(5);
        }
        foreach($sets as $set)
        {
            $set->ordr = $set->order($set->order);
        }

        $response = [
            'pagination' => [
                'total' => $sets->total(),
                'per_page' => $sets->perPage(),
                'current_page' => $sets->currentPage(),
                'last_page' => $sets->lastPage(),
                'from' => $sets->firstItem(),
                'to' => $sets->lastItem()
            ],
            'data' => $sets
        ];

        return $sets->count() > 0 ? response()->json($response) : $error;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $sets = Set::where('title', 'LIKE', "{$request->title}")->withTrashed()->get();

        if ($sets->count() > 0) {

            return response()->json('error');

        }else{
            $create = Set::create($request->all());

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
        $edit = Set::find($id)->update($request->all());

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
        Set::find($id)->delete();
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
        $set = Set::withTrashed()->find($id)->restore();
        return response()->json(['done']);
    }
    /**
     * Load list of available field sets
     *
     */
    public function sets()
    {
        $sets = Set::pluck('title', 'id');
        $response = [];
        foreach($sets as $key => $value)
        {
            $response[] = ['id' => $key, 'value' => $value];
        }
        return response()->json($response);
    }
}