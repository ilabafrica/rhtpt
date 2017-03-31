<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Questionnaire;
use App\Field;

class QuestionnaireController extends Controller
{

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
    /**
     * Load list of available questionnaires
     *
     */
    public function quest()
    {
        $questionnaires = Questionnaire::lists('title', 'id');
        $quest = [];
        foreach($questionnaires as $key => $value)
        {
            $quest[] = ['id' => $key, 'value' => $value];
        }
        return response()->json($quest);
    }
}