<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Models\Facility;

class FacilityRequest extends Request {

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
            'code'   => 'required|unique:facilities,code,'.$id,
            'name'   => 'required|unique:facilities,name,'.$id,
            'sub_county'   => 'required:facilities,sub_county_id,'.$id,
        ];
	}
	/**
	* @return \Illuminate\Routing\Route|null|string
	*/
	public function ingnoreId(){
		$id = $this->route('facility');
		$code = $this->input('code');
		$name = $this->input('name');
    $sub_county_id = $this->input('sub_county');
		return Facility::where(compact('id', 'code', 'name', 'sub_county_id'))->exists() ? $id : '';
	}
}
