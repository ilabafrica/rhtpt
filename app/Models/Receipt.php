<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Receipt extends Model
{
  	/**
  	 * Enabling soft deletes for sample reception.
  	 *
  	 */
  	use SoftDeletes;
  	protected $dates = ['deleted_at'];
    /**
  	 * Complete/Incomplete
  	 *
  	 */
  	const COMPLETE = 0;
  	const INCOMPLETE = 1;

  	/**
  	 * The database table used by the model.
  	 *
  	 * @var string
  	 */
  	protected $table = 'receipts';
    /**
  	 * user relationship
  	 *
  	 */
     public function receiver()
     {
          return $this->belongsTo('App\Models\Facility', 'recipient');
     }
    /**
  	 * Shipment relationship
  	 *
  	 */
     public function shipment()
     {
          return $this->belongsTo('App\Models\Shipment');
     }
}
