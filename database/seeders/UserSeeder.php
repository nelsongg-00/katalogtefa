<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Super Admin
        User::create([
            'name'     => 'Super Admin TeFa',
            'email'    => 'superadmin@tefa.com',
            'password' => Hash::make('password123'),
            'role'     => 'super_admin',
        ]);

        // 2. Admin Jurusan
        User::create([
            'name'     => 'Admin Jurusan RPL',
            'email'    => 'admin.rpl@tefa.com',
            'password' => Hash::make('password123'),
            'role'     => 'admin_jurusan',
        ]);

        // 3. Worker (Siswa/Pekerja TeFa)
        User::create([
            'name'     => 'Worker Budi',
            'email'    => 'worker@tefa.com',
            'password' => Hash::make('password123'),
            'role'     => 'worker',
        ]);

        // 4. Pelanggan
        User::create([
            'name'     => 'Pelanggan Umum',
            'email'    => 'pelanggan@gmail.com',
            'password' => Hash::make('password123'),
            'role'     => 'pelanggan',
        ]);
    }
}