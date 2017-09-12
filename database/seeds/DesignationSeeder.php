<?php

use Illuminate\Database\Seeder;
use App\Designation;

class DesignationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* Designations table */
        $designations = array(
            array("name" => "Nurse", "description" => ""),
            array("name" => "Counsellor", "description" => ""),
            array("name" => "Lab Tech.", "description" => "Lab Technologist"),
            array("name" => "RCO", "description" => ""),
            array("name" => "Nutritionist", "description" => ""),
            array("name" => "Doctor", "description" => ""),
            array("name" => "Physiotherapist", "description" => ""),
            array("name" => "Clinical Officer", "description" => ""),
            array("name" => "Pharmacist", "description" => "")
        );
        foreach ($designations as $designation)
        {
            Designation::create($designation);
        }
        $this->command->info('Designations table seeded');
    }
}
