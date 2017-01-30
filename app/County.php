<?php namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class County extends Model
{
  	/**
  	 * Enabling soft deletes for counties.
  	 *
  	 */
  	use SoftDeletes;
  	protected $dates = ['deleted_at'];

  	/**
  	 * The database table used by the model.
  	 *
  	 * @var string
  	 */
  	protected $table = 'counties';
    /**
  	 * Relationship with sub-countis.
  	 *
  	 */
     public function subCounties()
     {
       return $this->hasMany('App\Models\SubCounty');
     }
}
