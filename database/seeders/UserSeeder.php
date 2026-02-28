<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['username' => 'aldmic'],
            [
                'name'     => 'Aldmic',
                'email'    => 'aldmic@movieexplorer.com',
                'username' => 'aldmic',
                'password' => Hash::make('123abc123'),
            ]
        );
    }
}
