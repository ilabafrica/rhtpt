<?php namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
class Questionnaire extends Model
{
		public $fillable = ['title', 'description'];
  	/**
  	 * Enabling soft deletes for forms.
  	 *
  	 */
  	use SoftDeletes;
  	protected $dates = ['deleted_at'];

  	/**
  	 * The database table used by the model.
  	 *
  	 * @var string
  	 */
  	protected $table = 'questionnaires';
    /**
  	 * Sets relationship
  	 */
  	public function sets()
  	{
  	  return $this->hasMany('App\Set');
  	}
}
