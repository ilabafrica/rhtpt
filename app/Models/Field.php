<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
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
  	 * Parent-child relationship
  	 */
  	public function children()
  	{
  		return $this->belongsToMany('App\Models\Field', 'field_questions', 'field_id', 'question_id');
  	}
    /**
  	 * Options relationship
  	 */
  	public function options()
  	{
  	  return $this->belongsToMany('App\Models\Option', 'field_options', 'field_id', 'option_id');
  	}
}
