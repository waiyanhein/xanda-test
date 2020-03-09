<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(FleetSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(SpacecraftSeeder::class);
        $this->call(ArmamentSeeder::class);
        $this->call(PassportClientSeeder::class);
    }
}
