<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Publikasi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'publikasi';

    protected $fillable = [
        'user_id',
        'penelitian_id',
        'judul_publikasi',
        'abstrak',
        'jenis',
        'nama_publikasi',
        'penerbit',
        'issn_isbn',
        'volume',
        'nomor',
        'halaman',
        'tahun',
        'semester',
        'tanggal_terbit',
        'quartile',
        'indexing',
        'doi',
        'url',
        'file_publikasi',
        'penulis',
        'mahasiswa_terlibat',
        'catatan',
        'status_verifikasi',
        'catatan_verifikasi',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'penulis' => 'array',
        'mahasiswa_terlibat' => 'array',
        'tanggal_terbit' => 'date',
        'verified_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function penelitian()
    {
        return $this->belongsTo(Penelitian::class);
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // Scopes
    public function scopeVerified($query)
    {
        return $query->where('status_verifikasi', 'verified');
    }

    public function scopePending($query)
    {
        return $query->where('status_verifikasi', 'pending');
    }

    public function scopeByYear($query, $year)
    {
        return $query->where('tahun', 'like', "%$year%");
    }

    public function scopeBySemester($query, $semester)
    {
        return $query->where('semester', $semester);
    }

    public function scopeByIndexing($query, $indexing)
    {
        return $query->where('indexing', $indexing);
    }

    public function scopeRentangTahun($query, int $startYear)
    {
        return $query->where(function ($q) use ($startYear) {
            for ($year = $startYear; $year <= now()->year; $year++) {
                $q->orWhere('tahun', 'like', $year . '%');
            }
        });
    }
}

