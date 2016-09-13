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
  	 * Constants for shipping methods
  	 *
  	 */
     const POST = 0;
     const COURIER = 1;

  	/**
  	 * The database table used by the model.
  	 *
  	 * @var string
  	 */
  	protected $table = 'shipments';
    /**
  	 * Round relationship
  	 *
  	 */
     public function round()
     {
          return $this->belongsTo('App\Models\Round');
     }
    /**
  	 * Participant relationship
  	 *
  	 */
     public function part()
     {
          return $this->belongsTo('App\Models\User', 'participant');
     }
    /**
  	 * Readable shipping method
  	 *
  	 */
     public function shipping($method)
     {
          if($method == Shipment::POST)
              return 'Post';
          else if($method == Shipment::COURIER)
              return 'Courier';
     }
}
