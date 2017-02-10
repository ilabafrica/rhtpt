<?php namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Expected extends Model
{
  	public $fillable = ['item_id', 'result', 'tested_by', 'user_id'];
  	/**
  	 * Enabling soft deletes for expected results.
  	 *
  	 */
  	use SoftDeletes;
  	protected $dates = ['deleted_at'];

  	/**
  	 * The database table used by the model.
  	 *
  	 * @var string
  	 */
  	protected $table = 'expected_results';
    /**
  	 * Type of result
  	 *
  	 */
  	const NEGATIVE = 1;
  	const POSITIVE = 2;
    /**
  	 * User relationship
  	 *
  	 */
     public function user()
     {
          return $this->belongsTo('App\User', 'tested_by');
     }
    /**
  	 * Item relationship
  	 *
  	 */
     public function item()
     {
          return $this->belongsTo('App\Item');
     }
    /**
  	 * Return readable result
  	 *
  	 */
     public function result($result)
     {
          if($result == Expected::NEGATIVE)
              return 'Negative';
          else
              return 'Positive';
     }
}
