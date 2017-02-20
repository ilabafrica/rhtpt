<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Pt;
use App\Result;

use Auth;

class ResultController extends Controller
{

    public function manageResult()
    {
        return view('result.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $results = Pt::latest()->paginate(5);
        foreach($results as $result)
        {
            $result->rnd = $result->round->name;
            $result->tester = $result->user->name;
        }
        $response = [
            'pagination' => [
                'total' => $results->total(),
                'per_page' => $results->perPage(),
                'current_page' => $results->currentPage(),
                'last_page' => $results->lastPage(),
                'from' => $results->firstItem(),
                'to' => $results->lastItem()
            ],
            'data' => $results
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
            'round_id' => 'required',
            'date_prepared' => 'required',
            'date_shipped' => 'required',
            'shipping_method' => 'required',
            'shipper_id' => 'required',
            'facility_id' => 'required',
            'panels_shipped' => 'required',
        ]);
        $request->request->add(['user_id' => Auth::user()->id]);

        $create = Shipment::create($request->all());

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
            'round_id' => 'required',
            'date_prepared' => 'required',
            'date_shipped' => 'required',
            'shipping_method' => 'required',
            'shipper_id' => 'required',
            'facility_id' => 'required',
            'panels_shipped' => 'required',
        ]);
        $request->request->add(['user_id' => Auth::user()->id]);

        $edit = Shipment::find($id)->update($request->all());

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
        Shipment::find($id)->delete();
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
        $shipment = Shipment::withTrashed()->find($id)->restore();
        return response()->json(['done']);
    }

    /**
     * Receive a shipment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function receive(Request $request)
    {
        $this->validate($request, [
            'date_received' => 'required',
            'panels_received' => 'required',
            'condition' => 'required',
            'receiver' => 'required'
        ]);

        $create = Receipt::create($request->all());

        return response()->json($create);
    }
}