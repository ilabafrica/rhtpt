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
  	const NOT_CHECKED = 0;
	  const CHECKED = 1;
  	const NOT_VERIFIED = 2;
  	const VERIFIED = 3;
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
  	* Enrolment relationship
  	*
  	*/
    public function enrolment()
    {
        return $this->belongsTo('App\Enrol');
    }
}