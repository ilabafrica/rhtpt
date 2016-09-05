<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Models\Material;

class MaterialRequest extends Request {

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
              'batch'   => 'required|unique:materials,batch,'.$id,
              'date_prepared'   => 'required:materials,date_prepared,'.$id,
              'expiry_date'   => 'required:materials,expiry_date,'.$id,
              'material_type'   => 'required:materials,material_type,'.$id,
              'prepared_by'   => 'required:materials,prepared_by,'.$id,
          ];
  	}
  	/**
  	* @return \Illuminate\Routing\Route|null|string
  	*/
  	public function ingnoreId()
    {
    		$id = $this->route('material');
        $batch = $this->input('batch');
    		$date_prepared = $this->input('date_prepared');
    		$expiry_date = $this->input('expiry_date');
        $material_type = $this->input('material_type');
        $prepared_by = $this->input('prepared_by');
    		return Material::where(compact('id', 'batch', 'date_prepared', 'expiry_date', 'material_type', 'prepared_by'))->exists() ? $id : '';
  	}
}
