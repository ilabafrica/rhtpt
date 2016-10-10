<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ResultRequest;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\FieldSet;
use App\Models\Field;
use App\Models\Option;
use App\Models\Program;
use App\Models\Result;
use App\Models\Pt;

use Auth;
use Input;
use Session;

class ResultController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results = Pt::all();
        return view('result.index', compact('results'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $programs = Program::lists('name', 'id')->toArray();
        $sets = FieldSet::orderBy('order')->get();
        return view('result.create', compact('sets', 'programs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
      //dd(Input::all());
        //	Save pt first then proceed to save form fields
        $pt = new Pt;
        $pt->receipt_id = 1;//Input::get('receipt_id');
        $pt->user_id = Auth::user()->id;
        $pt->save();
        //	Proceed to form-fields
        foreach (Input::all() as $key => $value)
        {
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
        //	Redirect
        $url = session('SOURCE_URL');
        return redirect()->to($url)->with('message', trans('messages.record-successfully-saved'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
      public function show($id)
      {
          //  Get results for the selected pt submission
          $results = Pt::find($id)->results()->orderBy('field_id', 'ASC')->get();
          return view('result.show', compact('results'));
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
          //
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
