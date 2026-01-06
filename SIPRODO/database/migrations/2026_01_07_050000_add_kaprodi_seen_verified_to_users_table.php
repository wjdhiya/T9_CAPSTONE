<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('kaprodi_seen_penelitian_at')->nullable()->after('is_active');
            $table->timestamp('kaprodi_seen_publikasi_at')->nullable()->after('kaprodi_seen_penelitian_at');
            $table->timestamp('kaprodi_seen_pengmas_at')->nullable()->after('kaprodi_seen_publikasi_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'kaprodi_seen_penelitian_at',
                'kaprodi_seen_publikasi_at',
                'kaprodi_seen_pengmas_at',
            ]);
        });
    }
};
