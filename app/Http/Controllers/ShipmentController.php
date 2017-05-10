<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Shipment;
use App\Receipt;
use App\Enrol;
use App\Consignment;

use Auth;

class ShipmentController extends Controller
{

    public function manageShipment()
    {
        return view('shipment.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $error = ['error' => 'No results found, please try with different keywords.'];
        $shipments = Shipment::latest()->withTrashed()->paginate(5);
        if($request->has('q')) 
        {
            $search = $request->get('q');
            $shipments = Shipment::where('facility_id', 'LIKE', "%{$search}%")->latest()->withTrashed()->paginate(5);
        }
        foreach($shipments as $shipment)
        {
            $shipment->rnd = $shipment->round->name;
            $shipment->shppr = $shipment->shipper->name;
            $shipment->cnty = $shipment->county->name;
            $shipment->cons = $shipment->consignments->count();
        }
        $response = [
            'pagination' => [
                'total' => $shipments->total(),
                'per_page' => $shipments->perPage(),
                'current_page' => $shipments->currentPage(),
                'last_page' => $shipments->lastPage(),
                'from' => $shipments->firstItem(),
                'to' => $shipments->lastItem()
            ],
            'data' => $shipments
        ];

        return $shipments->count() > 0 ? response()->json($response) : $error;
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
            'county_id' => 'required',
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
            'county_id' => 'required',
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

        $request->request->add(['user_id' => Auth::user()->id]);

        $edit = Shipment::find($request->shipment_id)->update($request->all());

        return response()->json($edit);
    }
    /**
     * Distribute a shipment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function distribute(Request $request)
    {
        $this->validate($request, [
            'facility_id' => 'required',
            'total' => 'required',
            'date_picked' => 'required',
            'picked_by' => 'required',
            'contacts' => 'required'
        ]);

        $create = Consignment::create($request->all());

        return response()->json($create);
    }
    /**
     * Get picked consignment(s).
     *
    */
    public function consignments($id)
    {
        $error = ['error' => 'No results found, please try with different keywords.'];
        $picks = Shipment::find($id)->consignments;
        if(count($picks)>0)
        {
            foreach($picks as $pick)
            {
                $pick->fclty = $pick->facility->name;
            }
        }
        $response = [
            'data' => $picks
        ];
        return !empty($picks) ? response()->json($response) : $error;
    }
}