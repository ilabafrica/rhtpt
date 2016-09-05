<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Models\Option;

class OptionRequest extends Request {

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
            'name'   => 'required|unique:options,name,'.$id,
            'label'   => 'required:options,label,'.$id,
        ];
	}
	/**
	* @return \Illuminate\Routing\Route|null|string
	*/
	public function ingnoreId(){
		$id = $this->route('option');
		$name = $this->input('name');
    $label = $this->input('label');
		return Option::where(compact('id', 'name', 'label'))->exists() ? $id : '';
	}
}
