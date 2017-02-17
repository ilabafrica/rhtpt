<?php namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Notification extends Model
{
	public $fillable = ['template', 'message'];
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
  	protected $table = 'notifications';
	//  Notification templates
    const PANEL_DISPATCH = 1;
    const RESULTS_RECEIVED = 2;
    const FEEDBACK_READY = 3;
    const OTHER = 4;
}
