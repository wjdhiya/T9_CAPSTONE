<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ExtraUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            // [NIP, Name placeholder]
            ['07800068', 'Dosen 07800068'],
            ['23870015', 'Dosen 23870015'],
            ['15890025', 'Dosen 15890025'],
            ['23880013', 'Dosen 23880013'],
            ['20890020', 'Dosen 20890020'],
            ['14650011', 'Dosen 14650011'],
            ['23929017', 'Dosen 23929017'],
            ['20710005', 'Dosen 20710005'],
            ['00760016', 'Dosen 00760016'],
            ['14630009', 'Dosen 14630009'],
            ['20930052', 'Dosen 20930052'],
            ['23940022', 'Dosen 23940022'],
        ];

        foreach ($users as $u) {
            $nip = $u[0];
            $name = $u[1];

            // Generate email from NIP
            $email = $nip . '@telkomuniversity.ac.id';

            User::updateOrCreate(
                ['nip' => $nip],
                [
                    'name' => $name,
                    'email' => $email,
                    'password' => Hash::make('password'),
                    'role' => 'dosen',
                    'department' => 'Sistem Informasi',
                    'is_active' => true,
                ]
            );
        }
    }
}
