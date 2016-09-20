<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
class Field extends Model
{
  	/**
  	 * Enabling soft deletes for fields.
  	 *
  	 */
  	use SoftDeletes;
  	protected $dates = ['deleted_at'];

  	/**
  	 * The database table used by the model.
  	 *
  	 * @var string
  	 */
  	protected $table = 'fields';
    /**
  	 * Tags - type of field
  	 *
  	 */
  	const CHECKBOX = 0;
  	const DATE = 1;
  	const EMAIL = 2;
    const FIELD = 3;
  	const RADIO = 4;
  	const SELECT = 5;
  	const TEXT = 6;
    /**
  	 * Matrices
  	 *
  	 */
    const TESTS = 0;
   	const RESULTS = 1;
    /**
  	 * HIV TESTS
  	 *
  	 */
    const SCREENING = 0;
   	const CONFIRMATORY = 1;
    const TIE_BREAKER = 2;
    /**
  	 * HIV TEST RESULTS
  	 *
  	 */
    const SCREENING_RESULT = 0;
   	const CONFIRMATORY_RESULT = 1;
    const TIE_BREAKER_RESULT = 2;
    const FINAL_RESULT = 3;
    /**
  	 * Parent-child relationship
  	 */
  	/*public function children()
  	{
  		return $this->belongsToMany('App\Models\Field', 'field_questions', 'field_id', 'question_id');
  	}*/
    /**
  	 * Options relationship
  	 */
  	public function options()
  	{
  	  return $this->belongsToMany('App\Models\Option', 'field_options', 'field_id', 'option_id');
  	}
    /**
  	 * Return field ordering
  	 */
  	public function order($ordr = 0)
  	{
        if($ordr!=0)
            return Field::find($ordr)->first()->label;
        else
            return 'Not Applicable';
  	}
    /**
  	 * Return readable tag
  	 */
  	public function tag($tag)
  	{
  		  if($tag == Field::CHECKBOX)
            return 'Checkbox';
        else if($tag == Field::DATE)
            return 'Date';
        else if($tag == Field::EMAIL)
            return 'E-mail';
        else if($tag == Field::FIELD)
            return 'Text Field';
        else if($tag == Field::RADIO)
            return 'Radio Button';
        else if($tag == Field::SELECT)
            return 'Select List';
        else if($tag == Field::TEXT)
            return 'Free Text';
  	}
    /**
  	 * Set possible responses where applicable
  	 */
  	public function setOptions($field)
    {
      $fieldAdded = array();
  		$fieldId = 0;
  		if(is_array($field)){
  			foreach ($field as $key => $value) {
  				$fieldAdded[] = array(
  					'field_id' => (int)$this->id,
  					'option_id' => (int)$value
  					);
  				$fieldId = (int)$this->id;
  			}
  		}
  		// Delete existing field-option mappings
  		DB::table('field_options')->where('field_id', '=', $fieldId)->delete();
  		// Add the new mapping
  		DB::table('field_options')->insert($fieldAdded);
  	}
}
