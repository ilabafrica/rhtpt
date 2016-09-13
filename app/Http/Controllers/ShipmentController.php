<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\ShipmentRequest;

use App\Models\Shipment;
use App\Models\Round;
use App\Models\User;

use Session;
use Auth;

class ShipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //  Get all shipments
        $shipments = Shipment::all();
        return view('shipment.index', compact('shipments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $shipping_methods = array(Shipment::POST=>'Post', Shipment::COURIER=>'Courier');
        $users = User::lists('name', 'id')->toArray();
        $rounds = Round::lists('name', 'id')->toArray();
        //  Prepare view
        return view('shipment.create', compact('shipping_methods', 'users', 'rounds'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ShipmentRequest $request)
    {
        //  prepare create-statement
        $shipment = new Shipment;
        $shipment->round_id = $request->round;
        $shipment->date_prepared = $request->date_prepared;
        $shipment->date_shipped = $request->date_shipped;
        $shipment->shipping_method = $request->shipping_method;
        $shipment->courier = $request->courier;
        $shipment->participant = $request->participant;
        $shipment->panels_shipped = $request->panels_shipped;
        $shipment->user_id = Auth::user()->id;
        $shipment->save();
        $url = session('SOURCE_URL');

        return redirect()->to($url)->with('message', trans('messages.record-successfully-saved'))->with('active_shipment', $shipment ->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //  Get specific shipment
        $shipment = Shipment::findOrFail($id);
        return view('shipment.show', compact('shipment'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $shipment = Shipment::findOrFail($id);
        $shipping_methods = array(Shipment::POST=>'Post', Shipment::COURIER=>'Courier');
        $shipping_method = $shipment->shipping_method;
        $users = User::lists('name', 'id')->toArray();
        $user = $shipment->participant;
        $rounds = Round::lists('name', 'id')->toArray();
        $round = $shipment->round_id;
        //  Prepare view
        return view('shipment.edit', compact('shipment', 'shipping_methods', 'shipping_method', 'rounds', 'round', 'users', 'user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ShipmentRequest $request, $id)
    {
        //  prepare update-statement
        $shipment = Shipment::findOrFail($id);
        $shipment->round_id = $request->round;
        $shipment->date_prepared = $request->date_prepared;
        $shipment->date_shipped = $request->date_shipped;
        $shipment->shipping_method = $request->shipping_method;
        $shipment->courier = $request->courier;
        $shipment->participant = $request->participant;
        $shipment->panels_shipped = $request->panels_shipped;
        $shipment->user_id = Auth::user()->id;
        $shipment->save();
        $url = session('SOURCE_URL');

        return redirect()->to($url)->with('message', trans('messages.record-successfully-updated'))->with('active_shipment', $shipment ->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
