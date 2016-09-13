<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\RoundRequest;

use App\Models\Round;

use Session;
use Auth;

class RoundController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //  Get all rounds
        $rounds = Round::all();
        return view('round.index', compact('rounds'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //  Prepare view
        return view('round.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoundRequest $request)
    {
        //  prepare create-statement
        $round = new Round;
        $round->name = $request->name;
        $round->description = $request->description;
        $round->start_date = $request->start_date;
        $round->end_date = $request->end_date;
        $round->user_id = Auth::user()->id;
        $round->save();
        $url = session('SOURCE_URL');

        return redirect()->to($url)->with('message', trans('messages.record-successfully-saved'))->with('active_round', $round ->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //  Get specific round
        $round = Round::findOrFail($id);
        return view('round.show', compact('round'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //  Prepare view
        $round = Round::findOrFail($id);
        return view('round.edit', compact('round'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RoundRequest $request, $id)
    {
        //  prepare update-statement
        $round = Round::findOrFail($id);
        $round->name = $request->name;
        $round->description = $request->description;
        $round->start_date = $request->start_date;
        $round->end_date = $request->end_date;
        $round->user_id = Auth::user()->id;
        $round->save();
        $url = session('SOURCE_URL');

        return redirect()->to($url)->with('message', trans('messages.record-successfully-updated'))->with('active_round', $round ->id);
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
