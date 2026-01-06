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
            $table->string('sdg')->nullable();
            $table->string('kesesuaian_roadmap_kk')->nullable();
            $table->string('tipe_pendanaan')->nullable();
            $table->string('status_kegiatan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengabdian_masyarakat', function (Blueprint $table) {
            $table->dropColumn(['sdg', 'kesesuaian_roadmap_kk', 'tipe_pendanaan', 'status_kegiatan']);
        });
    }
};
