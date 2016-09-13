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
          return $this->belongsTo('App\Models\User', 'recipient');
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
