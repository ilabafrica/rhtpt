<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Item;
use App\Material;
use App\Round;
use App\User;

use Auth;

class ItemController extends Controller
{

    public function manageItem()
    {
        return view('item.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $items = Item::latest()->withTrashed()->paginate(5);
        foreach($items as $item)
        {
            $item->mtrl = $item->material->batch;
            $item->rnd = $item->round->name;
            $item->tstr = User::range($item->tester_id_range);
        }
        $response = [
            'pagination' => [
                'total' => $items->total(),
                'per_page' => $items->perPage(),
                'current_page' => $items->currentPage(),
                'last_page' => $items->lastPage(),
                'from' => $items->firstItem(),
                'to' => $items->lastItem()
            ],
            'data' => $items
        ];

        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'tester_id_range' => 'required',
            'pt_id' => 'required',
            'material_id' => 'required',
            'round_id' => 'required',
            'prepared_by' => 'required',
        ]);
        $request->request->add(['user_id' => Auth::user()->id]);

        $create = Item::create($request->all());

        return response()->json($create);
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
        $this->validate($request, [
            'tester_id_range' => 'required',
            'pt_id' => 'required',
            'material_id' => 'required',
            'round_id' => 'required',
            'prepared_by' => 'required',
        ]);
        $request->request->add(['user_id' => Auth::user()->id]);
        
        $edit = Item::find($id)->update($request->all());

        return response()->json($edit);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Item::find($id)->delete();
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
        $item = Item::withTrashed()->find($id)->restore();
        return response()->json(['done']);
    }
    /**
     * Function to return list of materials.
     *
     */
    public function materials()
    {
        $materials = Material::lists('batch', 'id');
        $categories = [];
        foreach($materials as $key => $value)
        {
            $categories[] = ['id' => $key, 'value' => $value];
        }
        return $categories;
    }
    /**
     * Function to return list of rounds.
     *
     */
    public function rounds()
    {
        $rounds = Round::lists('name', 'id');
        $categories = [];
        foreach($rounds as $key => $value)
        {
            $categories[] = ['id' => $key, 'value' => $value];
        }
        return $categories;
    }
}