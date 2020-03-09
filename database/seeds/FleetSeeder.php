<?php

use Illuminate\Database\Seeder;
use App\Models\Fleet;

class FleetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Fleet::class)->create([
            'name' => 'imperial'
        ]);
    }
}
