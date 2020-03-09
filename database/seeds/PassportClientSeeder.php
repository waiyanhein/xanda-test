<?php

use Illuminate\Database\Seeder;

class PassportClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Illuminate\Support\Facades\DB::table('oauth_clients')->insert([
            [
                'name' => 'Laravel Personal Access Client',
                'secret' => 'XGePat23p90f7VP4p3EOaePl8gqLZlGO47uwDP26',
                'redirect' => 'http://localhost',
                'personal_access_client' => 1,
                'password_client' => 0,
                'revoked' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Laravel Password Grant Client',
                'secret' => 'cjW4TBGkYJmoR7e4iMcciNgeBPAoQYWlXp4dPsuY',
                'redirect' => 'http://localhost',
                'personal_access_client' => 0,
                'password_client' => 1,
                'revoked' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
