<?php namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Registration extends Model
{
  	/**
  	 * Enabling soft deletes for registration.
  	 *
  	 */
  	use SoftDeletes;
  	protected $dates = ['deleted_at'];

  	/**
  	 * The database table used by the model.
  	 *
  	 * @var string
  	 */
  	protected $table = 'registrations';
    /**
  	 * Relationship with users.
  	 *
  	 */
     public function user()
     {
       return $this->belongsTo('App\User');
     }
}