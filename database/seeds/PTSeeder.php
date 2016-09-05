<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Permission;
use App\Models\Role;

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

            array("name" => "assign-role", "display_name" => "Can assign role"),
        );
        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
        $this->command->info('Permissions table seeded');
        /* Roles table */
        $roles = array(
            array("name" => "Superadmin", "display_name" => "Overall Administrator")
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
        $mandara = County::create(array("name" => "Mandera"));
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
            array("name" => "Wajir East", "county_id" => $wajr->id),
            array("name" => "Tarbaj", "county_id" => $wajr->id),
            array("name" => "Eldas", "county_id" => $wajr->id),
            array("name" => "Wajir West", "county_id" => $wajr->id),
            array("name" => "Habaswein", "county_id" => $wajr->id),
            array("name" => "Wajir South", "county_id" => $wajr->id),
            array("name" => "Wajir North", "county_id" => $wajr->id),
            array("name" => "Buna", "county_id" => $wajr->id),

            //  West Pokot
            array("name" => "West Pokot", "county_id" => $pokot->id),
            array("name" => "South Pokot", "county_id" => $pokot->id),
            array("name" => "Pokot Central", "county_id" => $pokot->id),
            array("name" => "North Pokot", "county_id" => $pokot->id),
          );
    }
}
