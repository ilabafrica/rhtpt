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
  	const SATISFACTORY = 0;
  	const UNSATISFACTORY = 1;

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
    /**
    * Check pt outcome
    *
    */
    public function outcome($feedback)
    {
        if($feedback == Pt::SATISFACTORY)
            return 'Satisfactory';
        else
            return 'Unsatisfactory';
    }
    /**
    * Get reason for unsatisfaction
    *
    */
    public function unsatisfactory()
    {
        //  TODO  - Check more than one reason...
        if($this->incorrect_results == 1)
            return 'Incorrect Results';
        else if($this->incomplete_kit_data == 1)
            return 'Incomplete Kit Data';
        else if($this->dev_from_procedure == 1)
            return 'Deviation from Procedure';
        else if($this->incomplete_other_information == 1)
            return 'Incomplete Other Information';
        else if($this->use_of_expired_kits == 1)
            return 'Use of Expired Kits';
        else if($this->invalid_results == 1)
            return 'Invalid Results';
        else if($this->wrong_algorithm == 1)
            return 'Wrong Algorithm';
        else if($this->incomplete_results == 1)
            return 'Incomplete Results';
    }
}