<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PengabdianMasyarakat extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pengabdian_masyarakat';

    protected $fillable = [
        'user_id',
        'judul',
        'deskripsi',
        'jenis',
        'sumber_dana',
        'dana',
        'tahun_akademik',
        'semester',
        'tanggal_mulai',
        'tanggal_selesai',
        'lokasi',
        'mitra',
        'jumlah_peserta',
        'status',
        'file_proposal',
        'file_laporan',
        'file_dokumentasi',
        'anggota',
        'mahasiswa_terlibat',
        'catatan',
        'status_verifikasi',
        'catatan_verifikasi',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'anggota' => 'array',
        'mahasiswa_terlibat' => 'array',
        'dana' => 'decimal:2',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'verified_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
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
        return $query->where('tahun_akademik', 'like', "%$year%");
    }

    public function scopeBySemester($query, $semester)
    {
        return $query->where('semester', $semester);
    }

    public function scopeRentangTahunAkademik($query, int $startYear)
    {
        return $query->where(function ($q) use ($startYear) {
            for ($year = $startYear; $year <= now()->year; $year++) {
                $q->orWhere('tahun_akademik', 'like', $year . '%');
            }
        });
    }
}

