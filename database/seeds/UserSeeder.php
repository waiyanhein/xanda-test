<?php

use Illuminate\Database\Seeder;
use App\Models\Fleet;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $imperialFleet = Fleet::first();

        factory(User::class)->create([
            'fleet_id' => $imperialFleet->id,
            'name' => 'R3-D3',
            'email' => 'general@xanda.net',
        ]);
    }
}
