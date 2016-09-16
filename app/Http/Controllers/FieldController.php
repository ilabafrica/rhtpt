<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\FieldRequest;

use App\Models\Field;

use Session;

class FieldController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //  Get all fields
        $fields = Field::all();
        return view('field.index', compact('fields'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $fields = Field::lists('label', 'id')->toArray();
        //  Prepare view
        $field_types = array(Field::CHECKBOX=>'Checkbox', Field::DATE=>'Date', Field::EMAIL=>'E-mail', Field::FIELD=>'Field', Field::RADIO=>'Radio', Field::SELECT=>'Select List', Field::TEXT=>'Free Text');
        return view('field.create', compact('field_types', 'fields'));
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
        $field = new Field;
        $field->name = $request->name;
        $field->label = $request->label;
        $field->description = $request->description;
        $field->order = $request->order;
        $field->tag = $request->tag;
        $field->save();
        $url = session('SOURCE_URL');

        return redirect()->to($url)->with('message', trans('messages.record-successfully-saved'))->with('active_field', $field ->id);
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
        $field = Field::findOrFail($id);
        return view('field.show', compact('field'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $field = Field::findOrFail($id);
        $fields = Field::lists('label', 'id')->toArray();
        $fld = $field->order;
        //  Prepare view
        $field_types = array(Field::CHECKBOX=>'Checkbox', Field::DATE=>'Date', Field::EMAIL=>'E-mail', Field::FIELD=>'Field', Field::RADIO=>'Radio', Field::SELECT=>'Select List', Field::TEXT=>'Free Text');
        return view('field.edit', compact('field', 'field_types', 'fields', 'field'));
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
        $field = Field::findOrFail($id);
        $field->name = $request->name;
        $field->label = $request->label;
        $field->description = $request->description;
        $field->order = $request->order;
        $field->tag = $request->tag;
        $field->save();
        $url = session('SOURCE_URL');

        return redirect()->to($url)->with('message', trans('messages.record-successfully-updated'))->with('active_field', $field ->id);
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
