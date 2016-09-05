<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Models\Field;

class FieldRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		$id = $this->ingnoreId();
		return [
            'name'   => 'required|unique:fields,name,'.$id,
            'label'   => 'required:fields,label,'.$id,
            'tag'   => 'required:fields,tag,'.$id,
        ];
	}
	/**
	* @return \Illuminate\Routing\Route|null|string
	*/
	public function ingnoreId(){
		$id = $this->route('field');
		$name = $this->input('name');
    $label = $this->input('label');
    $tag = $this->input('tag');
		return Field::where(compact('id', 'name', 'label', 'tag'))->exists() ? $id : '';
	}
}
