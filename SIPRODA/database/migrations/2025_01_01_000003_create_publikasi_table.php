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
        Schema::create('publikasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('penelitian_id')->nullable()->constrained('penelitian')->onDelete('set null');
            $table->string('judul');
            $table->text('abstrak')->nullable();
            $table->enum('jenis', ['jurnal', 'prosiding', 'buku', 'book_chapter', 'paten', 'hki']);
            $table->string('nama_publikasi'); // Nama jurnal/prosiding/penerbit
            $table->string('penerbit')->nullable();
            $table->string('issn_isbn')->nullable();
            $table->string('volume')->nullable();
            $table->string('nomor')->nullable();
            $table->string('halaman')->nullable();
            $table->year('tahun');
            $table->enum('semester', ['ganjil', 'genap']);
            $table->date('tanggal_terbit')->nullable();
            $table->enum('quartile', ['Q1', 'Q2', 'Q3', 'Q4', 'non-quartile'])->nullable();
            $table->enum('indexing', ['scopus', 'wos', 'sinta1', 'sinta2', 'sinta3', 'sinta4', 'sinta5', 'sinta6', 'non-indexed'])->nullable();
            $table->string('doi')->nullable();
            $table->string('url')->nullable();
            $table->string('file_publikasi')->nullable();
            $table->text('penulis')->nullable(); // JSON array of authors
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
        Schema::dropIfExists('publikasi');
    }
};

