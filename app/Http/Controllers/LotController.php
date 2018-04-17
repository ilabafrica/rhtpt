<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Lot;

use Auth;

class LotController extends Controller
{

    public function manageLot()
    {
        return view('lot.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $error = ['error' => 'No results found, please try with different keywords.'];
        $lots = Lot::latest()->withTrashed()->paginate(5);
        if($request->has('q')) 
        {
            $search = $request->get('q');
            $lot = Lot::where('lot', 'LIKE', "%{$search}%")->latest()->withTrashed()->paginate(5);
        }
        foreach($lots as $lot)
        {
            $lot->rnd = $lot->round->name;
            $lot->totalParts = $lot->participants();
        }
        $response = [
            'pagination' => [
                'total' => $lots->total(),
                'per_page' => $lots->perPage(),
                'current_page' => $lots->currentPage(),
                'last_page' => $lots->lastPage(),
                'from' => $lots->firstItem(),
                'to' => $lots->lastItem()
            ],
            'data' => $lots
        ];

        return $lots->count() > 0 ? response()->json($response) : $error;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        if ($request->round_id =="") {

            return response()->json(['1']);
            
        } else
        {
            $lots = Lot::where('round_id', 'LIKE', $request->round_id)->where('lot', 'LIKE', $request->lot)->withTrashed()->get();
            
            if ($lots->count() > 0) {

                return response()->json('2');

            } else
            {
                $lot = new Lot;
                $lot->round_id = $request->round_id;
                $lot->lot = $request->lot;
                $lot->tester_id = implode(", ", $request->tester_id);
                $lot->user_id = Auth::user()->id;
                $lot->save();
            }
        }
        return response()->json($lot);
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
        $request->request->add(['user_id' => Auth::user()->id]);

        $lot =  Lot::find($id);
                $lot->round_id = $request->round_id;
                $lot->lot = $request->lot;
                $lot->tester_id = implode(", ", $request->tester_id);
                $lot->user_id = Auth::user()->id;
                $lot->save();

        return response()->json('Done');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Lot::find($id)->delete();
        return response()->json(['done']);
    }

    /**
     * enable soft deleted record.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id) 
    {
        $lot = Lot::withTrashed()->find($id)->restore();
        return response()->json(['done']);
    }

    //  Fetch lots for panel dropdown
    public function lots()
    {
        $lots = Lot::all();
        $response = [];
        foreach($lots as $lot)
        {
            $response[] = ['id' => $lot->id, 'value' => $lot->round->description." Lot ".$lot->lot];
        }
        return $response;
    }
}