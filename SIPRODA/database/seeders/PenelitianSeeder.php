<?php

namespace Database\Seeders;

use App\Models\Penelitian;
use App\Models\User;
use Illuminate\Database\Seeder;

class PenelitianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dosen = User::where('role', 'dosen')->get();

        $penelitianData = [
            [
                'user_id' => $dosen[0]->id ?? 3,
                'judul' => 'Implementasi Machine Learning untuk Prediksi Churn Rate Pelanggan Telekomunikasi',
                'abstrak' => 'Penelitian ini bertujuan untuk mengembangkan model machine learning yang dapat memprediksi churn rate pelanggan pada industri telekomunikasi dengan akurasi tinggi.',
                'jenis' => 'internal',
                'sumber_dana' => 'Hibah Internal Telkom University',
                'dana' => 15000000,
                'tahun' => 2024,
                'semester' => 'ganjil',
                'tanggal_mulai' => '2024-08-01',
                'tanggal_selesai' => '2024-12-31',
                'status' => 'berjalan',
                'anggota' => ['Dr. Dosen Dua, M.T', 'Dosen Tiga, M.Kom'],
                'mahasiswa_terlibat' => ['Ahmad Rizki (1301210001)', 'Siti Nurhaliza (1301210002)'],
                'status_verifikasi' => 'verified',
                'verified_by' => 2,
                'verified_at' => now(),
            ],
            [
                'user_id' => $dosen[1]->id ?? 4,
                'judul' => 'Pengembangan Sistem Informasi Manajemen Berbasis Cloud Computing',
                'abstrak' => 'Penelitian ini fokus pada pengembangan sistem informasi manajemen yang memanfaatkan teknologi cloud computing untuk meningkatkan efisiensi operasional.',
                'jenis' => 'eksternal',
                'sumber_dana' => 'Kemenristekdikti',
                'dana' => 50000000,
                'tahun' => 2024,
                'semester' => 'genap',
                'tanggal_mulai' => '2024-02-01',
                'tanggal_selesai' => '2024-07-31',
                'status' => 'selesai',
                'anggota' => ['Dr. Dosen Satu, M.Kom'],
                'mahasiswa_terlibat' => ['Budi Santoso (1301210003)', 'Dewi Lestari (1301210004)'],
                'status_verifikasi' => 'verified',
                'verified_by' => 2,
                'verified_at' => now(),
            ],
            [
                'user_id' => $dosen[2]->id ?? 5,
                'judul' => 'Analisis Keamanan Sistem IoT pada Smart Home',
                'abstrak' => 'Penelitian ini menganalisis berbagai aspek keamanan pada sistem Internet of Things (IoT) yang diterapkan pada smart home.',
                'jenis' => 'mandiri',
                'sumber_dana' => 'Dana Mandiri',
                'dana' => 5000000,
                'tahun' => 2024,
                'semester' => 'ganjil',
                'tanggal_mulai' => '2024-09-01',
                'tanggal_selesai' => '2025-01-31',
                'status' => 'berjalan',
                'anggota' => [],
                'mahasiswa_terlibat' => ['Eko Prasetyo (1301210005)'],
                'status_verifikasi' => 'pending',
            ],
        ];

        foreach ($penelitianData as $data) {
            Penelitian::create($data);
        }
    }
}

