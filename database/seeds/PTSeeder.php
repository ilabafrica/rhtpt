<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Permission;
use App\Models\Role;
use App\Models\County;
use App\Models\SubCounty;
use App\Models\Program;
use App\Models\FieldSet;
use App\Models\Field;
use App\Models\Option;

//	Carbon - for use with dates
use Jenssegers\Date\Date as Carbon;

class PTSeeder extends Seeder
{
    public function run()
    {
    	$now = Carbon::today()->toDateTimeString();
    	/* Users table */
        $usersData = array(
            array(
                "username" => "admin", "password" => Hash::make("password"), "email" => "admin@rhtpt.or.ke",
                "name" => "PT Administrator", "gender" => "1", "phone"=>"0722000000", "address" => "P.O. Box 59857-00200, Nairobi", "created_at" => $now, "updated_at" => $now
            ),
            array(
                "username" => "kitsao", "password" => Hash::make("password"), "email" => "kitsao@gmail.com",
                "name" => "Kitsao", "gender" => "1", "phone"=>"0764999662", "address" => "Nairobi", "created_at" => $now, "updated_at" => $now
            )
        );
        foreach ($usersData as $user)
        {
            $users[] = User::create($user);
        }
        $this->command->info('Users table seeded');

        /* Permissions table */
        $permissions = array(
            array("name" => "back-up", "display_name" => "Can back up"),

            array("name" => "create-user", "display_name" => "Can create user"),
            array("name" => "read-user", "display_name" => "Can read user"),
            array("name" => "update-user", "display_name" => "Can update user"),
            array("name" => "delete-user", "display_name" => "Can delete user"),

            array("name" => "create-role", "display_name" => "Can create role"),
            array("name" => "read-role", "display_name" => "Can read role"),
            array("name" => "update-role", "display_name" => "Can update role"),
            array("name" => "delete-role", "display_name" => "Can delete role"),

            array("name" => "read-permission", "display_name" => "Can read permission"),
            array("name" => "assign-role", "display_name" => "Can assign role")
        );
        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
        $this->command->info('Permissions table seeded');
        /* Roles table */
        $roles = array(
            array("name" => "Superadmin", "display_name" => "Overall Administrator"),
            array("name" => "Participant", "display_name" => "Participant"),
            array("name" => "County Lab Admin", "display_name" => "County Lab Administrator")
        );
        foreach ($roles as $role)
        {
            Role::create($role);
        }
        $this->command->info('Roles table seeded');

        $role1 = Role::find(1);
        $permissions = Permission::all();

        //Assign all permissions to role administrator
        foreach ($permissions as $permission)
        {
            $role1->attachPermission($permission);
        }
        //Assign role Superadmin to all permissions
        User::find(1)->attachRole($role1);

        /* Counties table */
        $baringo = County::create(array("name" => "Baringo"));
        $bomet = County::create(array("name" => "Bomet"));
        $bungoma = County::create(array("name" => "Bungoma"));
        $busia = County::create(array("name" => "Busia"));
        $elgeyo = County::create(array("name" => "Elgeyo Marakwet"));
        $embu = County::create(array("name" => "Embu"));
        $garissa = County::create(array("name" => "Garissa"));
        $homabay = County::create(array("name" => "Homa Bay"));
        $isiolo = County::create(array("name" => "Isiolo"));
        $kajiado = County::create(array("name" => "Kajiado"));
        $kakamega = County::create(array("name" => "Kakamega"));
        $kericho = County::create(array("name" => "Kericho"));
        $kiambu = County::create(array("name" => "Kiambu"));
        $kilifi = County::create(array("name" => "Kilifi"));
        $kirinyaga = County::create(array("name" => "Kirinyaga"));
        $kisii = County::create(array("name" => "Kisii"));
        $kisumu = County::create(array("name" => "Kisumu"));
        $kitui = County::create(array("name" => "Kitui"));
        $kwale = County::create(array("name" => "Kwale"));
        $laikipia = County::create(array("name" => "Laikipia"));
        $lamu = County::create(array("name" => "Lamu"));
        $machakos = County::create(array("name" => "Machakos"));
        $makueni = County::create(array("name" => "Makueni"));
        $mandera = County::create(array("name" => "Mandera"));
        $marsabit = County::create(array("name" => "Marsabit"));
        $meru = County::create(array("name" => "Meru"));
        $migori = County::create(array("name" => "Migori"));
        $mombasa = County::create(array("name" => "Mombasa"));
        $muranga = County::create(array("name" => "Murang'a"));
        $nairobi = County::create(array("name" => "Nairobi"));
        $nakuru = County::create(array("name" => "Nakuru"));
        $nandi = County::create(array("name" => "Nandi"));
        $narok = County::create(array("name" => "Narok"));
        $nyamira = County::create(array("name" => "Nyamira"));
        $nyandarua = County::create(array("name" => "Nyandarua"));
        $nyeri = County::create(array("name" => "Nyeri"));
        $samburu = County::create(array("name" => "Samburu"));
        $siaya = County::create(array("name" => "Siaya"));
        $taita = County::create(array("name" => "Taita Taveta"));
        $tanariver = County::create(array("name" => "Tana River"));
        $tharakanithi = County::create(array("name" => "Tharaka Nithi"));
        $transnzoia = County::create(array("name" => "Trans Nzoia"));
        $turkana = County::create(array("name" => "Turkana"));
        $uasingishu = County::create(array("name" => "Uasin Gishu"));
        $vihiga = County::create(array("name" => "Vihiga"));
        $wajir = County::create(array("name" => "Wajir"));
        $pokot = County::create(array("name" => "West Pokot"));

        $this->command->info('Counties table seeded');
        /* Sub-Counties table */
        $subs = array(
            //  Baringo
            array("name" => "Mogotio", "county_id" => $baringo->id),
            array("name" => "Koibatek", "county_id" => $baringo->id),
            array("name" => "Marigat", "county_id" => $baringo->id),
            array("name" => "Baringo Central", "county_id" => $baringo->id),
            array("name" => "Baringo North", "county_id" => $baringo->id),
            array("name" => "East Pokot", "county_id" => $baringo->id),

            //  Bomet
            array("name" => "Bomet Central", "county_id" => $bomet->id),
            array("name" => "Bomet East", "county_id" => $bomet->id),
            array("name" => "Chepalungu", "county_id" => $bomet->id),
            array("name" => "Sotik", "county_id" => $bomet->id),
            array("name" => "Konoin", "county_id" => $bomet->id),

            //  Bungoma
            array("name" => "Bumula", "county_id" => $bungoma->id),
            array("name" => "Bungoma South", "county_id" => $bungoma->id),
            array("name" => "Bungoma Central", "county_id" => $bungoma->id),
            array("name" => "Bungoma West", "county_id" => $bungoma->id),
            array("name" => "Kimilili", "county_id" => $bungoma->id),
            array("name" => "Bungoma North", "county_id" => $bungoma->id),
            array("name" => "Mt. Elgon", "county_id" => $bungoma->id),
            array("name" => "Webuye East", "county_id" => $bungoma->id),
            array("name" => "Webuye West", "county_id" => $bungoma->id),

            //  Busia
            array("name" => "Teso North", "county_id" => $busia->id),
            array("name" => "Teso South", "county_id" => $busia->id),
            array("name" => "Busia", "county_id" => $busia->id),
            array("name" => "Nambale", "county_id" => $busia->id),
            array("name" => "Butula", "county_id" => $busia->id),
            array("name" => "Bunyala", "county_id" => $busia->id),
            array("name" => "Samia", "county_id" => $busia->id),

            //  Elgeyo Marakwet
            array("name" => "Keiyo", "county_id" => $elgeyo->id),
            array("name" => "Keiyo South", "county_id" => $elgeyo->id),
            array("name" => "Marakwet West", "county_id" => $elgeyo->id),
            array("name" => "Marakwet East", "county_id" => $elgeyo->id),

            //  Embu
            array("name" => "Embu West", "county_id" => $embu->id),
            array("name" => "Embu North", "county_id" => $embu->id),
            array("name" => "Embu East", "county_id" => $embu->id),
            array("name" => "Mbeere South", "county_id" => $embu->id),
            array("name" => "Mbeere North", "county_id" => $embu->id),
            array("name" => "Mt. Kenya Forest", "county_id" => $embu->id),

            //  Garissa
            array("name" => "Garissa Township", "county_id" => $garissa->id),
            array("name" => "Balambala", "county_id" => $garissa->id),
            array("name" => "Lagdera", "county_id" => $garissa->id),
            array("name" => "Dadaab", "county_id" => $garissa->id),
            array("name" => "Fafi", "county_id" => $garissa->id),
            array("name" => "Ijara", "county_id" => $garissa->id),

            //  Homa Bay
            array("name" => "Rachuonyo South", "county_id" => $homabay->id),
            array("name" => "Rachuonyo North", "county_id" => $homabay->id),
            array("name" => "Homabay", "county_id" => $homabay->id),
            array("name" => "Ndhiwa", "county_id" => $homabay->id),
            array("name" => "Mbita", "county_id" => $homabay->id),
            array("name" => "Suba", "county_id" => $homabay->id),

            //  Isiolo
            array("name" => "Isiolo North", "county_id" => $isiolo->id),
            array("name" => "Isiolo South", "county_id" => $isiolo->id),

            //  Kajiado
            array("name" => "Kajiado North", "county_id" => $kajiado->id),
            array("name" => "Kajiado Central", "county_id" => $kajiado->id),
            array("name" => "Isinya", "county_id" => $kajiado->id),
            array("name" => "Mashuru", "county_id" => $kajiado->id),
            array("name" => "Loitokitok", "county_id" => $kajiado->id),

            //  Kakamega
            array("name" => "Butere", "county_id" => $kakamega->id),
            array("name" => "Khwisero", "county_id" => $kakamega->id),
            array("name" => "Kakamega Central", "county_id" => $kakamega->id),
            array("name" => "Navakholo", "county_id" => $kakamega->id),
            array("name" => "Kakamega East", "county_id" => $kakamega->id),
            array("name" => "Kakamega North", "county_id" => $kakamega->id),
            array("name" => "Matete", "county_id" => $kakamega->id),
            array("name" => "Kakamega South", "county_id" => $kakamega->id),
            array("name" => "Likuyani", "county_id" => $kakamega->id),
            array("name" => "Lugari", "county_id" => $kakamega->id),
            array("name" => "Matungu", "county_id" => $kakamega->id),
            array("name" => "Mumias", "county_id" => $kakamega->id),

            //  Kericho
            array("name" => "Kericho East", "county_id" => $kericho->id),
            array("name" => "Kericho West", "county_id" => $kericho->id),
            array("name" => "Sigowet", "county_id" => $kericho->id),
            array("name" => "Kipkelion West", "county_id" => $kericho->id),
            array("name" => "Kipkelion East", "county_id" => $kericho->id),
            array("name" => "Bureti", "county_id" => $kericho->id),

            //  Kiambu
            array("name" => "Gatundu South", "county_id" => $kiambu->id),
            array("name" => "Gatundu North", "county_id" => $kiambu->id),
            array("name" => "Ruiru", "county_id" => $kiambu->id),
            array("name" => "Thika East", "county_id" => $kiambu->id),
            array("name" => "Thika West", "county_id" => $kiambu->id),
            array("name" => "Githunguri", "county_id" => $kiambu->id),
            array("name" => "Kiambu", "county_id" => $kiambu->id),
            array("name" => "Limuru", "county_id" => $kiambu->id),
            array("name" => "Kikuyu", "county_id" => $kiambu->id),
            array("name" => "Lari", "county_id" => $kiambu->id),

            //  Kilifi
            array("name" => "Bahari", "county_id" => $kilifi->id),
            array("name" => "Ganze", "county_id" => $kilifi->id),
            array("name" => "Malindi", "county_id" => $kilifi->id),
            array("name" => "Magarini", "county_id" => $kilifi->id),
            array("name" => "Kaloleni", "county_id" => $kilifi->id),
            array("name" => "Rabai", "county_id" => $kilifi->id),
            array("name" => "Arabuko Sokoke Forest", "county_id" => $kilifi->id),

            //  Kirinyaga
            array("name" => "Kirinyaga West", "county_id" => $kirinyaga->id),
            array("name" => "Kirinyaga Central", "county_id" => $kirinyaga->id),
            array("name" => "Kirinyaga East", "county_id" => $kirinyaga->id),
            array("name" => "Mwea East", "county_id" => $kirinyaga->id),
            array("name" => "Mwea West", "county_id" => $kirinyaga->id),
            array("name" => "Forest Area", "county_id" => $kirinyaga->id),

            //  Kisii
            array("name" => "Masaba South", "county_id" => $kisii->id),
            array("name" => "Kisii Central", "county_id" => $kisii->id),
            array("name" => "Marani", "county_id" => $kisii->id),
            array("name" => "Kisii South", "county_id" => $kisii->id),
            array("name" => "Gucha South", "county_id" => $kisii->id),
            array("name" => "Gucha Nyamache", "county_id" => $kisii->id),
            array("name" => "Sameta", "county_id" => $kisii->id),
            array("name" => "Kenyenya", "county_id" => $kisii->id),

            //  Kisumu
            array("name" => "Kisumu North", "county_id" => $kisumu->id),
            array("name" => "Kisumu East", "county_id" => $kisumu->id),
            array("name" => "Kisumu West", "county_id" => $kisumu->id),
            array("name" => "Nyando", "county_id" => $kisumu->id),
            array("name" => "Muhoroni", "county_id" => $kisumu->id),
            array("name" => "Nyakach", "county_id" => $kisumu->id),

            //  Kitui
            array("name" => "Kitui Central", "county_id" => $kitui->id),
            array("name" => "Kisasi", "county_id" => $kitui->id),
            array("name" => "Katulani", "county_id" => $kitui->id),
            array("name" => "Lower Yatta", "county_id" => $kitui->id),
            array("name" => "Kitui West", "county_id" => $kitui->id),
            array("name" => "Matinyani", "county_id" => $kitui->id),
            array("name" => "Mutomo", "county_id" => $kitui->id),
            array("name" => "Ikutha", "county_id" => $kitui->id),
            array("name" => "Mutito", "county_id" => $kitui->id),
            array("name" => "Nzambani", "county_id" => $kitui->id),
            array("name" => "Mwingi Central", "county_id" => $kitui->id),
            array("name" => "Migwani", "county_id" => $kitui->id),
            array("name" => "Mwingi East", "county_id" => $kitui->id),
            array("name" => "Kyuso", "county_id" => $kitui->id),
            array("name" => "Tseikuru", "county_id" => $kitui->id),
            array("name" => "Mumoni", "county_id" => $kitui->id),

            //  Kwale
            array("name" => "Matuga", "county_id" => $kwale->id),
            array("name" => "Kinango", "county_id" => $kwale->id),
            array("name" => "Msambweni", "county_id" => $kwale->id),

            //  Laikipia
            array("name" => "Laikipia East", "county_id" => $laikipia->id),
            array("name" => "Laikipia North", "county_id" => $laikipia->id),
            array("name" => "Laikipia West", "county_id" => $laikipia->id),

            //  Lamu
            array("name" => "Lamu West", "county_id" => $lamu->id),
            array("name" => "Lamu East", "county_id" => $lamu->id),

            //  Machakos
            array("name" => "Machakos", "county_id" => $machakos->id),
            array("name" => "Kangundo", "county_id" => $machakos->id),
            array("name" => "Kathiani", "county_id" => $machakos->id),
            array("name" => "Athi River", "county_id" => $machakos->id),
            array("name" => "Yatta", "county_id" => $machakos->id),
            array("name" => "Masinga", "county_id" => $machakos->id),
            array("name" => "Matungulu", "county_id" => $machakos->id),
            array("name" => "Mwala", "county_id" => $machakos->id),

            //  Makueni
            array("name" => "Kaiti", "county_id" => $makueni->id),
            array("name" => "Kibwezi East", "county_id" => $makueni->id),
            array("name" => "Kibwezi West", "county_id" => $makueni->id),
            array("name" => "Kilome", "county_id" => $makueni->id),
            array("name" => "Makueni", "county_id" => $makueni->id),
            array("name" => "Mbooni", "county_id" => $makueni->id),

            //  Mandera
            array("name" => "Banisa", "county_id" => $mandera->id),
            array("name" => "Mandera West", "county_id" => $mandera->id),
            array("name" => "Mandera East", "county_id" => $mandera->id),
            array("name" => "Lafey", "county_id" => $mandera->id),
            array("name" => "Mandera North", "county_id" => $mandera->id),
            array("name" => "Mandera South", "county_id" => $mandera->id),

            //  Marsabit
            array("name" => "Marsabit Central", "county_id" => $marsabit->id),
            array("name" => "Marsabit South", "county_id" => $marsabit->id),
            array("name" => "Loiyangalani", "county_id" => $marsabit->id),
            array("name" => "Marsabit North", "county_id" => $marsabit->id),
            array("name" => "North Horr", "county_id" => $marsabit->id),
            array("name" => "Moyale", "county_id" => $marsabit->id),
            array("name" => "Sololo", "county_id" => $marsabit->id),

            //  Meru
            array("name" => "Tigania East", "county_id" => $meru->id),
            array("name" => "Tigania West", "county_id" => $meru->id),
            array("name" => "Igembe North", "county_id" => $meru->id),
            array("name" => "Igembe South", "county_id" => $meru->id),
            array("name" => "Imenti North", "county_id" => $meru->id),
            array("name" => "Imenti South", "county_id" => $meru->id),
            array("name" => "Buuri", "county_id" => $meru->id),
            array("name" => "Meru Central", "county_id" => $meru->id),

            //  Migori
            array("name" => "Migori", "county_id" => $migori->id),
            array("name" => "Nyatike", "county_id" => $migori->id),
            array("name" => "Kuria East", "county_id" => $migori->id),
            array("name" => "Kuria West", "county_id" => $migori->id),
            array("name" => "Awendo", "county_id" => $migori->id),
            array("name" => "Uriri", "county_id" => $migori->id),
            array("name" => "Rongo", "county_id" => $migori->id),

            //  Mombasa
            array("name" => "Mvita", "county_id" => $mombasa->id),
            array("name" => "Kisauni", "county_id" => $mombasa->id),
            array("name" => "Likoni", "county_id" => $mombasa->id),
            array("name" => "Changamwe", "county_id" => $mombasa->id),

            //  Muranga
            array("name" => "Kiharu", "county_id" => $muranga->id),
            array("name" => "Kahuro", "county_id" => $muranga->id),
            array("name" => "Mathioya", "county_id" => $muranga->id),
            array("name" => "Kangema", "county_id" => $muranga->id),
            array("name" => "Gatanga", "county_id" => $muranga->id),
            array("name" => "Kigumo", "county_id" => $muranga->id),
            array("name" => "Kandara", "county_id" => $muranga->id),
            array("name" => "Muranga South", "county_id" => $muranga->id),

            //  Nairobi
            array("name" => "Starehe", "county_id" => $nairobi->id),
            array("name" => "Kamukunji", "county_id" => $nairobi->id),
            array("name" => "Kasarani", "county_id" => $nairobi->id),
            array("name" => "Makadara", "county_id" => $nairobi->id),
            array("name" => "Embakasi", "county_id" => $nairobi->id),
            array("name" => "Njiru", "county_id" => $nairobi->id),
            array("name" => "Dagoretti", "county_id" => $nairobi->id),
            array("name" => "Langata", "county_id" => $nairobi->id),
            array("name" => "Westlands", "county_id" => $nairobi->id),

            //  Nakuru
            array("name" => "Nakuru Town", "county_id" => $nakuru->id),
            array("name" => "Naivasha", "county_id" => $nakuru->id),
            array("name" => "Molo", "county_id" => $nakuru->id),
            array("name" => "Njoro", "county_id" => $nakuru->id),
            array("name" => "Kuresoi", "county_id" => $nakuru->id),
            array("name" => "Rongai", "county_id" => $nakuru->id),
            array("name" => "Nakuru North", "county_id" => $nakuru->id),
            array("name" => "Subukia", "county_id" => $nakuru->id),
            array("name" => "Gilgil", "county_id" => $nakuru->id),

            //  Nandi
            array("name" => "Nandi Central", "county_id" => $nandi->id),
            array("name" => "Nandi South", "county_id" => $nandi->id),
            array("name" => "Nandi North", "county_id" => $nandi->id),
            array("name" => "Nandi East", "county_id" => $nandi->id),
            array("name" => "Tinderet", "county_id" => $nandi->id),

            //  Narok
            array("name" => "Transmara West", "county_id" => $narok->id),
            array("name" => "Transmara East", "county_id" => $narok->id),
            array("name" => "Narok South", "county_id" => $narok->id),
            array("name" => "Narok North", "county_id" => $narok->id),

            //  Nyamira
            array("name" => "Nyamira", "county_id" => $nyamira->id),
            array("name" => "Nyamira North", "county_id" => $nyamira->id),
            array("name" => "Borabu", "county_id" => $nyamira->id),
            array("name" => "Manga", "county_id" => $nyamira->id),
            array("name" => "Masaba North", "county_id" => $nyamira->id),

            //  Nyandarua
            array("name" => "Kinangop", "county_id" => $nyandarua->id),
            array("name" => "Kipipiri", "county_id" => $nyandarua->id),
            array("name" => "Ol Kalou", "county_id" => $nyandarua->id),
            array("name" => "Nyandarua West", "county_id" => $nyandarua->id),
            array("name" => "Nyandarua North", "county_id" => $nyandarua->id),
            array("name" => "Aberdare Forest", "county_id" => $nyandarua->id),

            //  Nyeri
            array("name" => "Mathira East", "county_id" => $nyeri->id),
            array("name" => "Mathira West", "county_id" => $nyeri->id),
            array("name" => "Kieni West", "county_id" => $nyeri->id),
            array("name" => "Kieni East", "county_id" => $nyeri->id),
            array("name" => "Tetu", "county_id" => $nyeri->id),
            array("name" => "Mukurwe-ini", "county_id" => $nyeri->id),
            array("name" => "Nyeri Central", "county_id" => $nyeri->id),
            array("name" => "Nyeri South", "county_id" => $nyeri->id),

            //  Samburu
            array("name" => "Samburu Central", "county_id" => $samburu->id),
            array("name" => "Samburu East", "county_id" => $samburu->id),
            array("name" => "Samburu North", "county_id" => $samburu->id),

            //  Siaya
            array("name" => "Siaya", "county_id" => $siaya->id),
            array("name" => "Gem", "county_id" => $siaya->id),
            array("name" => "Ugenya", "county_id" => $siaya->id),
            array("name" => "Ugunja", "county_id" => $siaya->id),
            array("name" => "Bondo", "county_id" => $siaya->id),
            array("name" => "Rarieda", "county_id" => $siaya->id),

            //  Taita Taveta
            array("name" => "Taveta", "county_id" => $taita->id),
            array("name" => "Wundanyi", "county_id" => $taita->id),
            array("name" => "Mwatate", "county_id" => $taita->id),
            array("name" => "Voi", "county_id" => $taita->id),

            //  Tana River
            array("name" => "Bura", "county_id" => $baringo->id),
            array("name" => "Galole", "county_id" => $baringo->id),
            array("name" => "Tana Delta", "county_id" => $baringo->id),

            //  Tharaka Nithi
            array("name" => "Tharaka North", "county_id" => $tharakanithi->id),
            array("name" => "Tharaka South", "county_id" => $tharakanithi->id),
            array("name" => "Meru South", "county_id" => $tharakanithi->id),
            array("name" => "Maara", "county_id" => $tharakanithi->id),

            //  Trans Nzoia
            array("name" => "Trans Nzoia West", "county_id" => $transnzoia->id),
            array("name" => "Trans Nzoia East", "county_id" => $transnzoia->id),
            array("name" => "Kwanza", "county_id" => $transnzoia->id),

            //  Turkana
            array("name" => "Turkana South", "county_id" => $turkana->id),
            array("name" => "Turkana East", "county_id" => $turkana->id),
            array("name" => "Turkana North", "county_id" => $turkana->id),
            array("name" => "Turkana West", "county_id" => $turkana->id),
            array("name" => "Turkana Central", "county_id" => $turkana->id),
            array("name" => "Loima", "county_id" => $turkana->id),

            //  Uasin Gishu
            array("name" => "Turbo", "county_id" => $uasingishu->id),
            array("name" => "Soy", "county_id" => $uasingishu->id),
            array("name" => "Ainabkoi", "county_id" => $uasingishu->id),
            array("name" => "Moiben", "county_id" => $uasingishu->id),
            array("name" => "Kesses", "county_id" => $uasingishu->id),
            array("name" => "Kapseret", "county_id" => $uasingishu->id),

            //  Vihiga
            array("name" => "Vihiga", "county_id" => $vihiga->id),
            array("name" => "Sabatia", "county_id" => $vihiga->id),
            array("name" => "Emuhaya", "county_id" => $vihiga->id),
            array("name" => "Luanda", "county_id" => $vihiga->id),
            array("name" => "Hamisi", "county_id" => $vihiga->id),

            //  Wajir
            array("name" => "Wajir East", "county_id" => $wajir->id),
            array("name" => "Tarbaj", "county_id" => $wajir->id),
            array("name" => "Eldas", "county_id" => $wajir->id),
            array("name" => "Wajir West", "county_id" => $wajir->id),
            array("name" => "Habaswein", "county_id" => $wajir->id),
            array("name" => "Wajir South", "county_id" => $wajir->id),
            array("name" => "Wajir North", "county_id" => $wajir->id),
            array("name" => "Buna", "county_id" => $wajir->id),

            //  West Pokot
            array("name" => "West Pokot", "county_id" => $pokot->id),
            array("name" => "South Pokot", "county_id" => $pokot->id),
            array("name" => "Pokot Central", "county_id" => $pokot->id),
            array("name" => "North Pokot", "county_id" => $pokot->id),
          );
          foreach ($subs as $sb)
          {
              SubCounty::create($sb);
          }
          $this->command->info('Sub-Counties table seeded');

          /*  Programs */
          $programs = array(
              array("name" => "Laboratory", "description" => "Laboratory"),
              array("name" => "PMTCT", "description" => "Prevention of Mother To Child Transmission of HIV/AIDS"),
              array("name" => "PSC/CCC", "description" => "Patient Support Center"),
              array("name" => "VCT", "description" => "Voluntary Counselling and Testing"),
              array("name" => "VMMC", "description" => "Voluntary Male Medical Circumcision")
          );
          foreach ($programs as $program) {
              Program::create($program);
          }
          $this->command->info('Programs table seeded');

          /*  Field-sets */
          $sets = array(
              array("name" => "PT Panel Dates", "label" => "PT Panel Dates", "order" => "0"),
              array("name" => "Test 1", "label" => "Test 1", "order" => "1"),
              array("name" => "Test 2", "label" => "Test 2", "order" => "2"),
              array("name" => "Test 3", "label" => "Test 3", "order" => "3"),
              array("name" => "PT Panel 1", "label" => "Test Results", "order" => "4"),
              array("name" => "PT Panel 2", "label" => "Test Results", "order" => "5"),
              array("name" => "PT Panel 3", "label" => "Test Results", "order" => "6"),
              array("name" => "PT Panel 4", "label" => "Test Results", "order" => "7"),
              array("name" => "PT Panel 5", "label" => "Test Results", "order" => "8"),
              array("name" => "PT Panel 6", "label" => "Test Results", "order" => "9"),
              array("name" => "Remarks", "label" => "Remarks", "order" => "10"),
              array("name" => "New Tester Details", "label" => "New Tester Details", "order" => "11")
          );
          foreach ($sets as $set) {
              FieldSet::create($set);
          }
          $this->command->info('Field sets table seeded');

          /*  Fields */
          $fields = array(
              array("name" => "Date PT Panel Received", "label" => "Date PT Panel Received", "order" => "0", "tag" => "1", "field_set_id" => "1"),
              array("name" => "Date PT Panel Constituted", "label" => "Date PT Panel Constituted", "order" => "1", "tag" => "1", "field_set_id" => "1"),
              array("name" => "Date PT Panel Tested", "label" => "Date PT Panel Tested", "order" => "2", "tag" => "1", "field_set_id" => "1"),

              array("name" => "Test 1 Kit", "label" => "Kit Name", "order" => "0", "tag" => "4", "field_set_id" => "2"),
              array("name" => "Test 1 Lot", "label" => "Kit Lot No.", "order" => "1", "tag" => "3", "field_set_id" => "2"),
              array("name" => "Test 1 Expiry", "label" => "Kit Expiry Date", "order" => "2", "tag" => "1", "field_set_id" => "2"),

              array("name" => "Test 2 Kit", "label" => "Kit Name", "order" => "0", "tag" => "4", "field_set_id" => "3"),
              array("name" => "Test 2 Lot", "label" => "Kit Lot No.", "order" => "1", "tag" => "3", "field_set_id" => "3"),
              array("name" => "Test 2 Expiry", "label" => "Kit Expiry Date", "order" => "2", "tag" => "1", "field_set_id" => "3"),

              array("name" => "Test 3 Kit", "label" => "Kit Name", "order" => "0", "tag" => "4", "field_set_id" => "4"),
              array("name" => "Test 3 Lot", "label" => "Kit Lot No.", "order" => "1", "tag" => "3", "field_set_id" => "4"),
              array("name" => "Test 3 Expiry", "label" => "Kit Expiry Date", "order" => "2", "tag" => "1", "field_set_id" => "4"),

              array("name" => "PT Panel 1 Test 1 Results", "label" => "Test 1 Results", "order" => "0", "tag" => "4", "field_set_id" => "5"),
              array("name" => "PT Panel 1 Test 2 Results", "label" => "Test 2 Results", "order" => "1", "tag" => "4", "field_set_id" => "5"),
              array("name" => "PT Panel 1 Test 3 Results", "label" => "Test 3 Results", "order" => "2", "tag" => "4", "field_set_id" => "5"),
              array("name" => "PT Panel 1 Final Results", "label" => "Final Results", "order" => "3", "tag" => "4", "field_set_id" => "5"),

              array("name" => "PT Panel 2 Test 1 Results", "label" => "Test 1 Results", "order" => "0", "tag" => "4", "field_set_id" => "6"),
              array("name" => "PT Panel 2 Test 2 Results", "label" => "Test 2 Results", "order" => "1", "tag" => "4", "field_set_id" => "6"),
              array("name" => "PT Panel 2 Test 3 Results", "label" => "Test 3 Results", "order" => "2", "tag" => "4", "field_set_id" => "6"),
              array("name" => "PT Panel 2 Final Results", "label" => "Final Results", "order" => "3", "tag" => "4", "field_set_id" => "6"),

              array("name" => "PT Panel 3 Test 1 Results", "label" => "Test 1 Results", "order" => "0", "tag" => "4", "field_set_id" => "7"),
              array("name" => "PT Panel 3 Test 2 Results", "label" => "Test 2 Results", "order" => "1", "tag" => "4", "field_set_id" => "7"),
              array("name" => "PT Panel 3 Test 3 Results", "label" => "Test 3 Results", "order" => "2", "tag" => "4", "field_set_id" => "7"),
              array("name" => "PT Panel 3 Final Results", "label" => "Final Results", "order" => "3", "tag" => "", "field_set_id" => "7"),

              array("name" => "PT Panel 4 Test 1 Results", "label" => "Test 1 Results", "order" => "0", "tag" => "4", "field_set_id" => "8"),
              array("name" => "PT Panel 4 Test 2 Results", "label" => "Test 2 Results", "order" => "1", "tag" => "4", "field_set_id" => "8"),
              array("name" => "PT Panel 4 Test 3 Results", "label" => "Test 3 Results", "order" => "2", "tag" => "4", "field_set_id" => "8"),
              array("name" => "PT Panel 4 Final Results", "label" => "Final Results", "order" => "3", "tag" => "", "field_set_id" => "8"),

              array("name" => "PT Panel 5 Test 1 Results", "label" => "Test 1 Results", "order" => "0", "tag" => "4", "field_set_id" => "9"),
              array("name" => "PT Panel 5 Test 2 Results", "label" => "Test 2 Results", "order" => "1", "tag" => "4", "field_set_id" => "9"),
              array("name" => "PT Panel 5 Test 3 Results", "label" => "Test 3 Results", "order" => "2", "tag" => "4", "field_set_id" => "9"),
              array("name" => "PT Panel 5 Final Results", "label" => "Final Results", "order" => "3", "tag" => "4", "field_set_id" => "9"),

              array("name" => "PT Panel 6 Test 1 Results", "label" => "Test 1 Results", "order" => "0", "tag" => "4", "field_set_id" => "10"),
              array("name" => "PT Panel 6 Test 2 Results", "label" => "Test 2 Results", "order" => "1", "tag" => "4", "field_set_id" => "10"),
              array("name" => "PT Panel 6 Test 3 Results", "label" => "Test 3 Results", "order" => "2", "tag" => "4", "field_set_id" => "10"),
              array("name" => "PT Panel 6 Final Results", "label" => "Final Results", "order" => "3", "tag" => "4", "field_set_id" => "10"),

              array("name" => "Comments", "label" => "Comments", "order" => "0", "tag" => "6", "field_set_id" => "11")
          );
          foreach ($fields as $field) {
              Field::create($field);
          }
          $this->command->info('Fields table seeded');

          /*  Options */
          $options = array(
              array("name" => "KHB", "label" => "KHB", "description" => ""),
              array("name" => "First Response", "label" => "First Response", "description" => ""),
              array("name" => "Unigold", "label" => "Unigold", "description" => ""),
              array("name" => "Other", "label" => "Other", "description" => ""),
              array("name" => "Reactive", "label" => "Reactive", "description" => ""),
              array("name" => "Non-Reactive", "label" => "Non-Reactive", "description" => ""),
              array("name" => "Invalid", "label" => "Invalid", "description" => ""),
              array("name" => "Not Done", "label" => "Not Done", "description" => ""),
              array("name" => "Positive", "label" => "Positive", "description" => ""),
              array("name" => "Negative", "label" => "Negative", "description" => ""),
              array("name" => "Indeterminate", "label" => "Indeterminate", "description" => "")
          );
          foreach ($options as $option) {
              Option::create($option);
          }
          $this->command->info('Options table seeded');

          /*  Field Options */
          $foptions = array(
              array("field_id" => "4", "option_id" => "1"),
              array("field_id" => "4", "option_id" => "2"),
              array("field_id" => "4", "option_id" => "3"),
              array("field_id" => "4", "option_id" => "4"),

              array("field_id" => "7", "option_id" => "1"),
              array("field_id" => "7", "option_id" => "2"),
              array("field_id" => "7", "option_id" => "3"),
              array("field_id" => "7", "option_id" => "4"),

              array("field_id" => "10", "option_id" => "1"),
              array("field_id" => "10", "option_id" => "2"),
              array("field_id" => "10", "option_id" => "3"),
              array("field_id" => "10", "option_id" => "4"),

              array("field_id" => "13", "option_id" => "5"),
              array("field_id" => "13", "option_id" => "6"),
              array("field_id" => "13", "option_id" => "7"),
              array("field_id" => "13", "option_id" => "8"),
              array("field_id" => "14", "option_id" => "5"),
              array("field_id" => "14", "option_id" => "6"),
              array("field_id" => "14", "option_id" => "7"),
              array("field_id" => "14", "option_id" => "8"),
              array("field_id" => "15", "option_id" => "5"),
              array("field_id" => "15", "option_id" => "6"),
              array("field_id" => "15", "option_id" => "7"),
              array("field_id" => "15", "option_id" => "8"),
              array("field_id" => "16", "option_id" => "9"),
              array("field_id" => "16", "option_id" => "10"),
              array("field_id" => "16", "option_id" => "11"),
              array("field_id" => "16", "option_id" => "7"),
              array("field_id" => "16", "option_id" => "8"),

              array("field_id" => "17", "option_id" => "5"),
              array("field_id" => "17", "option_id" => "6"),
              array("field_id" => "17", "option_id" => "7"),
              array("field_id" => "17", "option_id" => "8"),
              array("field_id" => "18", "option_id" => "5"),
              array("field_id" => "18", "option_id" => "6"),
              array("field_id" => "18", "option_id" => "7"),
              array("field_id" => "18", "option_id" => "8"),
              array("field_id" => "19", "option_id" => "5"),
              array("field_id" => "19", "option_id" => "6"),
              array("field_id" => "19", "option_id" => "7"),
              array("field_id" => "19", "option_id" => "8"),
              array("field_id" => "20", "option_id" => "9"),
              array("field_id" => "20", "option_id" => "10"),
              array("field_id" => "20", "option_id" => "11"),
              array("field_id" => "20", "option_id" => "7"),
              array("field_id" => "20", "option_id" => "8"),

              array("field_id" => "21", "option_id" => "5"),
              array("field_id" => "21", "option_id" => "6"),
              array("field_id" => "21", "option_id" => "7"),
              array("field_id" => "21", "option_id" => "8"),
              array("field_id" => "22", "option_id" => "5"),
              array("field_id" => "22", "option_id" => "6"),
              array("field_id" => "22", "option_id" => "7"),
              array("field_id" => "22", "option_id" => "8"),
              array("field_id" => "23", "option_id" => "5"),
              array("field_id" => "23", "option_id" => "6"),
              array("field_id" => "23", "option_id" => "7"),
              array("field_id" => "23", "option_id" => "8"),
              array("field_id" => "24", "option_id" => "9"),
              array("field_id" => "24", "option_id" => "10"),
              array("field_id" => "24", "option_id" => "11"),
              array("field_id" => "24", "option_id" => "7"),
              array("field_id" => "24", "option_id" => "8"),

              array("field_id" => "25", "option_id" => "5"),
              array("field_id" => "25", "option_id" => "6"),
              array("field_id" => "25", "option_id" => "7"),
              array("field_id" => "25", "option_id" => "8"),
              array("field_id" => "26", "option_id" => "5"),
              array("field_id" => "26", "option_id" => "6"),
              array("field_id" => "26", "option_id" => "7"),
              array("field_id" => "26", "option_id" => "8"),
              array("field_id" => "27", "option_id" => "5"),
              array("field_id" => "27", "option_id" => "6"),
              array("field_id" => "27", "option_id" => "7"),
              array("field_id" => "27", "option_id" => "8"),
              array("field_id" => "28", "option_id" => "9"),
              array("field_id" => "28", "option_id" => "10"),
              array("field_id" => "28", "option_id" => "11"),
              array("field_id" => "28", "option_id" => "7"),
              array("field_id" => "28", "option_id" => "8"),

              array("field_id" => "29", "option_id" => "5"),
              array("field_id" => "29", "option_id" => "6"),
              array("field_id" => "29", "option_id" => "7"),
              array("field_id" => "29", "option_id" => "8"),
              array("field_id" => "30", "option_id" => "5"),
              array("field_id" => "30", "option_id" => "6"),
              array("field_id" => "30", "option_id" => "7"),
              array("field_id" => "30", "option_id" => "8"),
              array("field_id" => "31", "option_id" => "5"),
              array("field_id" => "31", "option_id" => "6"),
              array("field_id" => "31", "option_id" => "7"),
              array("field_id" => "31", "option_id" => "8"),
              array("field_id" => "32", "option_id" => "9"),
              array("field_id" => "32", "option_id" => "10"),
              array("field_id" => "32", "option_id" => "11"),
              array("field_id" => "32", "option_id" => "7"),
              array("field_id" => "32", "option_id" => "8"),

              array("field_id" => "33", "option_id" => "5"),
              array("field_id" => "33", "option_id" => "6"),
              array("field_id" => "33", "option_id" => "7"),
              array("field_id" => "33", "option_id" => "8"),
              array("field_id" => "34", "option_id" => "5"),
              array("field_id" => "34", "option_id" => "6"),
              array("field_id" => "34", "option_id" => "7"),
              array("field_id" => "34", "option_id" => "8"),
              array("field_id" => "35", "option_id" => "5"),
              array("field_id" => "35", "option_id" => "6"),
              array("field_id" => "35", "option_id" => "7"),
              array("field_id" => "35", "option_id" => "8"),
              array("field_id" => "36", "option_id" => "9"),
              array("field_id" => "36", "option_id" => "10"),
              array("field_id" => "36", "option_id" => "11"),
              array("field_id" => "36", "option_id" => "7"),
              array("field_id" => "36", "option_id" => "8"),
          );
          foreach ($foptions as $foption) {
              DB::table('field_options')->insert($foption);
          }
          $this->command->info('Field Options table seeded');
    }
}
