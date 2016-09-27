<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
class FieldSet extends Model
{
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
  	  return $this->hasMany('App\Models\Field');
  	}
    /**
  	 * Return field ordering
  	 */
  	public function order($ordr = 0)
  	{
        if($ordr!=0)
            return FieldSet::find($ordr)->first()->label;
        else
            return 'Not Applicable';
  	}
}
