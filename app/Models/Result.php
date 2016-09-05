<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Result extends Model
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
  	protected $table = 'results';
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
          return $this->belongsTo('Pt');
     }
    /**
  	 * Field relationship
  	 *
  	 */
     public function field()
     {
          return $this->belongsTo('Field');
     }
}
