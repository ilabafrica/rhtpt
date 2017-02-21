<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Questionnaire;
use App\Field;

class QuestionnaireController extends Controller
{

    public function manageQuestionnaire()
    {
        return view('questionnaire.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sets = Set::latest()->paginate(5);

        $response = [
            'pagination' => [
                'total' => $sets->total(),
                'per_page' => $sets->perPage(),
                'current_page' => $sets->currentPage(),
                'last_page' => $sets->lastPage(),
                'from' => $sets->firstItem(),
                'to' => $sets->lastItem()
            ],
            'data' => $sets
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
            'name' => 'required',
            'description' => 'required',
        ]);

        $create = Set::create($request->all());

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
            'name' => 'required',
            'description' => 'required',
        ]);

        $edit = Set::find($id)->update($request->all());

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
        Set::find($id)->delete();
        return response()->json(['done']);
    }

    /**
     * Fetch sets with corresponding fields.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function fetch() 
    {
        $sets = Questionnaire::first()->sets;
        $fields = [];
        $options = [];
        foreach($sets as $set)
        {
            $fields[$set->id] = $set->fields;
            foreach($fields[$set->id] as $field)
            {
                if($field->tag == Field::CHECKBOX || $field->tag == Field::RADIO || $field->tag == Field::SELECT)
                    $options[$field->id] = $field->options->get('id', 'name');
            }
        }
        $response = ["sets" => $sets, "fields" => $fields, "options" => $options];
        return response()->json($response);
    }
}