<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

use DB;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, CanResetPassword, EntrustUserTrait{
EntrustUserTrait::restore insteadof SoftDeletes;
}
    use Notifiable;
    use SoftDeletes;
    const MALE = 0;
    const FEMALE = 1;

    //  Tester ID ranges
    const ZERO = 0;
    const ONE = 1;
    const TWO = 2;
    const THREE = 3;
    const FOUR = 4;
    const FIVE = 5;
    const SIX = 6;
    const SEVEN = 7;
    const EIGHT = 8;
    const NINE = 9;
    //	Default password
	  const DEFAULT_PASSWORD = 'secret';
    //  Tester Designations
    const NURSE = 1;
    const LABTECH = 2;
    const COUNSELLOR = 3;
    const RCO = 4;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['first_name', 'middle_name', 'last_name', 'name', 'email', 'password', 'username', 'address', 'phone', 'gender', 'designation', 'implementing_partner_id'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];
    public function getIsAdminAttribute()
    {
        return true;
    }
    /**
     * Get the admin user currently the first user
     *
     * @return User model
     */
    public static function getAdminUser()
    {
        return User::find(1);
    }

    /**
  	 * Return lot
  	 */
  	public static function lot($round, $tester)
  	{
        $last = substr($tester, -1);
        $lot = Lot::where('round_id', $round)->where('tester_id', 'LIKE', '%'.$last.'%')->first();
        return $lot;
  	}
    /**
	  * role-user
	  *
	  * @return RoleUserTier object
	  */
  	public function ru()
  	{
        $res = DB::table('role_user')->where('user_id', $this->id)->first();
        return $res;
  	}
    /**
  	 * Return tester-id-range
  	 */
  	public function idRange($id)
  	{
          $last = substr($id, -1);
          if($last == 0 || $last == 1 || $last == 2)
                return User::ZERO_TO_TWO;
          else if($last == 3 || $last == 4 || $last == 5)
                return User::THREE_TO_FIVE;
          else if($last == 6 || $last == 7 || $last == 8)
                return User::SIX_TO_EIGHT;
          else if($last == 9)
                return User::NINE;
  	}
    /**
	* Return User ID given the uid
	* @param $uid the unique ID of the field
	*/
	public static function idByUID($uid=NULL)
	{
		if($uid!=NULL)
		{
			try 
			{
				$count = User::where('uid', $uid)->count();
        if($count > 0)
        {
            $user = User::where('uid', $uid)->orderBy('uid', 'asc')->first();
            return $user->id;
        }
        else
        {
            return null;
        }
			} 
			catch (ModelNotFoundException $e) 
			{
				Log::error("The user ` $uid ` does not exist:  ". $e->getMessage());
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
  	 * Return readable gender
  	 */
  	public static function gender($gender)
  	{
  		  if($gender == "Male")
              return USER::MALE;
          else
              return USER::FEMALE;;
  	}
    /**
    * Check if new user...use intended tester lot
    *
    * @return user object
    */
    public function registration()
    {
        return $this->hasOne('App\Registration');
    }

    /**
     * Check if user is County Coordinator
     *
     * @return User model
     */
    public function isCountyCoordinator()
    {
        if($this->hasRole('County Coordinator'))
            return true;
        else
            return false;
    }
    /**
     * Check if user is Sub-County Coordinator
     *
     * @return User model
     */
    public function isSubCountyCoordinator()
    {
        if($this->hasRole('Sub-County Coordinator'))
            return true;
        else
            return false;
    }
    /**
     * Check if user is Facility Incharge
     *
     * @return User model
     */
    public function isFacilityInCharge()
    {
        if($this->hasRole('Facility Incharge'))
            return true;
        else
            return false;
    }
    /**
     * Check if user is Participant
     *
     * @return User model
     */
    public function isParticipant()
    {
        if($this->hasRole('Participant'))
            return true;
        else
            return false;
    }
    /**
     * Check if user is Partner
     *
     * @return User model
     */
    public function isPartner()
    {
        if($this->hasRole('Partner'))
            return true;
        else
            return false;
    }
    /**
     * Check if user is Superadmin
     *
     * @return User model
     */
    public function isSuperAdministrator()
    {
        if($this->hasRole('Superadmin'))
            return true;
        else
            return false;
    }
     /**
    * Get county coordinators
    *
    */
    public function county_coordinators($id = null)
    {
      $role = Role::idByName('County Coordinator'); 

      if ($id) {
        $users = User::select('users.*')->join('role_user', 'users.id', '=', 'role_user.user_id')
                  ->where('role_id', $role)->where('tier', $id);              
      } else{          
        $users = User::select('users.*')->join('role_user', 'users.id', '=', 'role_user.user_id')
                  ->where('role_id', $role); 
      }

        return $users;
    } 
      /**
    * Get subcounty coordinators
    *
    */
    public function sub_county_coordinators($id = null)
    {
      $role = Role::idByName('Sub-County Coordinator');            
      
      if ($id) {
        $users = User::select('users.*')->join('role_user', 'users.id', '=', 'role_user.user_id')
                  ->where('role_id', $role)->where('tier', $id);              
      } else{  
        $users = User::select('users.*')->join('role_user', 'users.id', '=', 'role_user.user_id')
                  ->where('role_id', $role); 
      }
                        
        return $users;
    } 
      /**
    * Get participants 
    *
    */
    public function participants($roundID = 0)
    {
      $role = Role::idByName('Participant');
      if($roundID > 0){
        $users = User::select('users.*')->join('role_user', 'users.id', '=', 'role_user.user_id')
                  ->join('enrolments', 'users.id', '=', 'enrolments.user_id')
                  ->where('role_user.role_id', $role)->where('enrolments.round_id', $roundID)
                  ->whereNull('enrolments.deleted_at');
      }else{
        $users = User::select('users.*')->join('role_user', 'users.id', '=', 'role_user.user_id')
                  ->where('role_id', $role);
      }
                        
        return $users->distinct();
    } 
     /**
    * Get partners 
    *
    */
    public function partners($id = null)
    {
      $role = Role::idByName('Partner'); 

      if ($id) {
        $users = User::select('users.*')->join('role_user', 'users.id', '=', 'role_user.user_id')
                  ->where('role_id', $role)->where('tier', $id);              
      } else{             
        $users = User::select('users.*')->join('role_user', 'users.id', '=', 'role_user.user_id')
                  ->where('role_id', $role); 
      }
      return $users;
    } 
    /**
    * Return User ID given the name
    *
    */
    public static function idByName($name=NULL)
    {
        if($name!=NULL)
        {
            try 
            {
                $count = User::where('name', $name)->count();
                if($count > 0)
                {
                    $user = User::where('name', $name)->orderBy('name', 'asc')->first();
                    return $user->id;
                }
                else
                {
                    return null;
                }
            } 
            catch (ModelNotFoundException $e) 
            {
                Log::error("The user ` $name ` does not exist:  ". $e->getMessage());
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
    * Return User ID given the email
    *
    */
    public static function idByEmail($email=NULL)
    {
        if($email!=NULL)
        {
            try 
            {
                $count = User::where('email', $email)->count();
                if($count > 0)
                {
                    $user = User::where('email', $email)->orderBy('name', 'asc')->first();
                    return $user->id;
                }
                else
                {
                    return null;
                }
            } 
            catch (ModelNotFoundException $e) 
            {
                Log::error("The user with email ` $email ` does not exist:  ". $e->getMessage());
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
    * Return User ID given the phone
    *
    */
    public static function idByPhone($phone=NULL)
    {
        if($phone!=NULL)
        {
            try 
            {
                $count = User::where('phone', $phone)->count();
                if($count > 0)
                {
                    $user = User::where('phone', $phone)->orderBy('name', 'asc')->first();
                    
                    if ($user->role ==2) {
                      return $user->id;
                    }
                    else{
                      return false;
                    }                  
                }
                else
                {
                    return false;
                }
            } 
            catch (ModelNotFoundException $e) 
            {
                Log::error("The user with phone ` $phone ` does not exist:  ". $e->getMessage());
                //TODO: send email?
                return false;
            }
        }
        else
        {
            return false;
        }
    }
    /**
    * Return User ID given the email
    *
    */
    public static function idByUsername($username=NULL)
    {
        if($username!=NULL)
        {
            try 
            {
                $count = User::where('username', $username)->count();
                if($count > 0)
                {
                    $user = User::where('username', $username)->orderBy('name', 'asc')->first();
                    return $user->id;
                }
                else
                {
                    return null;
                }
            } 
            catch (ModelNotFoundException $e) 
            {
                Log::error("The user with username ` $username ` does not exist:  ". $e->getMessage());
                //TODO: send username?
                return null;
            }
        }
        else
        {
            return null;
        }
    }
    /**
    * Enrol relationship
    *
    */
    public function enrol()
    {
        return $this->hasMany('App\Enrol');
    }

    public function implementingPartner()
    {
        return $this->belongsTo('App\ImplementingPartner');
    }
    public function getEmailVerificationUrlAttribute()
    {
        return url('/email/verify/' . $this->verification_code);
    }
    /**
    * Return user designation
    *
    */
    public function designation($des)
    {
        $designation = 'N/A';
        if($des){
            $desig = Designation::find($des);
            if($desig)$designation = $desig->name;
        }
        return $designation;
    }
    /**
     * Return readable gender
     */
    public static function sex($gender)
    {
        if($gender == "Male")
              return User::MALE;
          else
              return User::FEMALE;;
    }

     /**
     * Detach all roles from a user
     *
     * @return object
     */
    public function detachAllRoles()
    {
        DB::table('role_user')->where('user_id', $this->id)->delete();
        return $this;
    }

    public function results($roundID = 0)
    {
        
        $enrolments = $this->join('enrolments', 'users.id', '=', 'enrolments.tester_id')->where('users.id', $this->id);

        if($roundID > 0) $enrolments = $enrolments->where('round_id', $roundID);

        $results = $enrolments->join('pt', 'enrolments.id', '=', 'pt.enrolment_id')
                        ->join('role_user', 'users.id', '=', 'role_user.user_id')
                        ->join('facilities', 'role_user.tier', '=', 'facilities.id')
                        ->join('sub_counties', 'facilities.sub_county_id', '=', 'sub_counties.id')
                        ->join('counties', 'sub_counties.county_id', '=', 'counties.id')
                        ->whereNull('pt.deleted_at')
                        ->select(['users.*', 'enrolments.*', 'pt.*', 'role_user.*']);

        return $results;
    }
    /**
     * Return readable gender
     */
    public static function maleOrFemale($gender)
    {
        if($gender==User::MALE)
            return "Male";
        else
            return "Female";
    }
    //  DMinimum participant ID
    const MIN_UNIQUE_ID = 119607;
    /**
    * Return user designation
    *
    */
    public static function des($des)
    {
        if($des)
        {
            if($des == User::NURSE)
                return "Nurse";
            else if($des == User::LABTECH)
                return "Lab Tech.";
            else if($des == User::COUNSELLOR)
                return "Counsellor";
            else if($des == User::RCO)
                return "RCO";
        }
        return "N/A";
    }
    //  self-registered participants status
    const REJECTED = 1;
}
