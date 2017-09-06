<?php namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Lot extends Model
{
  	public $fillable = ['round_id', 'lot', 'last_char_id', 'user_id'];
  	/**
  	 * Enabling soft deletes for items.
  	 *
  	 */
  	use SoftDeletes;
  	protected $dates = ['deleted_at'];
    /**
  	 * Lot numbers
  	 *
  	 */
  	const ONE = 1;
  	const TWO = 2;
  	const THREE = 3;
  	const FOUR = 4;
  	const FIVE = 5;
  	/**
  	 * The database table used by the model.
  	 *
  	 * @var string
  	 */
  	protected $table = 'lots';
    /**
  	 * User relationship
  	 *
  	 */
     public function user()
     {
          return $this->belongsTo('App\User');
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
     * Panel relationship
     *
     */
     public function panels()
     {
          return $this->hasMany('App\Panel');
     }
    /**
     * Return readable lot numbers
     *
     */
     public function lt()
     {
          if($this->lot == Lot::ONE)
              return "Lot 1";
          else if($this->lot == Lot::TWO)
              return "Lot 2";
          else if($this->lot == Lot::THREE)
              return "Lot 3";
          else if($this->lot == Lot::FOUR)
              return "Lot 4";
          else if($this->lot == Lot::FIVE)
              return "Lot 5";
     }
    /**
     * Function to return total participants in a lot
     *
     */
     public function participants()
     {
          $exploded = explode(", ", $this->tester_id);
          $counter = count($exploded);
          $total = 0;
          for($i = 0; $i < $counter; $i++)
          {
              $users = User::where('uid', 'LIKE', '%'.$exploded[$i])->pluck('id');
              $count = Enrol::whereIn('user_id', $users)->where('round_id', $this->round_id)->count();
              $total += $count;
          }
          return $total;
     }
}