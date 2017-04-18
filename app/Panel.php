<?php namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Panel extends Model
{
  	public $fillable = ['lot_id', 'panel', 'material_id', 'result', 'prepared_by', 'tested_by', 'user_id'];
  	/**
  	 * Enabling soft deletes for items.
  	 *
  	 */
  	use SoftDeletes;
  	protected $dates = ['deleted_at'];
	//	Constants for panel IDs
    const S1 = 1;
    const S2 = 2;
	const S3 = 3;
    const S4 = 4;
	const S5 = 5;
    const S6 = 6;
    /**
  	 * Type of result
  	 *
  	 */
  	const NEGATIVE = 1;
  	const POSITIVE = 2;

  	/**
  	 * The database table used by the model.
  	 *
  	 * @var string
  	 */
  	protected $table = 'panels';
    /**
  	 * User relationship
  	 *
  	 */
     public function user()
     {
          return $this->belongsTo('App\User');
	 }
    /**
  	 * Material relationship
  	 *
  	 */
     public function material()
     {
          return $this->belongsTo('App\Material');
     }
    /**
  	 * Lot relationship
  	 *
  	 */
     public function lot()
     {
          return $this->belongsTo('App\Lot');
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