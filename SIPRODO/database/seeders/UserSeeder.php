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
            'nidn' => '0000000000',
            'nip' => '000000000000000000',
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
            'nidn' => '0401018901',
            'nip' => '198901012015042001',
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
            'nidn' => '0402019001',
            'nip' => '199001022016042001',
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
            'nidn' => '0403019101',
            'nip' => '199101032017042001',
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
            'nidn' => '0404019201',
            'nip' => '199201042018042001',
            'phone' => '081234567894',
            'position' => 'Dosen',
            'department' => 'Sistem Informasi',
            'is_active' => true,
        ]);
    }
}

