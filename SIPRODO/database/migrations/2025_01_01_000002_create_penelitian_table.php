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
        Schema::create('penelitian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('judul');
            $table->text('abstrak')->nullable();
            $table->enum('jenis', ['internal', 'eksternal', 'mandiri']);
            $table->string('sumber_dana')->nullable();
            $table->decimal('dana', 15, 2)->nullable();
            $table->year('tahun_akademik');
            $table->enum('semester', ['ganjil', 'genap']);
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->enum('status', ['proposal', 'berjalan', 'selesai', 'ditolak'])->default('proposal');
            $table->string('file_proposal')->nullable();
            $table->string('file_laporan')->nullable();
            $table->text('anggota')->nullable(); // JSON array of team members
            $table->text('mahasiswa_terlibat')->nullable(); // JSON array of students
            $table->text('catatan')->nullable();
            $table->enum('status_verifikasi', ['pending', 'verified', 'rejected'])->default('pending');
            $table->text('catatan_verifikasi')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penelitian');
    }
};

