<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Permission;
use App\Role;
use App\County;
use App\SubCounty;
use App\Program;
use App\Set;
use App\Field;
use App\Option;
use App\Notification;
use App\Questionnaire;

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

            array("name" => "create-notification", "display_name" => "Can create option"),
            array("name" => "read-notification", "display_name" => "Can read notification"),
            array("name" => "view-notification", "display_name" => "Can view notification"),
            array("name" => "update-notification", "display_name" => "Can update notification"),
            array("name" => "delete-notification", "display_name" => "Can delete notification"),

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
            Permission::create($permission);
        }
        $this->command->info('Permissions table seeded');
        /* Roles table */
        $roles = array(
            array("name" => "Superadmin", "display_name" => "Overall Administrator"),
            array("name" => "Participant", "display_name" => "Participant"),
            array("name" => "Partner Admin", "display_name" => "Partner Admin"),
            array("name" => "County Lab Coordinator", "display_name" => "County Lab Coordinator")
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

        /* Programs table */
        $programs = array(
            array("name" => "Lab", "description" => "Laboratory"),
            array("name" => "PMTCT", "description" => "Prevention of Mother To Child Transmission of HIV/AIDS"),
            array("name" => "PSC/CCC", "description" => "Patient Support Center"),
            array("name" => "VCT", "description" => "Voluntary Counselling and Testing"),
            array("name" => "VMMC", "description" => "Voluntary Male Medical Circumcision"),
            array("name" => "PITC", "description" => "Patient Information and Testing Center"),
            array("name" => "HBTC", "description" => "Home-Based Testing and Counselling")
        );
        foreach ($programs as $program)
        {
            Program::create($program);
        }
        $this->command->info('Programs table seeded');

        /* Counties table */
        $counties = array(
            array("name" => "Mombasa"),
            array("name" => "Kwale"),
            array("name" => "Kilifi"),
            array("name" => "Tana River"),
            array("name" => "Lamu"),
            array("name" => "Taita Taveta"),
            array("name" => "Garissa"),
            array("name" => "Wajir"),
            array("name" => "Mandera"),
            array("name" => "Marsabit"),
            array("name" => "Isiolo"),
            array("name" => "Meru"),
            array("name" => "Tharaka Nithi"),
            array("name" => "Embu"),
            array("name" => "Kitui"),
            array("name" => "Machakos"),
            array("name" => "Makueni"),
            array("name" => "Nyandarua"),
            array("name" => "Nyeri"),
            array("name" => "Kirinyaga"),
            array("name" => "Murang\'a"),
            array("name" => "Kiambu"),
            array("name" => "Turkana"),
            array("name" => "West Pokot"),
            array("name" => "Samburu"),
            array("name" => "Trans Nzoia"),
            array("name" => "Uasin Gishu"),
            array("name" => "Elgeyo Marakwet"),
            array("name" => "Nandi"),
            array("name" => "Baringo"),
            array("name" => "Laikipia"),
            array("name" => "Nakuru"),
            array("name" => "Narok"),
            array("name" => "Kajiado"),
            array("name" => "Kericho"),
            array("name" => "Bomet"),
            array("name" => "Kakamega"),
            array("name" => "Vihiga"),
            array("name" => "Bungoma"),
            array("name" => "Busia"),
            array("name" => "Siaya"),
            array("name" => "Kisumu"),
            array("name" => "Homa Bay"),
            array("name" => "Migori"),
            array("name" => "Kisii"),
            array("name" => "Nyamira"),
            array("name" => "Nairobi")
        );
        foreach ($counties as $county)
        {
            County::create($county);
        }
        $this->command->info('Counties table seeded');

        /* Notifications table */
        $notifications = array(
            array("template" => Notification::PANEL_DISPATCH, "message" => "Dear PT Participant, NHRL has dispatched your PT Panel for Round [round]. If not received within 7 days, contact NHRL on 0722934622 or nhrlpt@gmail.com"),
            array("template" => Notification::RESULTS_RECEIVED, "message" => "Dear PT Participant, NHRL has received your PT Results for Round [round]. You will get your feedback shortly."),
            array("template" => Notification::FEEDBACK_RELEASE, "message" => "Dear PT Participant, NHRL has released your PT Feedback for Round [round]. If not received within 7 days, contact NHRL on 0722934622 or nhrlpt@gmail.com"),            
        );
        foreach ($notifications as $notification)
        {
            Notification::create($notification);
        }
        $this->command->info('Notifications table seeded');
        /* Options table */
        $options = array(
            array("title" => "KHB", "description" => ""),
            array("title" => "First Response", "description" => ""),
            array("title" => "Unigold", "description" => ""),
            array("title" => "Other", "description" => ""),
            array("title" => "Reactive", "description" => ""),
            array("title" => "Non-Reactive", "description" => ""),
            array("title" => "Invalid", "description" => ""),
            array("title" => "Not Done", "description" => ""),
            array("title" => "Positive", "description" => ""),
            array("title" => "Negative", "description" => ""),
            array("title" => "Indeterminate", "description" => ""),
            array("title" => "Transferred", "description" => ""),
            array("title" => "Resigned", "description" => ""),
            array("title" => "On Leave", "description" => ""),
            array("title" => "Off Duty", "description" => ""),
            array("title" => "Deceased", "description" => "")
        );
        foreach ($options as $option)
        {
            Option::create($option);
        }
        $this->command->info('Options table seeded');
        /* Questionnaires table */
        $questionnaires = array(
            array("title" => "Results", "description" => "Test results entry form"),
            array("title" => "Addressee Failure", "description" => "Addressee failure to perform tests")
        );
        foreach ($questionnaires as $questionnaire)
        {
            Questionnaire::create($questionnaire);
        }
        $this->command->info('Questionnaires table seeded');
        /* Field sets table */
        $sets = array(
            array("title" => "PT Panel Dates", "description" => "", "order" => "0", "questionnaire_id" => "1"),
            array("title" => "Test 1", "description" => "", "order" => "1", "questionnaire_id" => "1"),
            array("title" => "Test 2", "description" => "", "order" => "2", "questionnaire_id" => "1"),
            array("title" => "Test 3", "description" => "", "order" => "3", "questionnaire_id" => "1"),
            array("title" => "PT Panel 1", "description" => "Test Results", "order" => "4", "questionnaire_id" => "1"),
            array("title" => "PT Panel 2", "description" => "Test Results", "order" => "5", "questionnaire_id" => "1"),
            array("title" => "PT Panel 3", "description" => "Test Results", "order" => "6", "questionnaire_id" => "1"),
            array("title" => "PT Panel 4", "description" => "Test Results", "order" => "7", "questionnaire_id" => "1"),
            array("title" => "PT Panel 5", "description" => "Test Results", "order" => "8", "questionnaire_id" => "1"),
            array("title" => "PT Panel 6", "description" => "Test Results", "order" => "9", "questionnaire_id" => "1"),
            array("title" => "Remarks", "description" => "Remarks", "order" => "10", "questionnaire_id" => "1"),
            array("title" => "Addressee Non-Performance", "description" => "Addressee Non-Performance", "order" => "0", "questionnaire_id" => "2")
        );
        foreach ($sets as $set)
        {
            Set::create($set);
        }
        $this->command->info('Field Sets table seeded');
        /* Dummy bulk sms settings */
        DB::table('bulk_sms_settings')->insert(array("code" => "talking", "username" => "africa", "api_key" => "LBD239F81V", "created_at" => $now, "updated_at" => $now));

        $this->command->info('Bulk SMS Settings table seeded');
    }
}
