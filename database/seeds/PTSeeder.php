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
    	$now = Carbon::now('Africa/Nairobi');
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

            array("name" => "user-management", "display_name" => "Can manage users"),
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

            array("name" => "facility-catalog", "display_name" => "Can manage facility catalog"),
            array("name" => "create-facility", "display_name" => "Can create facility"),
            array("name" => "read-facility", "display_name" => "Can read facility"),
            array("name" => "update-facility", "display_name" => "Can update facility"),
            array("name" => "delete-facility", "display_name" => "Can delete facility"),

            array("name" => "program-management", "display_name" => "Can manage program"),
            array("name" => "create-set", "display_name" => "Can create field set"),
            array("name" => "read-set", "display_name" => "Can read field set"),
            array("name" => "view-set", "display_name" => "Can view field set"),
            array("name" => "update-set", "display_name" => "Can update field set"),
            array("name" => "delete-set", "display_name" => "Can delete field set"),
            array("name" => "create-field", "display_name" => "Can create field"),
            array("name" => "read-field", "display_name" => "Can read field"),
            array("name" => "view-field", "display_name" => "Can view field"),
            array("name" => "update-field", "display_name" => "Can update field"),
            array("name" => "delete-field", "display_name" => "Can delete field"),
            array("name" => "create-option", "display_name" => "Can create option"),
            array("name" => "read-option", "display_name" => "Can read option"),
            array("name" => "view-option", "display_name" => "Can view option"),
            array("name" => "update-option", "display_name" => "Can update option"),
            array("name" => "delete-option", "display_name" => "Can delete option"),

            array("name" => "proficiency-testing", "display_name" => "Can manage proficiency testing"),
            array("name" => "create-program", "display_name" => "Can create program"),
            array("name" => "read-program", "display_name" => "Can read program"),
            array("name" => "view-program", "display_name" => "Can view program"),
            array("name" => "update-program", "display_name" => "Can update program"),
            array("name" => "delete-program", "display_name" => "Can delete program"),
            array("name" => "create-shipper", "display_name" => "Can create shipper"),
            array("name" => "read-shipper", "display_name" => "Can read shipper"),
            array("name" => "view-shipper", "display_name" => "Can view shipper"),
            array("name" => "update-shipper", "display_name" => "Can update shipper"),
            array("name" => "delete-shipper", "display_name" => "Can delete shipper"),
            array("name" => "create-sample", "display_name" => "Can create sample"),
            array("name" => "read-sample", "display_name" => "Can read sample"),
            array("name" => "view-sample", "display_name" => "Can view sample"),
            array("name" => "update-sample", "display_name" => "Can update sample"),
            array("name" => "delete-sample", "display_name" => "Can delete sample"),
            array("name" => "create-round", "display_name" => "Can create round"),
            array("name" => "read-round", "display_name" => "Can read round"),
            array("name" => "view-round", "display_name" => "Can view round"),
            array("name" => "update-round", "display_name" => "Can update round"),
            array("name" => "delete-round", "display_name" => "Can delete round"),
            array("name" => "create-item", "display_name" => "Can create item"),
            array("name" => "read-item", "display_name" => "Can read item"),
            array("name" => "view-item", "display_name" => "Can view item"),
            array("name" => "update-item", "display_name" => "Can update item"),
            array("name" => "delete-item", "display_name" => "Can delete item"),
            array("name" => "create-result", "display_name" => "Can create result"),
            array("name" => "read-result", "display_name" => "Can read result"),
            array("name" => "view-result", "display_name" => "Can view result"),
            array("name" => "update-result", "display_name" => "Can update result"),
            array("name" => "delete-result", "display_name" => "Can delete result"),
            array("name" => "create-shipment", "display_name" => "Can create shipment"),
            array("name" => "read-shipment", "display_name" => "Can read shipment"),
            array("name" => "view-shipment", "display_name" => "Can view shipment"),
            array("name" => "update-shipment", "display_name" => "Can update shipment"),
            array("name" => "delete-shipment", "display_name" => "Can delete shipment"),
            array("name" => "create-receipt", "display_name" => "Can create receipt"),
            array("name" => "read-receipt", "display_name" => "Can read receipt"),
            array("name" => "view-receipt", "display_name" => "Can view receipt"),
            array("name" => "update-receipt", "display_name" => "Can update receipt"),
            array("name" => "delete-receipt", "display_name" => "Can delete receipt"),
            array("name" => "create-expected", "display_name" => "Can create expected"),
            array("name" => "read-expected", "display_name" => "Can read expected"),
            array("name" => "view-expected", "display_name" => "Can view expected"),
            array("name" => "update-expected", "display_name" => "Can update expected"),
            array("name" => "delete-expected", "display_name" => "Can delete expected"),

            array("name" => "bulk-sms", "display_name" => "Can manage bulk SMS"),
        );
        foreach ($permissions as $permission) {
            //Permission::create($permission);
        }
        $this->command->info('Permissions table seeded');
        /* Roles table */
      /*  $roles = array(
            array("name" => "Superadmin", "display_name" => "Overall Administrator"),
            array("name" => "Participant", "display_name" => "Participant"),
            array("name" => "Partner Admin", "display_name" => "Partner Admin"),
            array("name" => "County Lab Coordinator", "display_name" => "County Lab Coordinator")
        );
        foreach ($roles as $role)
        {
            Role::create($role);
        }
        $this->command->info('Roles table seeded');*/

        $role1 = Role::find(1);
        $permissions = Permission::all();

        //Assign all permissions to role administrator
        foreach ($permissions as $permission)
        {
            //$role1->attachPermission($permission);
        }
        //Assign role Superadmin to all permissions
        User::find(1)->attachRole($role1);
    }
}
