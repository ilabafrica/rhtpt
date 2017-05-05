<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Program;

class DashboardController extends Controller
{
    public function manageDash()
    {
        return view('landing');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('landing');
    }
    //  PREPARE SUMMARIES, REPORTS AND CHARTS
    /**
     * Display enrolment by gender chart
     *
     * @return chart
     */
     public function gender()
     {
         $gender = ['Male', 'Female'];
         $response = [];
         foreach($gender as $sex)
         {
              $response[] = ['key' => $sex, 'value' => '300'];
         }
         return response()->json($response);
     }
     /**
     * Display enrolment by program chart
     *
     * @return chart
     */
     public function program()
     {
         $programs = Program::lists('name');
         $response = [];
         foreach($programs as $program)
         {
              $response[] = ['key' => $program, 'value' => '200'];
         }
         return response()->json($response);
         
     }
     /**
     * Display enrolment by region chart
     *
     * @return chart
     */
     public function region()
     {
         
     }
     /**
     * Display summary of enrolment, response and satisfactory counts
     *
     * @return chart
     */
     public function response()
     {
         
     }
     /**
     * Display summary of response and satisfactory rates
     *
     * @return chart
     */
     public function satisfactory()
     {
         
     }
     /**
     * Display summary of response and unsatisfactory rates
     *
     * @return chart
     */
     public function unsatisfactory()
     {
         
     }
}
