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
        // Admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@telkomuniversity.ac.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'nip' => '0000000000',
            'phone' => '081234567890',
            'position' => 'Administrator',
            'department' => 'Sistem Informasi',
            'is_active' => true,
        ]);

        // Kaprodi
        User::create([
            'name' => 'Dr. Ibu Kaprodi',
            'email' => 'kaprodi@telkomuniversity.ac.id',
            'password' => Hash::make('password'),
            'role' => 'kaprodi',
            'nip' => '0401018901',
            'phone' => '081234567891',
            'position' => 'Kepala Program Studi',
            'department' => 'Sistem Informasi',
            'is_active' => true,
        ]);

        // Dosen 1
        User::create([
            'name' => 'Dr. Dosen Satu, M.Kom',
            'email' => 'dosen1@telkomuniversity.ac.id',
            'password' => Hash::make('password'),
            'role' => 'dosen',
            'nip' => '0402019001',
            'phone' => '081234567892',
            'position' => 'Dosen',
            'department' => 'Sistem Informasi',
            'is_active' => true,
        ]);

        // Dosen 2
        User::create([
            'name' => 'Dosen Dua, S.Kom., M.T',
            'email' => 'dosen2@telkomuniversity.ac.id',
            'password' => Hash::make('password'),
            'role' => 'dosen',
            'nip' => '0403019101',
            'phone' => '081234567893',
            'position' => 'Dosen',
            'department' => 'Sistem Informasi',
            'is_active' => true,
        ]);

        // Dosen 3
        User::create([
            'name' => 'Dosen Tiga, S.T., M.Kom',
            'email' => 'dosen3@telkomuniversity.ac.id',
            'password' => Hash::make('password'),
            'role' => 'dosen',
            'nip' => '0404019201',
            'phone' => '081234567894',
            'position' => 'Dosen',
            'department' => 'Sistem Informasi',
            'is_active' => true,
        ]);
    }
}

