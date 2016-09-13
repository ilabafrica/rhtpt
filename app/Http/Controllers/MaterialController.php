<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\MaterialRequest;

use App\Models\Material;
use App\Models\User;

use Session;
use Auth;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //  Get all materials
        $materials = Material::all();
        return view('material.index', compact('materials'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::lists('name', 'id')->toArray();
        //  Prepare view
        $material_types = array(Material::WHOLE_BLOOD=>'Whole Blood', Material::PLASMA=>'Plasma', Material::SLIDE=>'Slide', Material::SERUM=>'Serum');
        return view('material.create', compact('material_types', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MaterialRequest $request)
    {
        //  prepare create-statement
        $material = new Material;
        $material->batch = $request->batch;
        $material->date_prepared = $request->date_prepared;
        $material->expiry_date = $request->expiry_date;
        $material->material_type = $request->material_type;
        $material->original_source = $request->original_source;
        $material->date_collected = $request->date_collected;
        $material->prepared_by = $request->prepared_by;
        $material->user_id = Auth::user()->id;
        $material->save();
        $url = session('SOURCE_URL');

        return redirect()->to($url)->with('message', trans('messages.record-successfully-saved'))->with('active_material', $material ->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //  Get specific material
        $material = Material::findOrFail($id);
        return view('material.show', compact('material'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $material = Material::findOrFail($id);
        $users = User::lists('name', 'id')->toArray();
        $user = $material->prepared_by;
        $material_type = $material->material_type;
        //  Prepare view
        $material_types = array(Material::WHOLE_BLOOD=>'Whole Blood', Material::PLASMA=>'Plasma', Material::SLIDE=>'Slide', Material::SERUM=>'Serum');
        return view('material.edit', compact('material', 'material_types', 'material_type', 'users', 'user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(MaterialRequest $request, $id)
    {
        //  prepare update-statement
        $material = Material::findOrFail($id);
        $material->batch = $request->batch;
        $material->date_prepared = $request->date_prepared;
        $material->expiry_date = $request->expiry_date;
        $material->material_type = $request->material_type;
        $material->original_source = $request->original_source;
        $material->date_collected = $request->date_collected;
        $material->prepared_by = $request->prepared_by;
        $material->user_id = Auth::user()->id;
        $material->save();
        $url = session('SOURCE_URL');

        return redirect()->to($url)->with('message', trans('messages.record-successfully-updated'))->with('active_material', $material ->id);
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
