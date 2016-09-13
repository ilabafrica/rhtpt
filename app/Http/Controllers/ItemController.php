<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\ItemRequest;

use App\Models\Item;
use App\Models\Program;
use App\Models\Round;
use App\Models\Material;
use App\Models\User;

use Session;
use Auth;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //  Get all items
        $items = Item::all();
        return view('item.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //  prepare data for select lists
        $programs = Program::lists('name', 'id')->toArray();
        $materials = Material::lists('batch', 'id')->toArray();
        $rounds = Round::lists('name', 'id')->toArray();
        $users = User::lists('name', 'id')->toArray();
        //  Prepare view
        return view('item.create', compact('programs', 'materials', 'rounds', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ItemRequest $request)
    {
        //  prepare create-statement
        $item = new Item;
        $item->program_id = $request->program;
        $item->pt_id = $request->pt_identifier;
        $item->material_id = $request->material;
        $item->round_id = $request->round;
        $item->prepared_by = $request->prepared_by;
        $item->user_id = Auth::user()->id;
        $item->save();
        $url = session('SOURCE_URL');

        return redirect()->to($url)->with('message', trans('messages.record-successfully-saved'))->with('active_item', $item ->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //  Get specific item
        $item = Item::findOrFail($id);
        return view('item.show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //  Find item
        $item = Item::findOrFail($id);
        //  prepare data for select lists
        $programs = Program::lists('name', 'id')->toArray();
        $program = $item->program_id;
        $materials = Material::lists('batch', 'id')->toArray();
        $material = $item->material_id;
        $rounds = Round::lists('name', 'id')->toArray();
        $round = $item->round_id;
        $users = User::lists('name', 'id')->toArray();
        $user = $item->prepared_by;
        //  Prepare view
        return view('item.edit', compact('item', 'programs', 'program', 'materials', 'material', 'rounds', 'round', 'users', 'user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ItemRequest $request, $id)
    {
        //  prepare update-statement
        $item = Item::findOrFail($id);
        $item->program_id = $request->program;
        $item->pt_id = $request->pt_identifier;
        $item->material_id = $request->material;
        $item->round_id = $request->round;
        $item->prepared_by = $request->prepared_by;
        $item->user_id = Auth::user()->id;
        $item->save();
        $url = session('SOURCE_URL');

        return redirect()->to($url)->with('message', trans('messages.record-successfully-updated'))->with('active_item', $item ->id);
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
