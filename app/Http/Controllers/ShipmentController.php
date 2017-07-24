<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Shipment;
use App\Receipt;
use App\Enrol;
use App\Consignment;
use App\County;
use App\SubCounty;
use App\Facility;
use App\Round;

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
        if(Auth::user()->isCountyCoordinator())
        {
            $shipments = County::find(Auth::user()->ru()->tier)->shipments()->latest()->withTrashed()->paginate(5);
        }
        else if(Auth::user()->isSubCountyCoordinator())
        {
            $facilities = SubCounty::find(Auth::user()->ru()->tier)->facilities->pluck('id')->toArray();
            $shipments = Consignment::whereIn('facility_id', $facilities)->latest()->withTrashed()->paginate(5);
        }
        else if(Auth::user()->isFacilityInCharge())
        {
            $facility = Facility::find(Auth::user()->ru()->tier);
            $shipments = $facility->consignments()->latest()->withTrashed()->paginate(5);
        }
        if($request->has('q')) 
        {
            $search = $request->get('q');
            $shipments = Shipment::where('facility_id', 'LIKE', "%{$search}%")->latest()->withTrashed()->paginate(5);
        }
        if(Auth::user()->isSubCountyCoordinator() || Auth::user()->isFacilityInCharge())
        {
            foreach($shipments as $consignment)
            {
                $consignment->rnd = $consignment->shipment->round->name;
                $consignment->fclty = $consignment->facility->name;
            }
        }
        else
        {
            foreach($shipments as $shipment)
            {
                $ptRound = Round::withTrashed()->find($shipment->round_id);
                $shipment->rnd = $ptRound->name;
                $shipment->shppr = $shipment->shipper->name;
                $shipment->cnty = $shipment->county->name;
                $shipment->cons = $shipment->consignments->count();
            }
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
        $request->request->add(['user_id' => Auth::user()->id]);

        $edit = Shipment::find($id);
        $edit->round_id = $request->round_id;
        $edit->date_prepared = $request->date_prepared;
        $edit->date_shipped = $request->date_shipped;
        $edit->shipping_method = $request->shipping_method;
        $edit->shipper_id = $request->shipper_id;
        $edit->county_id = $request->county_id;
        $edit->panels_shipped = $request->panels_shipped;
        $edit->save();
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
        // dd($request->all());
        $edit = Shipment::find((int)$request->shipment_id);
        
        $edit->date_received = $request->date_received;
        $edit->panels_received = $request->panels_received;
        $edit->condition = $request->condition;
        $edit->receiver = $request->receiver;
        $edit->save();

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
        // dd($request->all());
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