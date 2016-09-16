<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Shipper extends Model
{
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
  	const COURIER = 0;
  	const PARTNER = 1;
    const COUNTY_LAB_COORDINATOR = 2;
    const OTHER = 3;
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
}
