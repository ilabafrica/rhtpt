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
        $counties = County::lists('name', 'id')->toArray();
        return view('facility.create', compact('counties'));
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
        $facility->in_charge_phone = $request->in_charge_phone;
        $facility->in_charge_email = $request->in_charge_email;
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
        $counties = County::lists('name', 'id')->toArray();
        $county_id = $facility->subCounty->county->id;
        $sub_counties = $facility->subCounty->county->subCounties->lists('name', 'id')->toArray();
        $sub_county_id = $facility->subCounty->id;
        return view('facility.edit', compact('facility', 'counties', 'county_id', 'sub_counties', 'sub_county_id'));
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
        $facility->in_charge_phone = $request->in_charge_phone;
        $facility->in_charge_email = $request->in_charge_email;
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
    /**
     * Deactivate facility - same as soft delete
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        //
    }
}
