<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\FacilityRequest;

use App\Models\County;
use App\Models\SubCounty;
use App\Models\Facility;

use Session;

class FacilityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //  Get all facilities
        $facilities = Facility::all();
        return view('facility.index', compact('facilities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //  Return form for new Facility
        $sub_counties = SubCounty::lists('name', 'id');
        return view('facility.create', compact('sub_counties'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FacilityRequest $request)
    {
        //  Prepare create-statement
        $facility = new Facility;
        $facility->code = $request->code;
        $facility->name = $request->name;
        $facility->sub_county_id = $request->sub_county;
        $facility->mailing_address = $request->mailing_address;
        $facility->in_charge = $request->in_charge;
        $facility->longitude = $request->longitude;
        $facility->latitude = $request->latitude;
        $facility->save();
        $url = session('SOURCE_URL');

        return redirect()->to($url)->with('message', trans('messages.record-successfully-saved'))->with('active_facility', $facility ->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //  Get record
        $facility = Facility::findOrFail($id);
        return view('facility.show', compact('facility'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //  Get record
        $facility = Facility::findOrFail($id);
        $sub_county = $facility->subCounty->id;
        $sub_counties = SubCounty::lists('name', 'id');
        return view('facility.show', compact('facility', 'sub_county', 'sub_counties'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(FacilityRequest $request, $id)
    {
        //  Prepare update-statement
        $facility = Facility::findOrFail($id);
        $facility->code = $request->code;
        $facility->name = $request->name;
        $facility->sub_county_id = $request->sub_county;
        $facility->mailing_address = $request->mailing_address;
        $facility->in_charge = $request->in_charge;
        $facility->longitude = $request->longitude;
        $facility->latitude = $request->latitude;
        $facility->save();
        $url = session('SOURCE_URL');

        return redirect()->to($url)->with('message', trans('messages.record-successfully-updated'))->with('active_facility', $facility ->id);
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
