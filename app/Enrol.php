<?php namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Enrol extends Model
{
  	/**
  	 * Enabling soft deletes for enrolments.
  	 *
  	 */
  	use SoftDeletes;
  	protected $dates = ['deleted_at'];

  	/**
  	 * The database table used by the model.
  	 *
  	 * @var string
  	 */
  	protected $table = 'enrolments';

    /**
    *Enrolment status
    *
    **/

    const DONE =1;
    const NOT_DONE =0;
    /**
  	 * Relationship with users.
  	 *
  	 */
     public function user()
     {
       return $this->belongsTo('App\User');
     }
		 /**
  	 * Relationship with rounds.
  	 *
  	 */
     public function round()
     {
       return $this->belongsTo('App\Round');
     }
     /**
     * Relationship with pt.
     *
     */
     public function pt()
     {
       return $this->hasOne('App\Pt', 'enrolment_id');
     }
}