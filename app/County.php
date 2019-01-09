<?php namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class County extends Model
{
  	/**
  	* Enabling soft deletes for counties.
  	*
  	*/
  	use SoftDeletes;
  	protected $dates = ['deleted_at'];

  	/**
  	* The database table used by the model.
  	*
  	* @var string
  	*/
  	protected $table = 'counties';
    /**
    * Relationship with sub-countis.
    *
    */
    public function subCounties()
    {
       return $this->hasMany('App\SubCounty');
    }
    /**
    * Get facilities for a county
    *
    */
    public function facilities()
    {
        $subs = $this->subCounties()->pluck('id')->toArray();
        return Facility::whereIn('sub_county_id', $subs);
    }    
    /**
    * Relationship with implementing partners.
    *
    */
    public function implementingPartners()
    {
       return $this->belongsToMany('App\ImplementingPartner');
    }

    /**
    * Get users for a county
    *
    */
    public function users($role=null)
    {
        if ($role =='User') {
            $county = [$this->id];
            $county_role = Role::idByName('County Coordinator');

            $subcounty_role = Role::idByName('Sub-County Coordinator');
            $subs = $this->subCounties()->pluck('id')->toArray();
            $users = User::select('users.*')->join('role_user', 'users.id', '=', 'role_user.user_id')
                        ->where(function($query) use ($subcounty_role, $subs){
                            return $query->where('role_id', $subcounty_role)->whereIn('tier', $subs);
                        })->orWhere(function($q) use ($county_role, $county){
                            return $q->where('role_id', $county_role)->whereIn('tier', $county);
                        });
        }else if (is_array($role)){

            $search = $role['search'];
            $prole = Role::idByName('Participant');
            $fls = $this->facilities()->pluck('id')->toArray();
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
            $fls = $this->facilities()->pluck('id')->toArray();
            $users = User::select('users.*')->join('role_user', 'users.id', '=', 'role_user.user_id')->where('role_id', $prole)->whereIn('tier', $fls);
        }        
        return $users->distinct();
    }
    /**
    * Get users enrolled to a round from this county
    *
    */
    public function enrolledUsers($roundID=3)
    {
        $participantRole = Role::idByName('Participant');
        $facilities = $this->facilities()->pluck('id')->toArray();
        $users = User::select('users.*')->join('role_user', 'users.id', '=', 'role_user.user_id')
                    ->join('enrolments', function($join) use ($roundID){
                        $join->on('users.id', '=', 'enrolments.user_id')
                            ->where('enrolments.round_id', '=', $roundID)
                            ->whereNull('enrolments.deleted_at');
                    })
                    ->where('role_user.role_id', $participantRole)->whereIn('role_user.tier', $facilities);

        return $users->distinct();
    }

    /**
    * Get shipments for a county
    *
    */
    public function shipments()
    {
        return $this->hasMany('App\Shipment');
    }
    /**
    * Return the county ID given the name
    *
    */
    public static function idByName($name=NULL)
    {
        if($name!=NULL)
        {
            try 
            {
                $count = County::where('name', $name)->orderBy('name', 'asc')->count();
                if($count > 0)
                {
                    $county = County::where('name', $name)->orderBy('name', 'asc')->first();
                    return $county->id;
                }
                else
                {
                    return null;
                }
            } 
            catch (ModelNotFoundException $e) 
            {
                Log::error("The county ` $name ` does not exist:  ". $e->getMessage());
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
    * Get results for a county
    *
    */
    public function results($search = null, $roundID = 0, $countyID = 0, $subCountyID = 0, $facilityID = 0)
    {
        $users = $this->users($search);

        $enrolments = $users->join('enrolments', 'users.id', '=', 'enrolments.user_id');

        if($roundID > 0) $enrolments = $enrolments->where('enrolments.round_id', $roundID);

        $results = $enrolments->join('pt', 'enrolments.id', '=', 'pt.enrolment_id')
                        ->join('facilities', 'role_user.tier', '=', 'facilities.id')
                        ->join('sub_counties', 'facilities.sub_county_id', '=', 'sub_counties.id')
                        ->join('counties', 'sub_counties.county_id', '=', 'counties.id')
                        ->select(['users.*', 'enrolments.*', 'pt.*', 'role_user.*']);

        if($countyID > 0) $results = $results->where('counties.id', $countyID);
        if($subCountyID > 0) $results = $results->where('sub_counties.id', $subCountyID);
        if($facilityID > 0) $results = $results->where('facilities.id', $facilityID);

        return $results;
    }
}
