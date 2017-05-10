<?php namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Shipment extends Model
{
	public $fillable = ['round_id', 'date_prepared', 'date_shipped', 'tracker', 'shipper_id', 'shipping_method', 'county_id', 'panels_shipped', 'user_id'];
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
  	 * County relationship
  	 *
  	 */
     public function county()
     {
          return $this->belongsTo('App\County');
     }
    /**
  	 * Round relationship
  	 *
  	 */
     public function round()
     {
          return $this->belongsTo('App\Round');
     }
    /**
  	 * Shipper relationship
  	 *
  	 */
     public function shipper()
     {
          return $this->belongsTo('App\Shipper', 'shipper_id');
     }
    /**
  	 * Readable shipping method
  	 *
  	 */
     public function shipping($method)
     {
        $sp = new Shipper;
        return $sp->shipper($method);
     }
    /**
  	 * Consignments relationship
  	 *
  	 */
     public function consignments()
     {
          return $this->hasMany('App\Consignment', 'shipment_id');
     }
}
