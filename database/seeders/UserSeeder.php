<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::create([
            'email' => 'test@test.com',
            'password' => bcrypt('password'),
            'name' => 'test',
        ]);
        \App\Models\User::factory()->times(9)->create();
    }
}
