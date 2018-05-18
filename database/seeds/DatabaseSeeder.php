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
        $this->call(UsersTableSeeder::class);
        $this->call(ParametersTableSeeder::class);
        $this->call(PagesTableSeeder::class);
        $this->call(EcommerceTableSeeder::class);
        $this->call(GdprTableSeeder::class);
    }
}
