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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['super_admin', 'kaprodi', 'dosen'])->default('dosen')->after('email');
            $table->string('nidn')->nullable()->after('role');
            $table->string('nip')->nullable()->after('nidn');
            $table->string('phone')->nullable()->after('nip');
            $table->string('position')->nullable()->after('phone');
            $table->string('department')->default('Sistem Informasi')->after('position');
            $table->boolean('is_active')->default(true)->after('department');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'nidn', 'nip', 'phone', 'position', 'department', 'is_active']);
        });
    }
};

