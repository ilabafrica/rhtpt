<?php namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Designation extends Model
{
	public $fillable = ['name','description'];
  	/**
  	 * Enabling soft deletes for designations.
  	 *
  	 */
  	use SoftDeletes;
  	protected $dates = ['deleted_at'];

  	/**
  	 * The database table used by the model.
  	 *
  	 * @var string
  	 */
  	protected $table = 'designations';
    /**
	* Return designation ID given the name
	* @param $title the name of the designationdesignations
	*/
	public static function idByTitle($title=NULL)
	{
		if($title!=NULL)
        {
            try 
            {
                $count = Designation::where('name', $title)->orderBy('name', 'asc')->count();
                if($count > 0)
                {
                    $designation = Designation::where('name', $title)->orderBy('name', 'asc')->first();
                    return $designation->id;
                }
                else
                {
                    return null;
                }
            } 
            catch (ModelNotFoundException $e) 
			{
				Log::error("The designation ` $title ` does not exist:  ". $e->getMessage());
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