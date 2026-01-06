<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pengabdian_masyarakat', function (Blueprint $table) {
            $table->text('dosen_nip')->nullable()->after('anggota'); // JSON array of NIP dosen
            $table->text('mahasiswa_nim')->nullable()->after('mahasiswa_terlibat'); // JSON array of NIM mahasiswa
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengabdian_masyarakat', function (Blueprint $table) {
            $table->dropColumn(['dosen_nip', 'mahasiswa_nim']);
        });
    }
};
