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
              'shipment'   => 'required:shipments,shipment_id,'.$id,
              'date_received'   => 'required:shipments,date_received,'.$id,
              'panels_received'   => 'required:shipments,panels_received,'.$id,
              'recipient'   => 'required:shipments,recipient,'.$id,
          ];
  	}
  	/**
  	* @return \Illuminate\Routing\Route|null|string
  	*/
  	public function ingnoreId(){
    		$id = $this->route('receipt');
    		$shipment_id = $this->input('shipment');
        $date_received = $this->input('date_received');
        $panels_received = $this->input('panels_received');
        $recipient = $this->input('recipient');
    		return Receipt::where(compact('id', 'shipment_id', 'date_received', 'panels_received', 'recipient'))->exists() ? $id : '';
  	}
}
