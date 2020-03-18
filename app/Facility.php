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
    public function users($role = null)
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
    public function results($search = null, $roundID = 0, $countyID = 0, $subCountyID = 0, $facilityID = 0)
    {
        $users = $this->users($search);

        $enrolments = $users->join('enrolments', 'users.id', '=', 'enrolments.tester_id');

        if($roundID > 0) $enrolments = $enrolments->where('round_id', $roundID);

        $results = $enrolments->join('pt', 'enrolments.id', '=', 'pt.enrolment_id')
                        ->join('facilities', 'enrolments.facility_id', '=', 'facilities.id')
                        ->join('sub_counties', 'facilities.sub_county_id', '=', 'sub_counties.id')
                        ->join('counties', 'sub_counties.county_id', '=', 'counties.id')
                        ->whereNull('pt.deleted_at')
                        ->select(['users.*', 'enrolments.*', 'pt.*', 'role_user.*']);

        if($countyID > 0) $results = $results->where('counties.id', $countyID);
        if($subCountyID > 0) $results = $results->where('sub_counties.id', $subCountyID);
        if($facilityID > 0) $results = $results->where('facilities.id', $facilityID);

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