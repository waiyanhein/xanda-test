<?php

use Illuminate\Database\Seeder;
use App\Models\Spacecraft;
use App\Models\Armament;

class ArmamentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $spacecrafts = Spacecraft::all();

        foreach ($spacecrafts as $spacecraft) {
            factory(Armament::class, rand(1, 5))->create([
                'spacecraft_id'=> $spacecraft->id,
            ]);
        }
    }
}
