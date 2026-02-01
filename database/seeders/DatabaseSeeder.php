<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'ผู้ดูแลระบบ',
            'email' => 'admin@schoolcoop.com',
            'password' => Hash::make('password'),
        ]);
    }
}
