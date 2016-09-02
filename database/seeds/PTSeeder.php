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
    }
}
