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

        factory(\App\Product::class, 50)->create();
    }
}
