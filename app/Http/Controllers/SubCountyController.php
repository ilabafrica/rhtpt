<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\County;
use App\SubCounty;
use App\User;
use App\Role;
use Auth;
use App;
use DB;

class SubCountyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function manageSubcounty ()
    {
         return view('subcounties.index');
    }
   
    public function index(Request $request)
    {
        
        $error = ['error' => 'No results found, please try with different keywords.'];

        $subcounties = DB::table('sub_counties')
                    ->leftjoin('counties','sub_counties.county_id', '=', 'counties.id')
                    ->select('counties.name AS counties','sub_counties.name','sub_counties.id','sub_counties.created_at','sub_counties.deleted_at')->latest()->paginate(15);
        if($request->has('q'))
        {
            $search = $request->get('q');
            $subcounties =DB::table('sub_counties')
                        ->leftjoin('counties','sub_counties.county_id', '=', 'counties.id')
                        ->select('counties.name AS counties','sub_counties.id','sub_counties.name','sub_counties.created_at','sub_counties.deleted_at')
                        ->where('sub_counties.name','LIKE', "%{search}%")
                        ->latest()->paginate(15);
        }    
         //filter users by region
        if($request->has('county')) 
        {            
          $subcounties = DB::table('counties')
                        ->leftjoin('sub_counties','sub_counties.county_id', '=', 'counties.id')
                        ->select('counties.name AS counties','sub_counties.id','sub_counties.name','sub_counties.created_at','sub_counties.deleted_at')
                        ->where('counties.id', $request->get('county'))
                        ->latest()->paginate(15);
        }
        $response = [
             'pagination'=> [
                 'total' => $subcounties->total(),
                 'per_page' => $subcounties->perPage(),
                 'current_page' => $subcounties->currentPage(),
                 'last_page' => $subcounties->lastPage(),
                 'from' => $subcounties->firstItem(),
                 'to' => $subcounties->lastItem()
                 ],

                 'data' => $subcounties
            ];

          return $subcounties->count() > 0 ? response()->json($response) : $error;
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
        $create = SubCounty::create($request->all());

        return response()->json($create);
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
        $subcnty = SubCounty::find($id);
        $subcnty->name = $request->name;
        $subcnty->county_id = $request->county_id;
        $subcnty->save();

        return response()->json($subcnty);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        SubCounty::find($id)->delete();
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
        $subcnty = SubCounty::withTrashed()->find($id)->restore();
        return response()->json(['done']);
    }

    public function search_subcounty()
    {
        $term = Input::get('term');
    
        $results = array();
        
        $queries = SubCounty::where('name', 'LIKE', '%'.$term.'%')
            ->take(5)->get();
        
        foreach ($queries as $query)
        {
            $results[] = [ 'id' => $query->id, 'value' => $query->name];
        }
        if (count($results)>0) {
            # code...
            $results[] = [ 'id' => 0, 'value' => 'No Records found'];
        } 
        return response()->json($results);
    }
    


}
