<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Models\Round;

class RoundRequest extends Request {

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
	            'round_name'   => 'required|unique:rounds,round_name,'.$id,
	            'start_date'   => 'required:rounds,start_date,'.$id,
	            'end_date'   => 'required:rounds,end_date,'.$id,
	        ];
		}
		/**
		* @return \Illuminate\Routing\Route|null|string
		*/
		public function ingnoreId(){
			$id = $this->route('round');
			$name = $this->input('round_name');
	    $start_date = $this->input('start_date');
	    $end_date = $this->input('end_date');
			return Round::where(compact('id', 'name', 'start_date', 'end_date'))->exists() ? $id : '';
		}
}
