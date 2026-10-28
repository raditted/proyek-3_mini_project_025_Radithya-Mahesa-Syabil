<?php

namespace Database\Seeders;

use App\Enums\UserRole;
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
        $users = [
            [
                'name' => 'Koordinator Amba',
                'email' => 'koordinator@gmail.com',
                'password' => Hash::make('password'),
                'role' => UserRole::Koordinator,
            ],
            [
                'name' => 'Pemantau Fuad',
                'email' => 'pemantau@gmail.com',
                'password' => Hash::make('password'),
                'role' => UserRole::Pemantau,
            ],
            [
                'name' => 'Bu Suki',
                'email' => 'suki@gmail.com',
                'password' => Hash::make('password'),
                'role' => UserRole::Pelapor,
            ],
            [
                'name' => 'Pa Nikga',
                'email' => 'panikga@gmail.com',
                'password' => Hash::make('password'),
                'role' => UserRole::Pelapor,
            ],
        ];
        foreach ($users as $user) {
            User::create($user);
        }
    }
}
