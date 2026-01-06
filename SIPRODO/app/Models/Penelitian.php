<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Casts\SafeArray;

class Penelitian extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'penelitian';

    protected $fillable = [
        'user_id',
        'judul_penelitian',
        'abstrak',
        'jenis',
        'sumber_dana',
        'anggaran',
        'tahun',
        'semester',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
        'status_verifikasi',
        'file_proposal',
        'file_laporan',
        'anggota',
        'mahasiswa_terlibat',
        'catatan',
        'catatan_verifikasi',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'anggota' => SafeArray::class,
        'mahasiswa_terlibat' => SafeArray::class,
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

    public function publikasi()
    {
        return $this->hasMany(Publikasi::class);
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

    public function scopeRentangTahun($query, int $startYear)
    {
        return $query->where(function ($q) use ($startYear) {
            for ($year = $startYear; $year <= now()->year; $year++) {
                $q->orWhere('tahun', 'like', $year . '%');
            }
        });
    }

    public function scopeBySemester($query, $semester)
    {
        return $query->where('semester', $semester);
    }
}

