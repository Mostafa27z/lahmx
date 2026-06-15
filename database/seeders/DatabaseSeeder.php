<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
   public function run(): void
{
    User::firstOrCreate(
        [
            'email' => 'admin@lahmax.com',
        ],
        [
            'name' => 'Admin',
            'phone' => '01000000000',
            'password' => 'password',
            'role' => 'admin',
        ]
    );
}
}