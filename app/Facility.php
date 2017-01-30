<?php namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Facility extends Model
{
  	/**
  	 * Enabling soft deletes for facilities.
  	 *
  	 */
  	use SoftDeletes;
  	protected $dates = ['deleted_at'];

  	/**
  	 * The database table used by the model.
  	 *
  	 * @var string
  	 */
  	protected $table = 'facilities';
    /**
  	 * Relationship with sub-countis.
  	 *
  	 */
     public function subCounty()
     {
       return $this->belongsTo('App\SubCounty');
     }
}
