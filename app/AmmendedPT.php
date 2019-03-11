<?php namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class AmmendedPT extends Model
{
  	/**
  	 * Enabling soft deletes for ammended_pt.
  	 *
  	 */
  	use SoftDeletes;
  	protected $dates = ['deleted_at'];

  	/**
  	 * The database table used by the model.
  	 *
  	 * @var string
  	 */
  	protected $table = 'ammended_pt';

    /**
    *Ammended PT status
    *
    **/

    const DELETED = 0; //Don't show this report
    const ACTIVE = 1; // Show both this and the previous report
    const OVER_RIDE = 2; // Show only this report
    
    /**
  	 * Relationship with users.
  	 *
  	 */
     public function ammendor()
     {
       return $this->hasOne('App\User', 'ammended_by');
     }

     /**
     * Relationship with pt.
     *
     */
     public function pt()
     {
       return $this->hasOne('App\Pt', 'pt_id');
     }
}