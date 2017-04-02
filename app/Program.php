<?php namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Program extends Model
{
	public $fillable = ['name','description'];
  	/**
  	 * Enabling soft deletes for programs.
  	 *
  	 */
  	use SoftDeletes;
  	protected $dates = ['deleted_at'];

  	/**
  	 * The database table used by the model.
  	 *
  	 * @var string
  	 */
  	protected $table = 'programs';
    /**
	* Return program ID given the name
	* @param $title the name of the program
	*/
	public static function idByTitle($title=NULL)
	{
		if($title!=NULL)
		{
			try 
			{
				$program = Program::where('name', $title)->orderBy('name', 'asc')->firstOrFail();
				return $program->id;
			} 
			catch (ModelNotFoundException $e) 
			{
				Log::error("The program ` $title ` does not exist:  ". $e->getMessage());
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
