<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Option;

class OptionController extends Controller
{

    public function manageOption()
    {
        return view('option.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $error = ['error' => 'No results found, please try with different keywords.'];
        $options = Option::latest()->withTrashed()->paginate(5);
        if($request->has('q')) 
        {
            $search = $request->get('q');
            $options = Option::where('title', 'LIKE', "%{$search}%")->latest()->withTrashed()->paginate(5);
        }

        $response = [
            'pagination' => [
                'total' => $options->total(),
                'per_page' => $options->perPage(),
                'current_page' => $options->currentPage(),
                'last_page' => $options->lastPage(),
                'from' => $options->firstItem(),
                'to' => $options->lastItem()
            ],
            'data' => $options
        ];

        return $options->count() > 0 ? response()->json($response) : $error;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $options = Option::where('title', 'LIKE', "{$request->title}")->withTrashed()->get();

        if ($options->count() > 0) {

            return response()->json('error');

        }else{
           $create = Option::create($request->all());

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
        $edit = Option::find($id)->update($request->all());

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
        Option::find($id)->delete();
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
        $option = Option::withTrashed()->find($id)->restore();
        return response()->json(['done']);
    }
    /**
     * Load list of available options
     *
     */
    public function options()
    {
        $options = Option::pluck('title', 'id');
        $response = [];
        foreach($options as $key => $value)
        {
            $response[] = ['id' => $key, 'value' => $value];
        }
        return response()->json($response);
    }
}