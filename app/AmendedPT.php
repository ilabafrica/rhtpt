<?php namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class AmendedPT extends Model
{
  	/**
  	 * Enabling soft deletes for amended_pt.
  	 *
  	 */
  	use SoftDeletes;
  	protected $dates = ['deleted_at'];

  	/**
  	 * The database table used by the model.
  	 *
  	 * @var string
  	 */
  	protected $table = 'amended_pt';

    /**
    *Amended PT status
    *
    **/

    const DELETED = 0; //Don't show this report
    const ACTIVE = 1; // Show both this and the previous report
    const OVER_RIDE = 2; // Show only this report
    
    /**
  	 * Relationship with users.
  	 *
  	 */
     public function amendor()
     {
       return $this->hasOne('App\User', 'amended_by');
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