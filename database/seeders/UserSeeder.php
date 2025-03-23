<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Lia Rahmawati',
            'email' => 'lia.rahmawati@polban.ac.id',
            'password' => Hash::make('liar123!#'),
            'role' => 'admin',
        ],);

        // Create regular user
        User::create([
            'name' => 'Farhan Muhammad Luthfi',
            'email' => 'farhan.muhammad.tif422@polban.ac.id',
            'password' => Hash::make('farh123!#'),
            'role' => 'user',
        ],);
    }
}