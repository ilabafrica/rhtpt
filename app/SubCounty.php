<?php namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class SubCounty extends Model
{
  	/**
  	 * Enabling soft deletes for sub-counties.
  	 *
  	 */
  	use SoftDeletes;
  	protected $dates = ['deleted_at'];

  	/**
  	 * The database table used by the model.
  	 *
  	 * @var string
  	 */
  	protected $table = 'sub_counties';
    /**
  	 * Relationship with county.
  	 *
  	 */
     public function county()
     {
       return $this->belongsTo('App\County');
     }
     /**
   	 * Relationship with facilities.
   	 *
   	 */
    public function facilities()
    {
      return $this->hasMany('App\Facility');
    }
    public static function idByName($name=NULL)
    {
        if($name!=NULL)
        {
            try 
            {
                $subCounty = SubCounty::where('name', $name)->orderBy('name', 'asc')->count();
                if($subCounty > 0)
                {
                    $subCounty = SubCounty::where('name', $name)->orderBy('name', 'asc')->first();
                    return $subCounty->id;
                }
                else
                {
                    return null;
                }
            } 
            catch (ModelNotFoundException $e) 
            {
                Log::error("The sub-county ` $name ` does not exist:  ". $e->getMessage());
                //TODO: send email?
                return null;
            }
        }
        else
        {
            return null;
        }
    }    
    /**
    * Get users for a sub-county
    *
    */
    public function users()
    {
        $prole = Role::idByName('Participant');
        $fls = $this->facilities->lists('id')->toArray();
        $users = User::select('users.*')->join('role_user', 'users.id', '=', 'role_user.user_id')->where('role_id', $prole)->whereIn('tier', $fls);
        return $users;
    }        
    /**
    * Get results for a sub-county
    *
    */
    public function results()
    {
        $users = $this->users()->lists('id');
        $enrolments = Enrol::whereIn('user_id', $users)->lists('id');
        $results = Pt::whereIn('enrolment_id', $enrolments);
        return $results;
    }
}