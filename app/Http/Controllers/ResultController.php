<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Pt;
use App\Result;

use Auth;
use Input;

class ResultController extends Controller
{

    public function manageResult()
    {
        return view('result.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $results = Pt::latest()->paginate(5);
        foreach($results as $result)
        {
            $result->rnd = $result->round->name;
            $result->tester = $result->user->name;
        }
        $response = [
            'pagination' => [
                'total' => $results->total(),
                'per_page' => $results->perPage(),
                'current_page' => $results->currentPage(),
                'last_page' => $results->lastPage(),
                'from' => $results->firstItem(),
                'to' => $results->lastItem()
            ],
            'data' => $results
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
        dd(Input::all());
        //	Save pt first then proceed to save form fields
        $pt = new Pt;
        $pt->receipt_id = 1;//Input::get('receipt_id');
        $pt->user_id = Auth::user()->id;
        //$pt->save();
        //	Proceed to form-fields
        foreach (Input::all() as $key => $value)
        {
            dd($value);
            if((stripos($key, 'token') !==FALSE) || (stripos($key, 'method') !==FALSE))
                continue;
            else if(stripos($key, 'field') !==FALSE)
            {
                $fieldId = $this->strip($key);
                if(is_array($value))
                  $value = implode(', ', $value);
                $result = new Result;
                $result->pt_id = $pt->id;
                $result->field_id = $fieldId;
          		$result->response = $value;
                $result->save();
            }
            else if(stripos($key, 'comment') !==FALSE)
            {
                if($value)
                {
                    $result = Result::where('field_id', $key)->first();
                    $result->comment = $value;
                    $result->save();
                }
            }
        }

        return response()->json($pt);
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
            'round_id' => 'required',
            'date_prepared' => 'required',
            'date_shipped' => 'required',
            'shipping_method' => 'required',
            'shipper_id' => 'required',
            'facility_id' => 'required',
            'panels_shipped' => 'required',
        ]);
        $request->request->add(['user_id' => Auth::user()->id]);

        $edit = Shipment::find($id)->update($request->all());

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
        Shipment::find($id)->delete();
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
        $shipment = Shipment::withTrashed()->find($id)->restore();
        return response()->json(['done']);
    }

    /**
     * Receive a shipment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function receive(Request $request)
    {
        $this->validate($request, [
            'date_received' => 'required',
            'panels_received' => 'required',
            'condition' => 'required',
            'receiver' => 'required'
        ]);

        $create = Receipt::create($request->all());

        return response()->json($create);
    }
    /**
  	 * Remove the specified begining of text to get Id alone.
  	 *
  	 * @param  int  $id
  	 * @return Response
  	 */
  	public function strip($field)
  	{
    		if(($pos = strpos($field, '_')) !== FALSE)
    		return substr($field, $pos+1);
  	}
}