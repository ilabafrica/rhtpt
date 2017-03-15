<?php namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
class Set extends Model
{
		public $fillable = ['title', 'description', 'order', 'questionnaire_id'];
  	/**
  	 * Enabling soft deletes for field sets.
  	 *
  	 */
  	use SoftDeletes;
  	protected $dates = ['deleted_at'];

  	/**
  	 * The database table used by the model.
  	 *
  	 * @var string
  	 */
  	protected $table = 'field_sets';
    /**
  	 * Fields relationship
  	 */
  	public function fields()
  	{
  	  return $this->hasMany('App\Field', 'field_set_id');
  	}
    /**
  	 * Return field ordering
  	 */
  	public function order($ordr = 0)
  	{
        if($ordr!=0)
            return Set::find($ordr)->first()->title;
        else
            return 'Not Applicable';
  	}
}
