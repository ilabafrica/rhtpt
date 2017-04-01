<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Pt;
use App\Result;
use App\Field;
use App\Option;

use Auth;
use Jenssegers\Date\Date as Carbon;

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
        $error = ['error' => 'No results found, please try with different keywords.'];
        $results = Pt::latest()->withTrashed()->paginate(5);
        if($request->has('q')) 
        {
            $search = $request->get('q');
            $results = Pt::where('pt_id', 'LIKE', "%{$search}%")->latest()->withTrashed()->paginate(5);
        }
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
        return $results->count() > 0 ? response()->json($response) : $error;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         //	Save pt first then proceed to save form fields
        $pt = new Pt;
        $pt->round_id = $request->get('round_id');
        $pt->user_id = Auth::user()->id;
        $pt->panel_status = Pt::NOT_CHECKED;
        $pt->save();
        //	Proceed to form-fields
        foreach ($request->all() as $key => $value)
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
        dd($pt);
        /**
        * Begin background processing
        */
        $rs = $pt->results;
        $date_pt_panel_received = NULL;
        $date_pt_panel_constituted = NULL;
        $date_pt_panel_tested = NULL;
        $test_1_kit_name = NULL;
        $test_2_kit_name = NULL;
        $test_1_kit_lot_no = NULL;
        $test_2_kit_lot_no = NULL;
        $test_1_expiry_date = NULL;
        $test_2_expiry_date = NULL;
        $test_3_expiry_date = NULL;

        $pt_panel_1_test_1_results = NULL;
        $pt_panel_1_test_2_results = NULL;
        $pt_panel_1_test_3_results = NULL;
        $pt_panel_1_final_results = NULL;
        $pt_panel_2_test_1_results = NULL;
        $pt_panel_2_test_2_results = NULL;
        $pt_panel_2_test_3_results = NULL;
        $pt_panel_2_final_results = NULL;
        $pt_panel_3_test_1_results = NULL;
        $pt_panel_3_test_2_results = NULL;
        $pt_panel_3_test_3_results = NULL;
        $pt_panel_3_final_results = NULL;
        $pt_panel_4_test_1_results = NULL;
        $pt_panel_4_test_2_results = NULL;
        $pt_panel_4_test_3_results = NULL;
        $pt_panel_4_final_results = NULL;
        $pt_panel_5_test_1_results = NULL;
        $pt_panel_5_test_2_results = NULL;
        $pt_panel_5_test_3_results = NULL;
        $pt_panel_5_final_results = NULL;
        $pt_panel_6_test_1_results = NULL;
        $pt_panel_6_test_2_results = NULL;
        $pt_panel_6_test_3_results = NULL;
        $pt_panel_6_final_results = NULL;
        foreach($rs as $rss)
        {
            //  Get all variables first to be used after the loop
            if($rss->field_id == Field::idByUID('Date PT Panel Received'))
                $date_pt_panel_received = $rss->response;
            if($rss->field_id == Field::idByUID('Date PT Panel Constituted'))
                $date_pt_panel_constituted = $rss->response;
            if($rss->field_id == Field::idByUID('Date PT Panel Tested'))
                $date_pt_panel_tested = $rss->response;
            if($rss->field_id == Field::idByUID('Test 1 Kit Name'))
                $test_1_kit_name = $rss->response;
            if($rss->field_id == Field::idByUID('Test 2 Kit Name'))
                $test_2_kit_name = $rss->response;
            if($rss->field_id == Field::idByUID('Test 1 Lot No.'))
                $test_1_kit_lot_no = $rss->response;
            if($rss->field_id == Field::idByUID('Test 2 Lot No.'))
                $test_2_kit_lot_no = $rss->response;
            if($rss->field_id == Field::idByUID('Test 1 Expiry Date'))
                $test_1_expiry_date = $rss->response;
            if($rss->field_id == Field::idByUID('Test 2 Expiry Date'))
                $test_2_expiry_date = $rss->response;
            if($rss->field_id == Field::idByUID('Test 3 Expiry Date'))
                $test_3_expiry_date = $rss->response;

            if($rss->field_id == Field::idByUID('PT Panel 1 Test 1 Results'))
                $pt_panel_1_test_1_results = $rss->response;
            if($rss->field_id == Field::idByUID('PT Panel 1 Test 2 Results'))
                $pt_panel_1_test_2_results = $rss->response;
            if($rss->field_id == Field::idByUID('PT Panel 1 Test 3 Results'))
                $pt_panel_1_test_3_results = $rss->response;
            if($rss->field_id == Field::idByUID('PT Panel 1 Final Results'))
                $pt_panel_1_final_results = $rss->response;
            if($rss->field_id == Field::idByUID('PT Panel 2 Test 1 Results'))
                $pt_panel_2_test_1_results = $rss->response;
            if($rss->field_id == Field::idByUID('PT Panel 2 Test 2 Results'))
                $pt_panel_2_test_2_results = $rss->response;
            if($rss->field_id == Field::idByUID('PT Panel 2 Test 3 Results'))
                $pt_panel_2_test_3_results = $rss->response;
            if($rss->field_id == Field::idByUID('PT Panel 2 Final Results'))
                $pt_panel_2_final_results = $rss->response;
            if($rss->field_id == Field::idByUID('PT Panel 3 Test 1 Results'))
                $pt_panel_3_test_1_results = $rss->response;
            if($rss->field_id == Field::idByUID('PT Panel 3 Test 2 Results'))
                $pt_panel_3_test_2_results = $rss->response;
            if($rss->field_id == Field::idByUID('PT Panel 3 Test 3 Results'))
                $pt_panel_3_test_3_results = $rss->response;
            if($rss->field_id == Field::idByUID('PT Panel 3 Final Results'))
                $pt_panel_3_final_results = $rss->response;
            if($rss->field_id == Field::idByUID('PT Panel 4 Test 1 Results'))
                $pt_panel_4_test_1_results = $rss->response;
            if($rss->field_id == Field::idByUID('PT Panel 4 Test 2 Results'))
                $pt_panel_4_test_2_results = $rss->response;
            if($rss->field_id == Field::idByUID('PT Panel 4 Test 3 Results'))
                $pt_panel_4_test_3_results = $rss->response;
            if($rss->field_id == Field::idByUID('PT Panel 4 Final Results'))
                $pt_panel_4_final_results = $rss->response;
            if($rss->field_id == Field::idByUID('PT Panel 5 Test 1 Results'))
                $pt_panel_5_test_1_results = $rss->response;
            if($rss->field_id == Field::idByUID('PT Panel 5 Test 2 Results'))
                $pt_panel_5_test_2_results = $rss->response;
            if($rss->field_id == Field::idByUID('PT Panel 5 Test 3 Results'))
                $pt_panel_5_test_3_results = $rss->response;
            if($rss->field_id == Field::idByUID('PT Panel 5 Final Results'))
                $pt_panel_5_final_results = $rss->response;
            if($rss->field_id == Field::idByUID('PT Panel 6 Test 1 Results'))
                $pt_panel_6_test_1_results = $rss->response;
            if($rss->field_id == Field::idByUID('PT Panel 6 Test 2 Results'))
                $pt_panel_6_test_2_results = $rss->response;
            if($rss->field_id == Field::idByUID('PT Panel 6 Test 3 Results'))
                $pt_panel_6_test_3_results = $rss->response;
            if($rss->field_id == Field::idByUID('PT Panel 6 Final Results'))
                $pt_panel_6_final_results = $rss->response;
        }
        //  Fetch expected results
        
        $dev_from_procedure = $this->check_dates($date_pt_panel_received, $date_pt_panel_constituted, $date_pt_panel_tested);
        $incomplete_other_info = $this->check_other_info($date_pt_panel_received, $date_pt_panel_constituted, $date_pt_panel_tested);
        $incomplete_kit_info = $this->check_kit_info($test_1_kit_name, $test_2_kit_name, $test_1_kit_lot_no, $test_2_kit_lot_no, $test_1_expiry_date, $test_2_expiry_date);
        $use_of_expired_kits = $this->check_expiry($date_pt_panel_tested, $test_1_expiry_date, $test_2_expiry_date, $test_3_expiry_date);
        $incomplete_results = $this->check_complete_results($pt_panel_1_test_1_results, $pt_panel_1_final_results, $pt_panel_2_test_1_results, $pt_panel_2_final_results, $pt_panel_3_test_1_results, $pt_panel_3_final_results, $pt_panel_4_test_1_results, $pt_panel_4_final_results, $pt_panel_5_test_1_results, $pt_panel_5_final_results, $pt_panel_6_test_1_results, $pt_panel_6_final_results);
        $incorrect_results = $this->check_correct_results($pt_panel_1_final_results, $pt_panel_2_final_results, $pt_panel_3_final_results, $pt_panel_4_final_results, $pt_panel_5_final_results, $pt_panel_6_final_results, $expected);
        $unsatisfactory = $this->check_satisfaction($incorrect_results);
        $invalid = $this->check_validity($pt_panel_1_test_1_results, $pt_panel_1_test_2_results, $pt_panel_1_test_3_results, $pt_panel_1_final_results, $pt_panel_2_test_1_results, $pt_panel_2_test_2_results, $pt_panel_2_test_3_results, $pt_panel_2_final_results, $pt_panel_3_test_1_results, $pt_panel_3_test_2_results, $pt_panel_3_test_3_results, $pt_panel_3_final_results, $pt_panel_4_test_1_results, $pt_panel_4_test_2_results, $pt_panel_4_test_3_results, $pt_panel_4_final_results, $pt_panel_5_test_1_results, $pt_panel_5_test_2_results, $pt_panel_5_test_3_results, $pt_panel_5_final_results, $pt_panel_6_test_1_results, $pt_panel_6_test_2_results, $pt_panel_6_test_3_results, $pt_panel_6_final_results);
        $wrong_algorithm = $this->check_algorithm($pt_panel_1_test_1_results, $pt_panel_1_test_2_results, $pt_panel_1_test_3_results, $pt_panel_1_final_results, $pt_panel_2_test_1_results, $pt_panel_2_test_2_results, $pt_panel_2_test_3_results, $pt_panel_2_final_results, $pt_panel_3_test_1_results, $pt_panel_3_test_2_results, $pt_panel_3_test_3_results, $pt_panel_3_final_results, $pt_panel_4_test_1_results, $pt_panel_4_test_2_results, $pt_panel_4_test_3_results, $pt_panel_4_final_results, $pt_panel_5_test_1_results, $pt_panel_5_test_2_results, $pt_panel_5_test_3_results, $pt_panel_5_final_results, $pt_panel_6_test_1_results, $pt_panel_6_test_2_results, $pt_panel_6_test_3_results, $pt_panel_6_final_results);
        $overall = $this->check_overall($dev_from_procedure, $incomplete_other_info, $incomplete_kit_info, $use_of_expired_kits, $incomplete_results, $incorrect_results, $unsatisfactory, $invalid, $wrong_algorithm);
        //  Update PT with the outcome of the algorithm.
        $pt->dev_from_procedure = $dev_from_procedure;
        $pt->incomplete_other_info = $incomplete_other_info;
        $pt->incomplete_kit_data = $incomplete_kit_info;
        $pt->use_of_expired_kits = $use_of_expired_kits;
        $pt->incomplete_results = $incomplete_results;
        $pt->incorrect_results = $incorrect_results;
        $pt->panel_result = $unsatisfactory;
        $pt->invalid = $invalid_results;
        $pt->wrong_algorithm = $wrong_algorithm;
        $pt->feedback = $overall;
        $pt->panel_status = Pt::CHECKED;
        $pt->save();
        return response()->json($pt);
    }

    /**
     * Fetch pt with related components for editing
     *
     * @param ID of the selected pt -  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pt = Pt::find($id);
        $results = $pt->results;
        $response = [
            'pt' => $pt,
            'results' => $results
        ];

        return response()->json($response);
    }
    /*
    verify the result after reviewing
    */
   public function verify($id)
    {
        $user_id = Auth::user()->id;

        $result = Pt::find($id);
        $result->verified_by = $user_id;
        $result->panel_status = Pt::CHECKED;
        $result->save();

        return response()->json($result);
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
    /**
     * Begin algorithm to mark test results
     */
    /**
     * Function to check dates - received, constituted, tested
     *
     * @param  $date_pt_panel_received, $date_consituted, $date_pt_panel_tested
     * @return Deviation from procedure.
     */
     public function check_dates($date_pt_panel_received, $date_pt_panel_constituted, $date_pt_panel_tested)
     {
         // Check Dates
         $dev_from_procedure = 0;
         $dt_constituted = Carbon::parse($date_pt_panel_constituted);
         $dt_tested = Carbon::parse($date_pt_panel_tested);
         if($date_pt_panel_tested == $date_pt_panel_constituted || $dt_constituted->diffInDays($dt_tested) == 1)
            $dev_from_procedure = 1;
         return $dev_from_procedure;
     }
    /**
     * Function to check other info - received, constituted, tested
     *
     * @param  $date_pt_panel_received, $date_consituted, $date_pt_panel_tested
     * @return Deviation from procedure.
     */
     public function check_other_info($date_pt_panel_received, $date_pt_panel_constituted, $date_pt_panel_tested)
     {
         // Check Dates
         $incomplete_other_info = 0;
         if(empty($date_pt_panel_tested) ||empty($date_pt_panel_constituted) || empty($dt_tested))
            $incomplete_other_info = 1;
         return $incomplete_other_info;
     }
    /**
     * Function to completeness of HIV test kits info
     *
     * @param  $test_1_kit_name, $test_2_kit_name, $test_1_kit_lot_no, $test_2_kit_lot_no, $test_1_expiry_date, $test_2_expiry_date
     * @return Incomplete kit data.
     */
     public function check_kit_info($test_1_kit_name, $test_2_kit_name, $test_1_kit_lot_no, $test_2_kit_lot_no, $test_1_expiry_date, $test_2_expiry_date)
     {
         // Check kit info
         $incomplete_kit_info = 0;
         if(empty($test_1_kit_name) || empty($test_2_kit_name) || empty($test_1_kit_lot_no) || empty($test_2_kit_lot_no) || empty($test_1_expiry_date) || empty($test_2_expiry_date))
             $incomplete_kit_info = 1;
         return $incomplete_kit_info;
     }
    /**
     * Function to check kit expiry against date tested
     *
     * @param  $date_pt_panel_tested, $date_consituted, $date_pt_panel_tested
     * @return Deviation from procedure.
     */
     public function check_expiry($date_pt_panel_tested, $test_1_expiry_date, $test_2_expiry_date, $test_3_expiry_date)
     {
         $use_of_expired_kits = 0;
         $dt_tested = Carbon::parse($date_pt_panel_tested);
         $dt_1_expiry = Carbon::parse($test_1_expiry_date);
         $dt_2_expiry = Carbon::parse($test_2_expiry_date);
         $dt_3_expiry = Carbon::parse($test_3_expiry_date);
         if($dt_tested->gt($dt_1_expiry) || $dt_tested->gt($dt_2_expiry) || $dt_tested->gt($dt_3_expiry))
            $use_of_expired_kits = 1;
         return $use_of_expired_kits;
     }
    /**
     * Function to check completeness of results
     *
     * @param  Test results, Final results
     * @return Incomplete Results.
     */
     public function check_complete_results($pt_panel_1_test_1_results, $pt_panel_1_final_results, $pt_panel_2_test_1_results, $pt_panel_2_final_results, $pt_panel_3_test_1_results, $pt_panel_3_final_results, $pt_panel_4_test_1_results, $pt_panel_4_final_results, $pt_panel_5_test_1_results, $pt_panel_5_final_results, $pt_panel_6_test_1_results, $pt_panel_6_final_results)
     {
         $incomplete_results = 1;
         $reactive = Option::idByTitle('Reactive');
         $non_reactive = Option::idByTitle('Non-Reactive');
         $not_done = Option::idByTitle('Not Done');
         if(
             ((($pt_panel_1_test_1_results == $reactive) || ($pt_panel_1_final_results == $non_reactive)) && (empty($pt_panel_1_final_results) || ($pt_panel_1_final_results == $not_done))) ||
             ((($pt_panel_2_test_1_results == $reactive) || ($pt_panel_2_final_results == $non_reactive)) && (empty($pt_panel_2_final_results) || ($pt_panel_2_final_results == $not_done))) ||
             ((($pt_panel_3_test_1_results == $reactive) || ($pt_panel_3_final_results == $non_reactive)) && (empty($pt_panel_3_final_results) || ($pt_panel_3_final_results == $not_done))) ||
             ((($pt_panel_4_test_1_results == $reactive) || ($pt_panel_4_final_results == $non_reactive)) && (empty($pt_panel_4_final_results) || ($pt_panel_4_final_results == $not_done))) ||
             ((($pt_panel_5_test_1_results == $reactive) || ($pt_panel_5_final_results == $non_reactive)) && (empty($pt_panel_5_final_results) || ($pt_panel_5_final_results == $not_done))) ||
             ((($pt_panel_6_test_1_results == $reactive) || ($pt_panel_6_final_results == $non_reactive)) && (empty($pt_panel_6_final_results) || ($pt_panel_6_final_results == $not_done)))
         )
            $incomplete_results = 1;
         return $incomplete_results;
     }
    /**
     * Function to check correctness of results
     *
     * @param  $date_pt_panel_received, $date_consituted, $date_pt_panel_tested
     * @return Incorrect results.
     */
     public function check_correct_results($pt_panel_1_final_results, $pt_panel_2_final_results, $pt_panel_3_final_results, $pt_panel_4_final_results, $pt_panel_5_final_results, $pt_panel_6_final_results, $expected)
     {
         // Check correctness
         $incorrect_results = 1;
         $indeterminate = Option::idByTitle('Indeterminate');
         if(
             ($pt_panel_1_final_results == $expected->pt1 || $pt_panel_1_final_results == $indeterminate) &&
             ($pt_panel_2_final_results == $expected->pt2 || $pt_panel_2_final_results == $indeterminate) &&
             ($pt_panel_3_final_results == $expected->pt3 || $pt_panel_3_final_results == $indeterminate) &&
             ($pt_panel_4_final_results == $expected->pt4 || $pt_panel_4_final_results == $indeterminate) &&
             ($pt_panel_5_final_results == $expected->pt5 || $pt_panel_5_final_results == $indeterminate) &&
             ($pt_panel_6_final_results == $expected->pt6 || $pt_panel_6_final_results == $indeterminate)
         )
            $incorrect_results = 0;
         return $incorrect_results;
     }
    /**
     * Function to check if results satisfactory
     *
     * @param  $incorrect_results
     * @return Unsatisfactory results.
     */
     public function check_satisfaction($incorrect_results)
     {
         $unsatisfactory = 0;
         if($incorrect_results == 1)
            $unsatisfactory = 1;
         return $unsatisfactory;
     }
    /**
     * Function to check if results are valid
     *
     * @param  Test results
     * @return Invalid results.
     */
     public function check_validity($pt_panel_1_test_1_results, $pt_panel_1_test_2_results, $pt_panel_1_test_3_results, $pt_panel_1_final_results, $pt_panel_2_test_1_results, $pt_panel_2_test_2_results, $pt_panel_2_test_3_results, $pt_panel_2_final_results, $pt_panel_3_test_1_results, $pt_panel_3_test_2_results, $pt_panel_3_test_3_results, $pt_panel_3_final_results, $pt_panel_4_test_1_results, $pt_panel_4_test_2_results, $pt_panel_4_test_3_results, $pt_panel_4_final_results, $pt_panel_5_test_1_results, $pt_panel_5_test_2_results, $pt_panel_5_test_3_results, $pt_panel_5_final_results, $pt_panel_6_test_1_results, $pt_panel_6_test_2_results, $pt_panel_6_test_3_results, $pt_panel_6_final_results)
     {
         $invalid = 0;
         $invalid = Option::idByTitle('Invalid');
         if(
             ($pt_panel_1_test_1_results == $invalid || $pt_panel_1_test_2_results == $invalid || $pt_panel_1_test_3_results == $invalid || $pt_panel_1_final_results == $invalid) || 
             ($pt_panel_2_test_1_results == $invalid || $pt_panel_2_test_2_results == $invalid || $pt_panel_2_test_3_results == $invalid || $pt_panel_2_final_results == $invalid) || 
             ($pt_panel_3_test_1_results == $invalid || $pt_panel_3_test_2_results == $invalid || $pt_panel_3_test_3_results == $invalid || $pt_panel_3_final_results == $invalid) || 
             ($pt_panel_4_test_1_results == $invalid || $pt_panel_4_test_2_results == $invalid || $pt_panel_4_test_3_results == $invalid || $pt_panel_4_final_results == $invalid) || 
             ($pt_panel_5_test_1_results == $invalid || $pt_panel_5_test_2_results == $invalid || $pt_panel_5_test_3_results == $invalid || $pt_panel_5_final_results == $invalid) || 
             ($pt_panel_6_test_1_results == $invalid || $pt_panel_6_test_2_results == $invalid || $pt_panel_6_test_3_results == $invalid || $pt_panel_6_final_results == $invalid)
        )
            $invalid = 1;
         return $invalid;
     }
    /**
     * Function to check if algorithm followed
     *
     * @param  Test results
     * @return Wrong algorithm.
     */
     public function check_algorithm($pt_panel_1_test_1_results, $pt_panel_1_test_2_results, $pt_panel_1_test_3_results, $pt_panel_1_final_results, $pt_panel_2_test_1_results, $pt_panel_2_test_2_results, $pt_panel_2_test_3_results, $pt_panel_2_final_results, $pt_panel_3_test_1_results, $pt_panel_3_test_2_results, $pt_panel_3_test_3_results, $pt_panel_3_final_results, $pt_panel_4_test_1_results, $pt_panel_4_test_2_results, $pt_panel_4_test_3_results, $pt_panel_4_final_results, $pt_panel_5_test_1_results, $pt_panel_5_test_2_results, $pt_panel_5_test_3_results, $pt_panel_5_final_results, $pt_panel_6_test_1_results, $pt_panel_6_test_2_results, $pt_panel_6_test_3_results, $pt_panel_6_final_results)
     {
         $wrong_algorithm = 0;
         $reactive = Option::idByTitle('Reactive');
         $non_reactive = Option::idByTitle('Non-Reactive');
         $not_done = Option::idByTitle('Not Done');
         if(
             ($pt_panel_1_test_1_results == $non_reactive && $pt_panel_1_test_2_results == $not_done) || ($pt_panel_1_test_1_results == $reactive && ($pt_panel_1_test_2_results == $non_reactive || $pt_panel_1_test_2_results == $reactive)) || 
             ($pt_panel_2_test_1_results == $non_reactive && $pt_panel_2_test_2_results == $not_done) || ($pt_panel_2_test_1_results == $reactive && ($pt_panel_2_test_2_results == $non_reactive || $pt_panel_2_test_2_results == $reactive)) || 
             ($pt_panel_3_test_1_results == $non_reactive && $pt_panel_3_test_2_results == $not_done) || ($pt_panel_3_test_1_results == $reactive && ($pt_panel_3_test_2_results == $non_reactive || $pt_panel_3_test_2_results == $reactive)) || 
             ($pt_panel_4_test_1_results == $non_reactive && $pt_panel_4_test_2_results == $not_done) || ($pt_panel_4_test_1_results == $reactive && ($pt_panel_4_test_2_results == $non_reactive || $pt_panel_4_test_2_results == $reactive)) || 
             ($pt_panel_5_test_1_results == $non_reactive && $pt_panel_5_test_2_results == $not_done) || ($pt_panel_5_test_1_results == $reactive && ($pt_panel_5_test_2_results == $non_reactive || $pt_panel_5_test_2_results == $reactive)) || 
             ($pt_panel_6_test_1_results == $non_reactive && $pt_panel_6_test_2_results == $not_done) || ($pt_panel_6_test_1_results == $reactive && ($pt_panel_6_test_2_results == $non_reactive || $pt_panel_6_test_2_results == $reactive))
        )
            $wrong_algorithm = 1;
         return $wrong_algorithm;
     }
    /**
     * Function to set overall result - satisfactory/unsatisfactory
     *
     * @param  $dev_from_procedure, $incomplete_other_info, $incomplete_kit_info, $use_of_expired_kits, $incomplete_results, $incorrect_results, $unsatisfactory, $invalid, $wrong_algorithm
     * @return Unsatisfactory results.
     */
     public function check_overall($dev_from_procedure, $incomplete_other_info, $incomplete_kit_info, $use_of_expired_kits, $incomplete_results, $incorrect_results, $unsatisfactory, $invalid, $wrong_algorithm)
     {
         $overall = 0;
         if($dev_from_procedure == 0 && $incomplete_other_info == 0 && $incomplete_kit_info == 0 && $use_of_expired_kits == 0 && $incomplete_results == 0 && $incorrect_results == 0 && $unsatisfactory == 0 && $invalid == 0 && $wrong_algorithm)
            $overall = 1;
         return $overall;
     }
}