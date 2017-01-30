<?php namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Item extends Model
{
  	/**
  	 * Enabling soft deletes for items.
  	 *
  	 */
  	use SoftDeletes;
  	protected $dates = ['deleted_at'];

  	/**
  	 * The database table used by the model.
  	 *
  	 * @var string
  	 */
  	protected $table = 'items';
    /**
  	 * User relationship
  	 *
  	 */
     public function user()
     {
          return $this->belongsTo('App\Models\User', 'prepared_by');
     }
    /**
  	 * Program relationship
  	 *
  	 */
     public function program()
     {
          return $this->belongsTo('App\Models\Program');
     }
    /**
  	 * Round relationship
  	 *
  	 */
     public function round()
     {
          return $this->belongsTo('App\Models\Round');
     }
    /**
  	 * Material relationship
  	 *
  	 */
     public function material()
     {
          return $this->belongsTo('App\Models\Material');
     }
}
