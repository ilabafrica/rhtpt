<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Nonperformance;

class NonperformanceController extends Controller
{

    public function manageNonperformance()
    {
        return view('nonperf.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $error = ['error' => 'No results found, please try with different keywords.'];
        $reasons = Nonperformance::latest()->withTrashed()->paginate(5);
        if($request->has('q')) 
        {
            $search = $request->get('q');
            $reasons = Nonperformance::where('title', 'LIKE', "%{$search}%")->latest()->withTrashed()->paginate(5);
        }

        $response = [
            'pagination' => [
                'total' => $reasons->total(),
                'per_page' => $reasons->perPage(),
                'current_page' => $reasons->currentPage(),
                'last_page' => $reasons->lastPage(),
                'from' => $reasons->firstItem(),
                'to' => $reasons->lastItem()
            ],
            'data' => $reasons
        ];

        return $reasons->count() > 0 ? response()->json($response) : $error;
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
            'title' => 'required',
            'description' => 'required',
        ]);

        $create = Nonperformance::create($request->all());

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
            'title' => 'required',
            'description' => 'required',
        ]);

        $edit = Nonperformance::find($id)->update($request->all());

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
        Nonperformance::find($id)->delete();
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
        $reason = Nonperformance::withTrashed()->find($id)->restore();
        return response()->json(['done']);
    }
    /**
     * Load list of available reasons
     *
     */
    public function reasons()
    {
        $reasons = Nonperformance::lists('title', 'id');
        $response = [];
        foreach($reasons as $key => $value)
        {
            $response[] = ['id' => $key, 'value' => $value];
        }
        return response()->json($response);
    }
}