<?php namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Item extends Model
{
  	public $fillable = ['tester_id_range', 'pt_id', 'material_id', 'round_id', 'prepared_by', 'user_id'];
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
          return $this->belongsTo('App\User', 'prepared_by');
     }
    /**
  	 * Program relationship
  	 *
  	 */
     public function program()
     {
          return $this->belongsTo('App\Program');
     }
    /**
  	 * Round relationship
  	 *
  	 */
     public function round()
     {
          return $this->belongsTo('App\Round');
     }
    /**
  	 * Material relationship
  	 *
  	 */
     public function material()
     {
          return $this->belongsTo('App\Material');
     }
}
