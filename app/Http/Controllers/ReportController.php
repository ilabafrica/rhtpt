<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Option;

use DB;

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
        $summaries = DB::select("SELECT r.id, r.description AS 'round', COUNT(e.round_id) AS 'enrolment', COUNT(pt.id) AS 'response', SUM(feedback=0) AS 'satisfactory', SUM(feedback=1) AS 'unsatisfactory' FROM rounds r, enrolments e, pt AS pt WHERE r.id=e.round_id AND e.id=pt.enrolment_id GROUP BY r.id");
        $percentiles = DB::select("SELECT r.id, r.description AS 'round', COUNT(e.round_id) AS 'enrolment', COUNT(pt.id) AS 'total_response', concat(round(( COUNT(pt.id)/COUNT(e.round_id) * 100 ),2),'%') AS 'response', concat(round(( SUM(feedback=0)/COUNT(pt.id) * 100 ),2),'%') AS 'satisfactory' FROM rounds r, enrolments e, pt as pt WHERE r.id=e.round_id AND e.id=pt.enrolment_id GROUP BY r.id;");
        $unsperf = DB::select("SELECT r.id, r.description AS 'round', COUNT(pt.id) AS 'response', SUM(feedback=0) AS 'total_unsatisfactory', concat(round(( SUM(feedback=1)/COUNT(pt.id) * 100 ),2),'%') AS 'unsatisfactory', concat(round(( SUM(incorrect_results=1)/SUM(feedback=1) * 100 ),2),'%') AS 'incorrect_results', concat(round(( SUM(wrong_algorithm=1)/SUM(feedback=1) * 100 ),2),'%') AS 'wrong_algorithm'  FROM rounds r, enrolments e, pt AS pt WHERE r.id=e.round_id AND e.id=pt.enrolment_id GROUP BY r.id;");
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
        $unsPerCategories = ['Unsatisfactory', 'Incorrect Results', 'Wrong Algorithm'];
        foreach ($unsperf as $content) 
        {
            foreach ($unsPerCategories as $category) 
            {
                if(strcasecmp("unsatisfactory", $category) == 0)
                    $unsPerfChart[] = ['round' => $content->round, 'title' => $category, 'total' => (float)$content->unsatisfactory];
                else if(strcasecmp("incorrect results", $category) == 0)
                    $unsPerfChart[] = ['round' => $content->round, 'title' => $category, 'total' => (float)$content->incorrect_results];
                else if(strcasecmp("wrong algorithm", $category) == 0)
                    $unsPerfChart[] = ['round' => $content->round, 'title' => $category, 'total' => (float)$content->wrong_algorithm];
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
        $options = Option::lists('title', 'id');
        $response = [];
        foreach($options as $key => $value)
        {
            $response[] = ['id' => $key, 'value' => $value];
        }
        return response()->json($response);
    }
}