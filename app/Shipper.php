<?php namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
class Shipper extends Model
{
		public $fillable = ['name', 'shipper_type', 'contact', 'phone', 'email'];
  	/**
  	 * Enabling soft deletes for shippers.
  	 *
  	 */
  	use SoftDeletes;
  	protected $dates = ['deleted_at'];

  	/**
  	 * The database table used by the model.
  	 *
  	 * @var string
  	 */
  	protected $table = 'shippers';
    /**
  	 * Type of shipper
  	 *
  	 */
  	const COURIER = 1;
  	const PARTNER = 2;
    const COUNTY_LAB_COORDINATOR = 3;
    const OTHER = 4;
    /**
    * Return readable shipper-type
    *
    */
    public function shipper($shipper)
    {
       if($shipper == Shipper::COURIER)
           return 'Courier';
       else if($shipper == Shipper::PARTNER)
           return 'Partner';
       else if($shipper == Shipper::COUNTY_LAB_COORDINATOR)
           return 'County Lab Coordinator';
       else if($shipper == Shipper::OTHER)
           return 'Other';
    }
    /**
  	 * Set possible facilities where applicable
  	 */
  	public function setFacilities($field)
    {
      $fieldAdded = array();
  		$shipperId = 0;
  		if(is_array($field)){
  			foreach ($field as $key => $value) {
  				$fieldAdded[] = array(
  					'shipper_id' => (int)$this->id,
  					'facility_id' => (int)$value
  					);
  				$shipperId = (int)$this->id;
  			}
  		}
  		// Delete existing shipper-facility mappings
  		DB::table('shipper_facilities')->where('shipper_id', '=', $shipperId)->delete();
  		// Add the new mapping
  		DB::table('shipper_facilities')->insert($fieldAdded);
  	}
    /**
  	 * Facilities relationship
  	 */
  	public function facilities()
  	{
  	  return $this->belongsToMany('App\Facility', 'shipper_facilities', 'shipper_id', 'facility_id');
  	}
}
