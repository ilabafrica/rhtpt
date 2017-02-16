<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Shipper;

class ShipperController extends Controller
{

    public function manageShipper()
    {
        return view('shipper.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $shippers = Shipper::latest()->withTrashed()->paginate(5);
        foreach($shippers as $shipper)
            $shipper->st = $shipper->shipper($shipper->shipper_type);
        $response = [
            'pagination' => [
                'total' => $shippers->total(),
                'per_page' => $shippers->perPage(),
                'current_page' => $shippers->currentPage(),
                'last_page' => $shippers->lastPage(),
                'from' => $shippers->firstItem(),
                'to' => $shippers->lastItem()
            ],
            'data' => $shippers
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
            'shipper_type' => 'required',
            'contact' => 'required',
            'phone' => 'required',
            'email' => 'required',
        ]);

        $create = Shipper::create($request->all());

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
            'description' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        $edit = Shipper::find($id)->update($request->all());

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
        Shipper::find($id)->delete();
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
        $shipper = Shipper::withTrashed()->find($id)->restore();
        return response()->json(['done']);
    }
    /**
     * Function to return list of shipper types.
     *
     */
    public function options()
    {
        $shipper_types = [
            Shipper::COURIER => 'Courier',
            Shipper::PARTNER => 'Partner',
            Shipper::COUNTY_LAB_COORDINATOR => 'County Lab Coordinator',
            Shipper::OTHER => 'Other'
        ];
        $categories = [];
        foreach($shipper_types as $key => $value)
        {
            $categories[] = ['title' => $value, 'name' => $key];
        }
        return $categories;
    }
    /**
     * Function to return list of shippers.
     *
     */
    public function shippers($id)
    {
        $shippers = Shipper::where('shipper_type', $id)->lists('name', 'id');
        $categories = [];
        foreach($shippers as $key => $value)
        {
            $categories[] = ['id' => $key, 'value' => $value];
        }
        return $categories;
    }
}