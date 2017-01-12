<?php

namespace App\Http\Controllers;
set_time_limit(0);
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\County;
use App\Models\SubCounty;
use App\Models\Facility;
use App\Models\Program;
use App\Models\Tier;
use App\Models\Role;
use App\Models\User;
use App\Models\Shipper;

use Input;
use Response;
use DB;
use Hash;

class ApiController extends Controller
{
    public function dropdown($id)
    {
       //$county_id = Input::get('county');
       $subCounties = County::find($id)->subCounties();
       return Response::make($subCounties->get(['id','name']));
    }
    public function dropdown2($id)
    {
       $facilities = SubCounty::find($id)->facilities();
       return Response::make($facilities->get(['id','name']));
    }
    public function dropdown3($id)
    {
       $partners = Shipper::whereIn('id', DB::table('shipper_facilities')->where('facility_id', $id)->lists('shipper_id'));
       return Response::make($partners->get(['id','name']));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    /*public static function rhtpt()
  	{
  		$metadata = base_path().'/public/facilities.json';
  		$json = json_decode(file_get_contents($metadata), true);
  		//dd($json);
  		foreach ($json as $a => $b)
  		{
  			//dd($b["code"]);
  			$c = County::where('name', 'LIKE', '%'.$b["county"].'%')->count();
  			if($c>0)
  			{
  				$cnty = County::where('name', 'LIKE', '%'.$b["county"].'%')->first();
  				$sb = new SubCounty;
  				$sb->name = strtoupper($b['sub_county']);
  				$sb->county_id = $cnty->id;
  				$sb->save();
  				if($sb)
  				{
  					$facility = new Facility;
  					$facility->code = $b["code"];
  					$facility->name = strtoupper($b["name"]);
  					$facility->sub_county_id = $sb->id;
  					$facility->save();
  				}
  			}
  			else
  			{
  				dd($b);
  			}
  		}
  	}*/
    public static function rhtpt()
  	{
  		$metadata = base_path().'/public/dirty_facilities.json';
  		$json = json_decode(file_get_contents($metadata), true);
  		//dd($json);
  		foreach ($json as $a => $b)
  		{
  			//dd($b["code"]);
  			$c = County::where('name', 'LIKE', '%'.$b["COUNTY"].'%')->count();
  			if($c>0)
  			{
  				$cnty = County::where('name', 'LIKE', '%'.$b["COUNTY"].'%')->first();
  				$sb = new SubCounty;
  				$sb->name = strtoupper($b['SUBCOUNTY']);
  				$sb->county_id = $cnty->id;
  				$sb->save();
  				if($sb)
  				{
  					$facility = new Facility;
  					$facility->code = $b["MFL"];
  					$facility->name = strtoupper($b["NAME"]);
  					$facility->sub_county_id = $sb->id;
  					$facility->save();
  				}
  			}
  			else
  			{
  				dd($b);
  			}
  		}
  	}
  	/*public static function pt()
  	{
  		$metadata = base_path().'/public/participants.json';
  		$json = json_decode(file_get_contents($metadata), true);
  		//dd($json);
  		foreach ($json as $a => $b)
  		{
  			//dd($b["code"]);
        // Check if facility exists
        $f = Facility::where('code', $b["mfl_code"])->orWhere('name', $b["facility_name"])->get();
        $facility_id = NULL;
        if(count($f)>0)
        {
          $facility_id = Facility::where('code', $b["mfl_code"])->orWhere('name', $b["facility_name"])->first()->id;
        }
        else
        {
          $scnty = SubCounty::where('name', $b["sub_county"])->get();
          $sb_id = NULL;
          if(count($scnty)>0)
          {
            $sb_id = SubCounty::where('name', $b["sub_county"])->first()->id;
          }
          else
          {
            $s = new SubCounty;
            $s->name = $b["sub_county"];
            $s->county_id = 1;
            $s->save();
            $sb_id = $s->id;
          }
          $fcty = new Facility;
          $fcty->code = $b["mfl_code"];
          $fcty->name = strtoupper($b["facility_name"]);
          $fcty->sub_county_id = SubCounty::where('name', $b["sub_county"])->first()->id;
          $fcty->save();
          $facility_id = $fcty->id;
        }
  			$prg = Program::where('name', $b["program"])->first();
  			$usr = new User;
  			$usr->name = $b['tester_name'];
  			$usr->gender = 0;
        $tel = NULL;
        if(trim($b['tester_phone'])!=NULL || trim($b['tester_phone'])!="NULL")
        {
          $tel = trim($b['tester_phone']);
          $tel = rtrim($tel, '.');
          $tel = ltrim($tel, '0');
          $tel = "+254".$tel;
        }
  			$usr->phone = $tel;
  			$usr->username = $b['id_no'];
  			$usr->password = Hash::make("123456");
  			$usr->uid = $b['id_no'];
        //dd($usr->phone);
  			$usr->save();
  			if($usr)
  			{
          $role = Role::find(Role::idByName('Participant'));
          $usr->attachRole($role);

  				$tier = new Tier;
  				$tier->user_id = $usr->id;
  				$tier->role_id = $role->id;
  				$tier->tier = $facility_id;
  				$tier->program_id = $prg->id;
  				$tier->save();
  			}
  		}
  	}*/
    public static function pt()
  	{
  		$metadata = base_path().'/public/dirty_participants.json';
  		$json = json_decode(file_get_contents($metadata), true);
  		//dd($json);
  		foreach ($json as $a => $b)
  		{
  			//dd($b["code"]);
        // Get facility ID
        $facility_id = Facility::where('name', $b["FACILITY"])->first()->id;

  			$prg = Program::where('name', $b["PROGRAM"])->first();
  			$usr = new User;
  			$usr->name = $b['TESTER'];
  			$usr->gender = 0;
        $tel = NULL;
        if((trim($b['PHONE'])!=NULL || trim($b['PHONE'])!="NULL") && (int)strlen($b['PHONE']) > 8)
        {
          $tel = str_replace(' ', '', $b['PHONE']);
          $tel = trim($tel);
          $tel = rtrim($tel, '.');
          $tel = ltrim($tel, '0');
          $tel = substr($tel, 0, 9);
          $tel = "+254".$tel;
        }
        if(array_key_exists("EMAIL", $b))
        {
          if(trim($b["EMAIL"])!=NULL || trim($b["EMAIL"])!="NULL")
            $usr->email = $b["EMAIL"];
        }
  			$usr->phone = $tel;
  			$usr->username = $b['IDNO'];
  			$usr->password = Hash::make("123456");
  			$usr->uid = $b['IDNO'];
        //dd($usr->phone);
  			$usr->save();
  			if($usr)
  			{
          $role = Role::find(Role::idByName('Participant'));
          $usr->attachRole($role);

  				$tier = new Tier;
  				$tier->user_id = $usr->id;
  				$tier->role_id = $role->id;
  				$tier->tier = $facility_id;
  				$tier->program_id = $prg->id;
  				$tier->save();
  			}
  		}
  	}
}
