<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Field;

use DB;

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
        $error = ['error' => 'No results found, please try with different keywords.'];
        $fields = Field::latest()->withTrashed()->paginate(5);
        if($request->has('q')) 
        {
            $search = $request->get('q');
            $fields = Field::where('title', 'LIKE', "%{$search}%")->latest()->withTrashed()->paginate(5);
        }

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

        return $fields->count() > 0 ? response()->json($response) : $error;
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
            'uid' => 'required',
            'field_set_id' => 'required',
            'order' => 'required',
            'tag' => 'required',
        ]);
        $fields = Field::where('title', 'LIKE', "{$request->title}")->withTrashed()->get();

        if ($fields->count() > 0) {

            return response()->json('error');

        }else{
            $field = new Field;
            $field->uid = $request->uid;
            $field->title = $request->title;
            $field->field_set_id = $request->field_set_id;
            $field->order = $request->order;
            $field->tag = $request->tag;
            $field->save();
            try
            {
                $field->save();
                if($request->opts)
                {
                    $field->setOptions($request->opt);
                }
                return response()->json($field);
            }
        	catch(QueryException $e)
            {
                Log::error($e);
            }
        }
    }
    /**
     * Fetch pt with related components for editing
     *
     * @param ID of the selected pt -  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $field = Field::find($id);
        $options = [];
        if(count(DB::table('field_options')->where('field_id', $id))>0)
            $options = $field->options->toArray();
        $response = [
            'field' => $field,
            'opts' => $options
        ];

        return response()->json($response);
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
            'uid' => 'required',
            'field_set_id' => 'required',
            'order' => 'required',
            'tag' => 'required',
        ]);

        $field = Field::find($id);
        $field->uid = $request->uid;
        $field->title = $request->title;
        $field->field_set_id = $request->field_set_id;
        $field->order = $request->order;
        $field->tag = $request->tag;
        $field->save();
        try
        {
            $field->save();
            if($request->opts)
            {
                $field->setOptions($request->opt);
            }
            return response()->json($field);
        }
    	catch(QueryException $e)
        {
            Log::error($e);
        }
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
        $fields = Field::pluck('uid', 'id');
        $response = [];
        foreach($fields as $key => $value)
        {
            $response[] = ['id' => $key, 'value' => $value];
        }
        return response()->json($response);
    }
}