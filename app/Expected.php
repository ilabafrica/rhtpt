<?php namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Expected extends Model
{
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
  	const NEGATIVE = 0;
  	const POSITIVE = 1;
    /**
  	 * User relationship
  	 *
  	 */
     public function user()
     {
          return $this->belongsTo('App\Models\User', 'tested_by');
     }
    /**
  	 * Item relationship
  	 *
  	 */
     public function item()
     {
          return $this->belongsTo('App\Models\Item');
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
