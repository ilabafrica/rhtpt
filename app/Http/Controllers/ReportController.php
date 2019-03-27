<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Option;
use App\Facility;

use DB;
use Excel;
use App;

class ReportController extends Controller
{

    public function manageReport()
    {
        return view('report.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $summaries = DB::select("SELECT r.id, r.description AS 'round', COUNT(DISTINCT e.user_id) AS 'enrolment', COUNT(DISTINCT pt.id) AS 'response', SUM(feedback=0) AS 'satisfactory', SUM(feedback=1) AS 'unsatisfactory' FROM rounds r INNER JOIN enrolments e ON r.id=e.round_id LEFT JOIN pt ON e.id=pt.enrolment_id WHERE ISNULL(r.deleted_at) AND ISNULL(e.deleted_at) GROUP BY r.id");
        $percentiles = DB::select("SELECT r.id, r.description AS 'round', COUNT(DISTINCT e.user_id) AS 'enrolment', COUNT(DISTINCT pt.id) AS 'total_response', concat(round(( COUNT(DISTINCT pt.id)/COUNT(DISTINCT e.user_id) * 100 ),2),'%') AS 'response', concat(round(( SUM(feedback=0)/COUNT(DISTINCT pt.id) * 100 ),2),'%') AS 'satisfactory' FROM rounds r INNER JOIN enrolments e ON r.id=e.round_id LEFT JOIN pt ON e.id=pt.enrolment_id WHERE ISNULL(r.deleted_at) AND ISNULL(e.deleted_at) GROUP BY r.id;");
        $unsperf = DB::select("SELECT r.id, r.description AS 'round', COUNT(DISTINCT pt.id) AS 'response', SUM(feedback=0) AS 'total_unsatisfactory', concat(round(( SUM(feedback=1)/COUNT(DISTINCT pt.id) * 100 ),2),'%') AS 'unsatisfactory', concat(round(( SUM(incomplete_kit_data=1)/SUM(feedback=1) * 100 ),2),'%') AS 'incomplete_kit_data', concat(round(( SUM(incorrect_results=1)/SUM(feedback=1) * 100 ),2),'%') AS 'incorrect_results', concat(round(( SUM(wrong_algorithm=1)/SUM(feedback=1) * 100 ),2),'%') AS 'wrong_algorithm', concat(round(( SUM(dev_from_procedure=1)/SUM(feedback=1) * 100 ),2),'%') AS 'deviation_from_procedure', concat(round(( SUM(incomplete_other_information=1)/SUM(feedback=1) * 100 ),2),'%') AS 'incomplete_other_information', concat(round(( SUM(use_of_expired_kits=1)/SUM(feedback=1) * 100 ),2),'%') AS 'use_of_expired_kits', concat(round(( SUM(invalid_results=1)/SUM(feedback=1) * 100 ),2),'%') AS 'invalid_results', concat(round(( SUM(incomplete_results=1)/SUM(feedback=1) * 100 ),2),'%') AS 'incomplete_results' FROM rounds r INNER JOIN enrolments e ON r.id=e.round_id LEFT JOIN pt ON e.id=pt.enrolment_id WHERE ISNULL(r.deleted_at) AND ISNULL(e.deleted_at) GROUP BY r.id;");
        $summariesChart = [];
        $sumCategories = ['Enrolment', 'Response', 'Satisfactory', 'Unsatisfactory'];
        foreach ($summaries as $content) 
        {
            foreach ($sumCategories as $category) 
            {
                if(strcasecmp("enrolment", $category) == 0)
                    $summariesChart[] = ['round' => $content->round, 'title' => $category, 'total' => (int)$content->enrolment];
                else if(strcasecmp("response", $category) == 0)
                    $summariesChart[] = ['round' => $content->round, 'title' => $category, 'total' => (int)$content->response];
                 else if(strcasecmp("satisfactory", $category) == 0)
                    $summariesChart[] = ['round' => $content->round, 'title' => $category, 'total' => (int)$content->satisfactory];
                 else if(strcasecmp("unsatisfactory", $category) == 0)
                    $summariesChart[] = ['round' => $content->round, 'title' => $category, 'total' => (int)$content->unsatisfactory];
            }
        }
        $percentilesChart = [];
        $perCategories = ['Response', 'Satisfactory'];
        foreach ($percentiles as $content) 
        {
            foreach ($perCategories as $category) 
            {
                if(strcasecmp("response", $category) == 0)
                    $percentilesChart[] = ['round' => $content->round, 'title' => $category, 'total' => (float)$content->response];
                else if(strcasecmp("satisfactory", $category) == 0)
                    $percentilesChart[] = ['round' => $content->round, 'title' => $category, 'total' => (float)$content->satisfactory];
            }
        }
        $unsPerfChart = [];
        $unsPerCategories = ['Incomplete Kit Data', 'Incorrect Results', 'Wrong Algorithm', 'Deviation from Procedure', 'Incomplete Other Information', 'Use of Expired Kits', 'Invalid Results', 'Incomplete Results'];
        foreach ($unsperf as $content) 
        {
            foreach ($unsPerCategories as $category) 
            {
                if(strcasecmp("incomplete kit data", $category) == 0)
                    $unsPerfChart[] = ['round' => $content->round, 'title' => $category, 'total' => (float)$content->incomplete_kit_data];
                else if(strcasecmp("incorrect results", $category) == 0)
                    $unsPerfChart[] = ['round' => $content->round, 'title' => $category, 'total' => (float)$content->incorrect_results];
                else if(strcasecmp("wrong algorithm", $category) == 0)
                    $unsPerfChart[] = ['round' => $content->round, 'title' => $category, 'total' => (float)$content->wrong_algorithm];
                else if(strcasecmp("deviation from procedure", $category) == 0)
                    $unsPerfChart[] = ['round' => $content->round, 'title' => $category, 'total' => (float)$content->deviation_from_procedure];
                else if(strcasecmp("incomplete other information", $category) == 0)
                    $unsPerfChart[] = ['round' => $content->round, 'title' => $category, 'total' => (float)$content->incomplete_other_information];
                else if(strcasecmp("use of expired kits", $category) == 0)
                    $unsPerfChart[] = ['round' => $content->round, 'title' => $category, 'total' => (float)$content->use_of_expired_kits];
                else if(strcasecmp("invalid results", $category) == 0)
                    $unsPerfChart[] = ['round' => $content->round, 'title' => $category, 'total' => (float)$content->invalid_results];
                else if(strcasecmp("incomplete results", $category) == 0)
                    $unsPerfChart[] = ['round' => $content->round, 'title' => $category, 'total' => (float)$content->incomplete_results];
            }
        }

        $response = [
            'summaries' => $summaries,
            'percentiles' => $percentiles,
            'unsperf' => $unsperf,
            'summariesChart' => $summariesChart,
            'percentilesChart' => $percentilesChart,
            'unsPerfChart' => $unsPerfChart
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
            'title' => 'required',
            'description' => 'required',
        ]);

        $create = Option::create($request->all());

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
            'title' => 'required',
            'description' => 'required',
        ]);

        $edit = Option::find($id)->update($request->all());

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
        Option::find($id)->delete();
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
        $option = Option::withTrashed()->find($id)->restore();
        return response()->json(['done']);
    }
    /**
     * Load list of available options
     *
     */
    public function options()
    {
        $options = Option::pluck('title', 'id');
        $response = [];
        foreach($options as $key => $value)
        {
            $response[] = ['id' => $key, 'value' => $value];
        }
        return response()->json($response);
    }
}
$excel = App::make('excel');