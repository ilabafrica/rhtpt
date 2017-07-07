<?php namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Consignment extends Model
{
  	public $fillable = ['shipment_id', 'facility_id', 'tracker', 'total', 'date_picked', 'picked_by', 'contacts'];
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
  	protected $table = 'consignments';
    /**
  	 * Shipment relationship
  	 *
  	 */
     public function shipment()
     {
          return $this->belongsTo('App\Shipment');
     }
    /**
     * Facility relationship
     *
     */
     public function facility()
     {
          return $this->belongsTo('App\Facility');
     }
}
