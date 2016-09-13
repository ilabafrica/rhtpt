<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\ReceiptRequest;

use App\Models\Receipt;
use App\Models\Shipment;
use App\Models\User;

use Session;
use Auth;

class ReceiptController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //  Get all receipts
        $receipts = Receipt::all();
        return view('receipt.index', compact('receipts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $shipments = Shipment::lists('participant', 'id')->toArray();
        $users = User::lists('name', 'id')->toArray();
        //  Prepare view
        return view('receipt.create', compact('shipments', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ReceiptRequest $request)
    {
        //  prepare create-statement
        $receipt = new Receipt;
        $receipt->shipment_id = $request->shipment;
        $receipt->date_received = $request->date_received;
        $receipt->panels_received = $request->panels_received;
        $receipt->condition = $request->condition;
        $receipt->storage = $request->storage;
        $receipt->transit_temperature = $request->transit_temperature;
        $receipt->recipient = $request->recipient;
        $receipt->save();
        $url = session('SOURCE_URL');

        return redirect()->to($url)->with('message', trans('messages.record-successfully-saved'))->with('active_receipt', $receipt ->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //  Get specific receipt
        $receipt = Receipt::findOrFail($id);
        return view('receipt.show', compact('receipt'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $receipt = Receipt::findOrFail($id);
        $shipments = Shipment::lists('participant', 'id')->toArray();
        $shipment = $receipt->shipment_id;
        $users = User::lists('name', 'id')->toArray();
        $user = $receipt->recipient;
        //  Prepare view
        return view('receipt.edit', compact('receipt', 'shipments', 'shipment', 'users', 'user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ReceiptRequest $request, $id)
    {
        //  prepare update-statement
        $receipt = Receipt::findOrFail($id);
        $receipt->shipment_id = $request->shipment;
        $receipt->date_received = $request->date_received;
        $receipt->panels_received = $request->panels_received;
        $receipt->condition = $request->condition;
        $receipt->storage = $request->storage;
        $receipt->transit_temperature = $request->transit_temperature;
        $receipt->recipient = $request->recipient;
        $receipt->save();
        $url = session('SOURCE_URL');

        return redirect()->to($url)->with('message', trans('messages.record-successfully-updated'))->with('active_receipt', $receipt ->id);
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
