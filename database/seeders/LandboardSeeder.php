<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class LandboardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accountId = DB::table('accounts')->insertGetId([
            'username' => 'admin123',
            'email' => 'landboard123@example.com',
            'password' => Hash::make('Qw123456'),
            'role' => 'landboard',
            'is_first_login' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('landboards')->insert([
            'account_id' => $accountId,
            'name' => 'Faray Juan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
