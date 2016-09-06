<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class SubCounty extends Model
{
  	/**
  	 * Enabling soft deletes for sub-counties.
  	 *
  	 */
  	use SoftDeletes;
  	protected $dates = ['deleted_at'];

  	/**
  	 * The database table used by the model.
  	 *
  	 * @var string
  	 */
  	protected $table = 'sub_counties';
    /**
  	 * Relationship with county.
  	 *
  	 */
     public function county()
     {
       return $this->belongsTo('App\Models\County');
     }
     /**
   	 * Relationship with facilities.
   	 *
   	 */
    public function facilities()
    {
      return $this->hasMany('App\Models\Facility');
    }
}
