<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create 10 users using model factory
        factory(\App\User::class, 10)->create();
        //  Remember users must be created first because of the user-id fk
        factory(\App\Product::class, 50)->create();
    }
}
