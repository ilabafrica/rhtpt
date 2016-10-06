<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\County;
use App\Models\SubCounty;
use App\Models\Shipper;

use Input;
use Response;
use DB;

class ApiController extends Controller
{
    public function dropdown($id)
    {
       //$county_id = Input::get('county');
       $subCounties = County::find($id)->subCounties();
       return Response::make($subCounties->get(['id','name']));
    }
    public function dropdown2($id)
    {
       $facilities = SubCounty::find($id)->facilities();
       return Response::make($facilities->get(['id','name']));
    }
    public function dropdown3($id)
    {
       $partners = Shipper::whereIn('id', DB::table('shipper_facilities')->where('facility_id', $id)->lists('shipper_id'));
       return Response::make($partners->get(['id','name']));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
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
