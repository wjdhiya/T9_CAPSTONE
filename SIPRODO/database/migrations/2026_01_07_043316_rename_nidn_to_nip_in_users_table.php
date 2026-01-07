<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop the old 'nip' column (assumed to be the incorrect/unused 18-digit one)
            if (Schema::hasColumn('users', 'nip')) {
                $table->dropColumn('nip');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            // Rename 'nidn' (short ID) to 'nip'
            if (Schema::hasColumn('users', 'nidn')) {
                $table->renameColumn('nidn', 'nip');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Rename 'nip' back to 'nidn'
            if (Schema::hasColumn('users', 'nip')) {
                $table->renameColumn('nip', 'nidn');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            // Add 'nip' column back
            if (!Schema::hasColumn('users', 'nip')) {
                $table->string('nip')->nullable()->after('nidn');
            }
        });
    }
};
