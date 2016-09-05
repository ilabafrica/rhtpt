<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Models\Receipt;

class ReceiptRequest extends Request {

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
              'round'   => 'required:shipments,round_id,'.$id,
              'date_prepared'   => 'required:shipments,date_prepared,'.$id,
              'date_shipped'   => 'required:shipments,date_shipped,'.$id,
              'shipping_method'   => 'required:shipments,shipping_method,'.$id,
              'courier'   => 'required:shipments,courier,'.$id,
              'participant'   => 'required:shipments,participant,'.$id,
              'panels_shipped'   => 'required:shipments,panels_shipped,'.$id,
          ];
  	}
  	/**
  	* @return \Illuminate\Routing\Route|null|string
  	*/
  	public function ingnoreId(){
    		$id = $this->route('receipt');
    		$round_id = $this->input('round');
        $date_prepared = $this->input('date_prepared');
        $date_shipped = $this->input('date_shipped');
        $shipping_method = $this->input('shipping_method');
        $courier = $this->input('courier');
        $participant = $this->input('participant');
        $panels_shipped = $this->input('panels_shipped');
    		return Receipt::where(compact('id', 'round_id', 'date_prepared', 'date_shipped', 'shipping_method', 'courier', 'participant', 'panels_shipped'))->exists() ? $id : '';
  	}
}
