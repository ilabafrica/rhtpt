<?php namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Facility extends Model
{
	public $fillable = ['code', 'name','registration_number','mailing_address', 'in_charge', 'in_charge_phone', 'in_charge_email','sub_county_id'];
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
				$facility = Facility::where('code', $code)->orderBy('name', 'asc')->count();
				if($facility > 0)
				{
					$facility = Facility::where('code', $code)->orderBy('name', 'asc')->first();
					return $facility->id;
				}
				else
				{
					return null;
				}
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
    /**
	* Return Facility ID given the name
	* @param $code the unique mfl code of the facility
	*/
	public static function idByName($name=NULL)
	{
		if($name!=NULL)
		{
			try 
			{
				$facility = Facility::where('name', $name)->orderBy('name', 'asc')->count();
				if($facility > 0)
				{
					$facility = Facility::where('name', $name)->orderBy('name', 'asc')->first();
					return $facility->code;
				}
				else
				{
					return null;
				}
			} 
			catch (ModelNotFoundException $e) 
			{
				Log::error("The facility ` $name ` does not exist:  ". $e->getMessage());
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
  	* Relationship with participant.
  	*
  	*/
    public function users()
    {
   		$prole = Role::idByName('Participant');
        $users = User::select('users.*')->join('role_user', 'users.id', '=', 'role_user.user_id')->where('role_id', $prole)->where('tier', $this->id);
        return $users;
    }
	/**
  	* Relationship with consignments.
  	*
  	*/
    public function consignments()
    {
   		return $this->hasMany('App\Consignment');
    }
    /**
    * Get results for a Facility
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
    * Get users enrolled to a round from this facility
    *
    */
    public function enrolledUsers($roundID=3)
    {
      	$prole = Role::idByName('Participant');
      	$users = User::select('users.*')->join('role_user', 'users.id', '=', 'role_user.user_id')
                    ->join('enrolments', function($join) use ($roundID){
                      	$join->on('users.id', '=', 'enrolments.user_id')
                          	->where('enrolments.round_id', '=', $roundID)
                        	->whereNull('enrolments.deleted_at');
                    })
                    ->where('role_user.role_id', $prole)->where('role_user.tier', $this->id);

        return $users->distinct();
    }

}