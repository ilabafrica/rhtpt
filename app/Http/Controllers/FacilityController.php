<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Facility;

class FacilityController extends Controller
{

    public function manageFacility()
    {
        return view('facility.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $facilitys = Facility::latest()->paginate(5);
        foreach($facilitys as $facility)
        {
            $facility->sub = $facility->subCounty->name;
            $facility->county = $facility->subCounty->county->name;
        }

        $response = [
            'pagination' => [
                'total' => $facilitys->total(),
                'per_page' => $facilitys->perPage(),
                'current_page' => $facilitys->currentPage(),
                'last_page' => $facilitys->lastPage(),
                'from' => $facilitys->firstItem(),
                'to' => $facilitys->lastItem()
            ],
            'data' => $facilitys
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
        $this->validate($request, [
            'name' => 'required',
            'label' => 'required',
            'description' => 'required',
            'order' => 'required',
            'tag' => 'required',
            'options' => 'required',
        ]);

        $create = Facility::create($request->all());

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
            'name' => 'required',
            'label' => 'required',
            'description' => 'required',
            'order' => 'required',
            'tag' => 'required',
            'options' => 'required',
        ]);

        $edit = Facility::find($id)->update($request->all());

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
        Facility::find($id)->delete();
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
        $facility = Facility::withTrashed()->find($id)->restore();
        return response()->json(['done']);
    }
}