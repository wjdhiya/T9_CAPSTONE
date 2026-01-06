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
            $table->renameColumn('tahun_akademik', 'tahun');
        });

        Schema::table('publikasi', function (Blueprint $table) {
            $table->renameColumn('tahun_akademik', 'tahun');
        });

        Schema::table('pengabdian_masyarakat', function (Blueprint $table) {
            $table->renameColumn('tahun_akademik', 'tahun');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penelitian', function (Blueprint $table) {
            $table->renameColumn('tahun', 'tahun_akademik');
        });

        Schema::table('publikasi', function (Blueprint $table) {
            $table->renameColumn('tahun', 'tahun_akademik');
        });

        Schema::table('pengabdian_masyarakat', function (Blueprint $table) {
            $table->renameColumn('tahun', 'tahun_akademik');
        });
    }
};
