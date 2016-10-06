<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Models\Item;

class ItemRequest extends Request {

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
              'tester_id_range'   => 'required:items,tester_id_range,'.$id,
              'pt_identifier'   => 'required:items,pt_id,'.$id,
              'round'   => 'required:items,round_id,'.$id,
              'material'   => 'required:items,material_id,'.$id,
              'prepared_by'   => 'required:items,prepared_by,'.$id,
          ];
  	}
  	/**
  	* @return \Illuminate\Routing\Route|null|string
  	*/
  	public function ingnoreId(){
    		$id = $this->route('item');
    		$tester_id_range = $this->input('tester_id_range');
        $round_id = $this->input('round');
        $material_id = $this->input('material');
        $prepared_by = $this->input('prepared_by');
    		return Item::where(compact('id', 'tester_id_range', 'round_id', 'material_id', 'prepared_by'))->exists() ? $id : '';
  	}
}
