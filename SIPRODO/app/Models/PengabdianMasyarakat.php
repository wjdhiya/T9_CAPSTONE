<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Casts\SafeArray;

class PengabdianMasyarakat extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pengabdian_masyarakat';

    protected $fillable = [
        'user_id',
        'judul_pkm',
        'deskripsi',
        'jenis_hibah',
        'sumber_dana',
        'anggaran',
        'tahun',
        'semester',
        'tanggal_mulai',
        'tanggal_selesai',
        'skema',
        'mitra',
        'jumlah_peserta',
        'status',
        'file_proposal',
        'file_laporan',
        'file_dokumentasi',
        'tim_abdimas',
        'dosen_nip',
        'anggota_mahasiswa',
        'mahasiswa_nim',
        'catatan',
        'status_verifikasi',
        'catatan_verifikasi',
        'verified_by',
        'verified_at',
        'sdg',
        'kesesuaian_roadmap_kk',
        'tipe_pendanaan',
        'status_kegiatan',
    ];

    protected $casts = [
        'tim_abdimas' => SafeArray::class,
        'dosen_nip' => SafeArray::class,
        'anggota_mahasiswa' => SafeArray::class,
        'mahasiswa_nim' => SafeArray::class,
        'anggaran' => 'decimal:2',
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
        return $query->where('tahun', 'like', "%$year%");
    }

    public function scopeBySemester($query, $semester)
    {
        return $query->where('semester', $semester);
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

