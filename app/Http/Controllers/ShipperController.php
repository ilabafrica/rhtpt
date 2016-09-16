<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\ShipperRequest;

use App\Models\Shipper;

use Session;

class ShipperController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //  Get all shippers
        $shippers = Shipper::all();
        return view('shipper.index', compact('shippers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $shipper_types = array(Shipper::COURIER=>'Courier', Shipper::PARTNER=>'Partner', Shipper::COUNTY_LAB_COORDINATOR=>'County Lab Coordinator', Shipper::OTHER=>'Other');
        //  Prepare view
        return view('shipper.create', compact('shipper_types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ShipperRequest $request)
    {
        //  prepare create-statement
        $shipper = new Shipper;
        $shipper->name = $request->name;
        $shipper->shipper_type = $request->shipper_type;
        $shipper->contact = $request->contact;
        $shipper->save();
        $url = session('SOURCE_URL');

        return redirect()->to($url)->with('message', trans('messages.record-successfully-saved'))->with('active_shipper', $shipper ->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //  Get specific shipper
        $shipper = Shipper::findOrFail($id);
        return view('shipper.show', compact('shipper'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $shipper = Shipper::findOrFail($id);
        $shipper_types = array(Shipper::COURIER=>'Courier', Shipper::PARTNER=>'Partner', Shipper::COUNTY_LAB_COORDINATOR=>'County Lab Coordinator', Shipper::OTHER=>'Other');
        $shipper_type = $shipper->shipper_type;
        //  Prepare view
        return view('shipper.edit', compact('shipper', 'shipper_types', 'shipper_type'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ShipperRequest $request, $id)
    {
        //  prepare update-statement
        $shipper = Shipper::findOrFail($id);
        $shipper->name = $request->name;
        $shipper->shipper_type = $request->shipper_type;
        $shipper->contact = $request->contact;
        $shipper->save();
        $url = session('SOURCE_URL');

        return redirect()->to($url)->with('message', trans('messages.record-successfully-updated'))->with('active_shipper', $shipper ->id);
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
