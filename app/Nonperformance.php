<?php namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
class NonPerformance extends Model
{
		public $fillable = ['title', 'description'];
  	/**
  	 * Enabling soft deletes for non-performance.
  	 *
  	 */
  	use SoftDeletes;
  	protected $dates = ['deleted_at'];

  	/**
  	 * The database table used by the model.
  	 *
  	 * @var string
  	 */
  	protected $table = 'nonperformance';
    /**
  	 * Registration relationship
  	 */
  	public function registrations()
  	{
  	  	return $this->hasMany('App\Registration');
  	}
    /**
		* Return reason ID given the name
		* @param $title the name of the reason
		*/
		public static function idByTitle($title=NULL)
		{
				if($title!=NULL)
				{
						try 
						{
								$reason = Nonperformance::where('title', $title)->orderBy('title', 'asc')->firstOrFail();
								return $reason->id;
						} 
						catch (ModelNotFoundException $e) 
						{
								Log::error("The reason ` $title ` does not exist:  ". $e->getMessage());
								//TODO: send email?
								return null;
						}
				}
				else
				{
						return null;
				}
		}
}
