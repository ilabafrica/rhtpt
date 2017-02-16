<?php namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Round extends Model
{
	public $fillable = ['name', 'description', 'start_date', 'end_date', 'user_id'];
  	/**
  	 * Enabling soft deletes for rounds.
  	 *
  	 */
  	use SoftDeletes;
  	protected $dates = ['deleted_at'];

  	/**
  	 * The database table used by the model.
  	 *
  	 * @var string
  	 */
  	protected $table = 'rounds';
}
