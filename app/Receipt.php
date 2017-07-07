<?php namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Receipt extends Model
{
  	public $fillable = ['shipment_id', 'date_received', 'panels_received', 'condition', 'receiver'];
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
  	 * Shipment relationship
  	 *
  	 */
     public function shipment()
     {
          return $this->belongsTo('App\Shipment');
     }
}
