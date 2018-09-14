<?php namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class EvaluatedResult extends Model
{
  	/**
  	 * Enabling soft deletes for pt results.
  	 *
  	 */
  	use SoftDeletes;
  	protected $dates = ['deleted_at'];

  	/**
  	 * The database table used by the model.
  	 *
  	 * @var string
  	 */
  	protected $table = 'evaluated_results';
    /**
  	 * Type of result
  	 *
  	 */
  	const NEGATIVE = 0;
  	const POSITIVE = 1;
    /**
  	 * Pt relationship
  	 *
  	 */
     public function pt()
     {
          return $this->belongsTo('App\Pt');
     }
    /**
  	 * Field relationship
  	 *
  	 */
     public function field()
     {
          return $this->belongsTo('App\Field');
     }
}
