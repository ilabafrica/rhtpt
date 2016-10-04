<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\FieldRequest;

use App\Models\FieldSet;
use App\Models\Option;

use Session;

class FieldSetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //  Get all fields
        $sets = FieldSet::all();
        return view('set.index', compact('sets'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sets = FieldSet::lists('label', 'id')->toArray();
        //  Prepare view
        return view('set.create', compact('$sets'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FieldRequest $request)
    {
        //  prepare create-statement
        $set = new FieldSet;
        $set->name = $request->name;
        $set->label = $request->label;
        $set->description = $request->description;
        $set->order = $request->order;
  			$set->save();
  			$url = session('SOURCE_URL');
        return redirect()->to($url)->with('message', trans('messages.record-successfully-saved'))->with('active_set', $set ->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //  Get specific field
        $set = FieldSet::findOrFail($id);
        return view('set.show', compact('set'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $set = FieldSet::findOrFail($id);
        $sets = FieldSet::lists('label', 'id')->toArray();
        $fld = $set->order;
        //  Prepare view
        return view('set.edit', compact('sets', 'set', 'fld'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(FieldRequest $request, $id)
    {
        //  prepare update-statement
        $set = FieldSet::findOrFail($id);
        $set->name = $request->name;
        $set->label = $request->label;
        $set->description = $request->description;
        $set->order = $request->order;
  			$set->save();
  			$url = session('SOURCE_URL');
        return redirect()->to($url)->with('message', trans('messages.record-successfully-updated'))->with('active_set', $set ->id);
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
