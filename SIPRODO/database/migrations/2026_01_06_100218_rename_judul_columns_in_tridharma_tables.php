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
            $table->renameColumn('judul', 'judul_penelitian');
        });

        Schema::table('publikasi', function (Blueprint $table) {
            $table->renameColumn('judul', 'judul_publikasi');
        });

        Schema::table('pengabdian_masyarakat', function (Blueprint $table) {
            $table->renameColumn('judul', 'judul_pkm');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penelitian', function (Blueprint $table) {
            $table->renameColumn('judul_penelitian', 'judul');
        });

        Schema::table('publikasi', function (Blueprint $table) {
            $table->renameColumn('judul_publikasi', 'judul');
        });

        Schema::table('pengabdian_masyarakat', function (Blueprint $table) {
            $table->renameColumn('judul_pkm', 'judul');
        });
    }
};
