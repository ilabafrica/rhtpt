<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Shipment extends Model
{
  	/**
  	 * Enabling soft deletes for shipments.
  	 *
  	 */
  	use SoftDeletes;
  	protected $dates = ['deleted_at'];

  	/**
  	 * The database table used by the model.
  	 *
  	 * @var string
  	 */
  	protected $table = 'shipments';
    /**
  	 * Shipment relationship
  	 *
  	 */
     public function shipment()
     {
          return $this->belongsTo('Shipment');
     }
}
