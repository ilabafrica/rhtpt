<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Models\Program;

class ProgramRequest extends Request {

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
            'name'   => 'required|unique:programs,name,'.$id,
        ];
	}
	/**
	* @return \Illuminate\Routing\Route|null|string
	*/
	public function ingnoreId(){
		$id = $this->route('program');
    $label = $this->input('label');
		return Program::where(compact('id', 'name'))->exists() ? $id : '';
	}
}
