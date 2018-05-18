<?php namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\SubCounty;
use App\Facility;
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
    * Get  facilites for this partner
    *
    */
    public function facilities()
    {
      $counties = $this->counties()->pluck('id')->toArray();

      $subcounties = SubCounty::whereIn('county_id', $counties)->pluck('id')->toArray();

      $facilities = Facility::whereIn('sub_county_id', $subcounties)->pluck('id')->toArray();

      $result = array( 'counties'=>$counties, 'subcounties'=>$subcounties, 'facilities'=>$facilities);

      return $result;
    }


    /**
    * Get users for a sub-county
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

            $users = User::select('users.*')->join('role_user', 'users.id', '=', 'role_user.user_id')
                        ->where(function($query) use ($subcounty_role, $subs){
                            return $query->where('role_id', $subcounty_role)->whereIn('tier', $subs);
                        })->orWhere(function($q) use ($county_role, $county){
                            return $q->where('role_id', $county_role)->whereIn('tier', $county);
                        })->orWhere(function($qry) use ($partner_role, $partner){
                            return $qry->where('role_id', $partner_role)->whereIn('tier', $partner);
                        });
        }else{
            $prole = Role::idByName('Participant');
            $fls = $result['facilities'];
            $users = User::select('users.*')->join('role_user', 'users.id', '=', 'role_user.user_id')->where('role_id', $prole)->whereIn('tier', $fls);
          }
        return $users;
    }  
}