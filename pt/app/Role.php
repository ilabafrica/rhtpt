<?php namespace App;

use Zizaco\Entrust\EntrustRole;
use Illuminate\Database\Eloquent\softDeletes;

class Role extends EntrustRole
{
	public $fillable = ['name','description'];
	use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
	/**
    * Function for getting the admin role, currently the first user
    *
    */
    public static function getAdminRole()
    {
        return Role::find(1);
    }
    /**
	* Return Role ID given the name
	* @param $name the name of the role
	*/
	public static function idByName($name=NULL)
	{
		if($name!=NULL)
		{
			try
			{
				$role = Role::where('name', $name)->orderBy('name', 'asc')->firstOrFail();
				return $role->id;
			}
			catch (ModelNotFoundException $e)
			{
				Log::error("The role ` $name ` does not exist:  ". $e->getMessage());
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
