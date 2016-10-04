<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class ResultRequest extends Request {

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
  		//$id = $this->ingnoreId();
  		return [];
  	}
  	/**
  	* @return \Illuminate\Routing\Route|null|string
  	*/
  	/*public function ingnoreId(){
    		$id = $this->route('expected');
    		$item_id = $this->input('item_id');
        $result = $this->input('result');
        $tested_by = $this->input('tested_by');
    		return Expected::where(compact('id', 'item_id', 'result', 'tested_by'))->exists() ? $id : '';
  	}*/
}
