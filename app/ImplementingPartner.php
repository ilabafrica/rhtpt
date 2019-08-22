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

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'implementing_partners';

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
        $result = $this->facilities();

        if ($role =='User') {

            $partner = [$this->id];
            $partner_role = Role::idByName('Partner');

            $county = $result['counties'];
            $county_role = Role::idByName('County Coordinator');

            $subcounty_role = Role::idByName('Sub-County Coordinator');
            $subs = $result['subcounties'];

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
            $prole = Role::idByName('Participant');
            $fls = $result['facilities'];;
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
                        ->join('role_user', 'users.id', '=', 'role_user.user_id')->where('role_id', $prole)
                        ->whereIn('tier', $fls);
       }else{
            $prole = Role::idByName('Participant');
            $fls = $result['facilities'];
            $users = User::select('users.*')
                        ->join('role_user', 'users.id', '=', 'role_user.user_id')
                        ->where('role_id', $prole)->whereIn('tier', $fls);
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

        $enrolments = $users->join('enrolments', 'users.id', '=', 'enrolments.tester_id');

        if($roundID > 0) $enrolments = $enrolments->where('round_id', $roundID);

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