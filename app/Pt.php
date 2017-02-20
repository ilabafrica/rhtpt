<?php namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Pt extends Model
{
  	public $fillable = ['round_id', 'user_id'];
  	/**
  	 * Enabling soft deletes for pt survey.
  	 *
  	 */
  	use SoftDeletes;
  	protected $dates = ['deleted_at'];
	/**
  	 * Status of result
  	 *
  	 */
  	const VERIFIED = 1;
  	const NOT_VERIFIED = 0;
	/**
  	 * Status of result
  	 *
  	 */
  	const SATISFACTORY = 1;
  	const UNSATISFACTORY = 0;

  	/**
  	 * The database table used by the model.
  	 *
  	 * @var string
  	 */
  	protected $table = 'pt';
    /**
  	 * Result relationship
  	 *
  	 */
     public function results()
     {
          return $this->hasMany('App\Result');
     }
    /**
  	 * Tester relationship
  	 *
  	 */
     public function user()
     {
          return $this->belongsTo('App\User');
     }
    /**
  	 * Round relationship
  	 *
  	 */
     public function round()
     {
          return $this->belongsTo('App\Round');
     }
}
