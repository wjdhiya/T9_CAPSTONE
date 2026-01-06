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
            $table->renameColumn('mahasiswa_terlibat', 'anggota_terlibat');
            $table->renameColumn('dana', 'anggaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengabdian_masyarakat', function (Blueprint $table) {
            $table->renameColumn('anggota_terlibat', 'mahasiswa_terlibat');
            $table->renameColumn('anggaran', 'dana');
        });
    }
};
