<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Field;

class FieldController extends Controller
{

    public function manageField()
    {
        return view('field.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $fields = Field::latest()->paginate(5);

        foreach($fields as $field)
        {
            $field->tg = $field->tag($field->tag);
            $field->ordr = $field->order($field->order);
        }

        $response = [
            'pagination' => [
                'total' => $fields->total(),
                'per_page' => $fields->perPage(),
                'current_page' => $fields->currentPage(),
                'last_page' => $fields->lastPage(),
                'from' => $fields->firstItem(),
                'to' => $fields->lastItem()
            ],
            'data' => $fields
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
        dd($request->all());
        $this->validate($request, [
            'title' => 'required',
            'uid' => 'required',
            'field_set_id' => 'required',
            'order' => 'required',
            'tag' => 'required',
        ]);

        $create = Field::create($request->all());

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
            'label' => 'required',
            'description' => 'required',
            'order' => 'required',
            'tag' => 'required',
            'options' => 'required',
        ]);

        $edit = Field::find($id)->update($request->all());

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
        Field::find($id)->delete();
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
        $field = Field::withTrashed()->find($id)->restore();
        return response()->json(['done']);
    }
    /**
     * Function to return types of fields.
     *
     */
    public function tags()
    {
        $response = [];
        $data = [
                    Field::CHECKBOX => "Checkbox", 
                    Field::DATE => "Date", 
                    Field::FIELD => "Text Field", 
                    Field::RADIO => "Radio Button", 
                    Field::SELECT => "Select List", 
                    Field::TEXT => "Textarea"
                ];
        foreach($data as $key => $value)
        {
            $response[] = ['id' => $key, 'value' => $value];
        }
        return $response;
    }
    /**
     * Load list of available fields
     *
     */
    public function fields()
    {
        $fields = Field::lists('uid', 'id');
        $response = [];
        foreach($fields as $key => $value)
        {
            $response[] = ['id' => $key, 'value' => $value];
        }
        return response()->json($response);
    }
}