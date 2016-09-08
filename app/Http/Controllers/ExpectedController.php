<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\ExpectedRequest;

use App\Models\Expected;
use App\Models\Result;
use App\Models\User;
use App\Models\Item;

use Session;
use Auth;

class ExpectedController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //  Get all expecteds
        $expected = Expected::all();
        return view('expected.index', compact('expected'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //  Prepare selects
        $results = array(Result::POSITIVE=>'Positive', Result::NEGATIVE=>'Negative');
        $items = Item::lists('pt_id', 'id');
        $users = User::lists('name', 'id');
        //  Prepare view
        return view('expected.create', compact('results', 'items', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ExpectedRequest $request)
    {
        //  prepare create-statement
        $expected = new Expected;
        $expected->item_id = $request->item;
        $expected->result = $request->result;
        $expected->tested_by = $request->tested_by;
        $expected->save();
        $url = session('SOURCE_URL');

        return redirect()->to($url)->with('message', trans('messages.record-successfully-saved'))->with('active_expected', $expected ->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //  Get specific expected
        $expected = Expected::findOrFail($id);
        return view('expected.show', compact('expected'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $expected = Expected::findOrFail($id);
        //  Prepare selects
        $results = array(Result::POSITIVE=>'Positive', Result::NEGATIVE=>'Negative');
        $result = $expected->result;
        $items = Item::lists('pt_id', 'id');
        $item = $expected->item_id;
        $users = User::lists('name', 'id');
        $user = $expected->tested_by;
        //  Prepare view
        return view('expected.create', compact('expected', 'results', 'result', 'items', 'item', 'users', 'user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ExpectedRequest $request, $id)
    {
        //  prepare update-statement
        $expected = Expected::findOrFail($id);
        $expected->item_id = $request->item;
        $expected->result = $request->result;
        $expected->tested_by = $request->tested_by;
        $expected->save();
        $url = session('SOURCE_URL');

        return redirect()->to($url)->with('message', trans('messages.record-successfully-updated'))->with('active_expected', $expected ->id);
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
