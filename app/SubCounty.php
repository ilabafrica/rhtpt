<?php namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class SubCounty extends Model
{
    public $fillable = ['name', 'county_id'];
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
    public function users($role = null)
    {
        if ($role =='User') {
            
            $subcounty_role = Role::idByName('Sub-County Coordinator');
            $subs = [$this->id];
            $users = User::select('users.*')->join('role_user', 'users.id', '=', 'role_user.user_id')
                        ->where('role_id', $subcounty_role)->whereIn('tier', $subs);
        }else if (is_array($role)){

            $search = $role['search'];
            $prole = Role::idByName('Participant');
            $fls = $this->facilities->pluck('id')->toArray();
            $users = User::select('users.*')
                         ->where(function($query) use ($search){
                            $query->where('users.name', 'LIKE', "%{$search}%");
                            $query->orWhere('first_name', 'LIKE', "%{$search}%");
                            $query->orWhere('middle_name', 'LIKE', "%{$search}%");
                            $query->orWhere('last_name', 'LIKE', "%{$search}%");
                            $query->orWhere('phone', 'LIKE', "%{$search}%");
                            $query->orWhere('email', 'LIKE', "%{$search}%");
                            $query->orWhere('uid', 'LIKE', "%{$search}%");
                        })
                        ->join('role_user', 'users.id', '=', 'role_user.user_id')->where('role_id', $prole)->whereIn('tier', $fls);
       }else{
            $prole = Role::idByName('Participant');
            $fls = $this->facilities->pluck('id')->toArray();
            $users = User::select('users.*')->join('role_user', 'users.id', '=', 'role_user.user_id')->where('role_id', $prole)->whereIn('tier', $fls);
          }
        return $users;
    }       
    /**
    * Get results for a sub-county
    *
    */
    public function results($search=null)
    {
        // $users = $this->users()->pluck('id');
      if($search){
            $users = $this->users($search)->pluck('id');

        }else{
            $users = $this->users()->pluck('id');

        }
        $enrolments = Enrol::whereIn('user_id', $users)->pluck('id');
        $results = Pt::whereIn('enrolment_id', $enrolments);
        return $results;
    }

    /**
    * Get users enrolled to a round from this sub-county
    *
    */
    public function enrolledUsers($roundID=3)
    {
      $prole = Role::idByName('Participant');
      $fls = $this->facilities()->pluck('id')->toArray();
      $users = User::select('users.*')->join('role_user', 'users.id', '=', 'role_user.user_id')
                      ->join('enrolments', function($join) use ($roundID){
                          $join->on('users.id', '=', 'enrolments.user_id')
                              ->where('enrolments.round_id', '=', $roundID)
                              ->whereNull('enrolments.deleted_at');
                      })
                      ->where('role_user.role_id', $prole)->whereIn('role_user.tier', $fls);

        return $users->distinct();
    }

}