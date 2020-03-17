<?php namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\SubCounty;
use App\Facility;
use App\Enrol;
use App\Pt;
class ImplementingPartner extends Model
{
	public $fillable = ['name', 'agency_id'];
	use SoftDeletes;
	protected $dates = ['deleted_at'];

    public function agency()
    {
      return $this->belongsTo('App\Agency');
    }

    public function counties()
    {
      return $this->belongsToMany('App\County');
    }
    /**
    * Get  details for this partner
    *
    */
    public function facilities()
    {
      $counties = $this->counties()->pluck('id')->toArray();

      $subcounties = SubCounty::whereIn('county_id', $counties)->pluck('id');

      $facilities = Facility::whereIn('sub_county_id', $subcounties)->pluck('id');

      $result = array( 'counties'=>$counties, 'subcounties'=>$subcounties, 'facilities'=>$facilities);

      return $result;
    }
    /**
    * Get  facilities for this partner
    *
    */
    public function all_facilities()
    {
        $result = $this->facilities();
        $subs = $result['subcounties'];
        return Facility::whereIn('sub_county_id', $subs);
    }
    /**
    * Get users for an Implementing Partner
    *
    */
    public function users($role = null)
    {

        if ($role =='User') {

            $facilities = $this->facilities();
            $partner = [$this->id];
            $partner_role = Role::idByName('Partner');

            $county = $facilities['counties'];
            $county_role = Role::idByName('County Coordinator');

            $subcounty_role = Role::idByName('Sub-County Coordinator');
            $subs = $facilities['subcounties'];

            $users = User::select('users.*')
                        ->join('role_user', 'users.id', '=', 'role_user.user_id')
                        ->where(function($query) use ($subcounty_role, $subs){
                            return $query->where('role_id', $subcounty_role)->whereIn('tier', $subs);
                        })->orWhere(function($q) use ($county_role, $county){
                            return $q->where('role_id', $county_role)->whereIn('tier', $county);
                        })->orWhere(function($qry) use ($partner_role, $partner){
                            return $qry->where('role_id', $partner_role)->whereIn('tier', $partner);
                        });
        }else if (is_array($role)){

            $search = $role['search'];
            $users = User::select('users.*')
                         ->where(function($query) use ($search){
                            $query->where('users.name', 'LIKE', "%{$search}%");
                            $query->orWhere('users.first_name', 'LIKE', "%{$search}%");
                            $query->orWhere('users.middle_name', 'LIKE', "%{$search}%");
                            $query->orWhere('users.last_name', 'LIKE', "%{$search}%");
                            $query->orWhere('users.phone', 'LIKE', "%{$search}%");
                            $query->orWhere('users.email', 'LIKE', "%{$search}%");
                            $query->orWhere('users.uid', 'LIKE', "%{$search}%");
                        });
        }else{
            $users = User::select('users.*');
        }
        return $users;
    }  

    /**
    * Get results for a partner affiliated users
    *
    */
    public function results($search = null, $roundID = 0, $countyID = 0, $subCountyID = 0, $facilityID = 0)
    {
        $users = $this->users($search);
        $facilities = $this->facilities();

        $enrolments = $users->join('enrolments', 'users.id', '=', 'enrolments.tester_id')
                            ->join('users AS panels', 'enrolments.user_id', 'panels.id')
                            ->whereIn('enrolments.facility_id', $facilities['facilities']);

        if($roundID > 0) $enrolments = $enrolments->where('round_id', $roundID);

        $results = $enrolments->join('pt', 'enrolments.id', '=', 'pt.enrolment_id')
                        ->join('facilities', 'enrolments.facility_id', '=', 'facilities.id')
                        ->join('sub_counties', 'facilities.sub_county_id', '=', 'sub_counties.id')
                        ->join('counties', 'sub_counties.county_id', '=', 'counties.id')
                        ->whereNull('pt.deleted_at')
                        ->select(['users.*', 'enrolments.*', 'pt.*', 'panels.uid AS panel_id']);

        if($countyID > 0) $results = $results->where('counties.id', $countyID);
        if($subCountyID > 0) $results = $results->where('sub_counties.id', $subCountyID);
        if($facilityID > 0) $results = $results->where('facilities.id', $facilityID);

        return $results;
    }
}