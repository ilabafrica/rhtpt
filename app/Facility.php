<?php namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Facility extends Model
{
  	/**
  	 * Enabling soft deletes for facilities.
  	 *
  	 */
  	use SoftDeletes;
  	protected $dates = ['deleted_at'];

  	/**
  	 * The database table used by the model.
  	 *
  	 * @var string
  	 */
  	protected $table = 'facilities';
    /**
  	 * Relationship with sub-countis.
  	 *
  	 */
     public function subCounty()
     {
   		return $this->belongsTo('App\SubCounty');
     }
    /**
	* Return Facility ID given the mfl code
	* @param $code the unique mfl code of the facility
	*/
	public static function idByCode($code=NULL)
	{
		if($code!=NULL)
		{
			try 
			{
				$facility = Facility::where('code', $code)->orderBy('code', 'asc')->firstOrFail();
				return $facility->id;
			} 
			catch (ModelNotFoundException $e) 
			{
				Log::error("The facility ` $code ` does not exist:  ". $e->getMessage());
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
