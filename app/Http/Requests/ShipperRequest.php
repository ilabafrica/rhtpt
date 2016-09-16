<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Models\Shipper;

class ShipperRequest extends Request {

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
            'name'   => 'required|unique:shippers,name,'.$id,
						'shipper_type'   => 'required:shippers,shipper_type,'.$id,
            'contact'   => 'required:shippers,contact,'.$id,
        ];
	}
	/**
	* @return \Illuminate\Routing\Route|null|string
	*/
	public function ingnoreId(){
		$id = $this->route('shipper');
		$name = $this->input('name');
    $shipper_type = $this->input('shipper_type');
		$contact = $this->input('contact');
		return Shipper::where(compact('id', 'name', 'label', 'shipper_type', 'contact'))->exists() ? $id : '';
	}
}
