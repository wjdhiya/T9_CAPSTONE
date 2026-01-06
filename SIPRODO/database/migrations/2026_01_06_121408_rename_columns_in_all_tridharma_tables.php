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
        Schema::table('penelitian', function (Blueprint $table) {
            $table->renameColumn('dana', 'anggaran');
        });

        Schema::table('pengabdian_masyarakat', function (Blueprint $table) {
            $table->renameColumn('anggota_terlibat', 'anggota_mahasiswa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penelitian', function (Blueprint $table) {
            $table->renameColumn('anggaran', 'dana');
        });

        Schema::table('pengabdian_masyarakat', function (Blueprint $table) {
            $table->renameColumn('anggota_mahasiswa', 'anggota_terlibat');
        });
    }
};
