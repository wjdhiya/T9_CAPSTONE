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
            $table->renameColumn('jenis', 'jenis_hibah');
            $table->renameColumn('anggota', 'tim_abdimas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengabdian_masyarakat', function (Blueprint $table) {
            $table->renameColumn('jenis_hibah', 'jenis');
            $table->renameColumn('tim_abdimas', 'anggota');
        });
    }
};
