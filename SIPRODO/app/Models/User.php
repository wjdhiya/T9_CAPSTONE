<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'nidn',
        'nip',
        'phone',
        'position',
        'department',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // Relationships
    public function penelitian()
    {
        return $this->hasMany(Penelitian::class);
    }

    public function publikasi()
    {
        return $this->hasMany(Publikasi::class);
    }

    public function pengabdianMasyarakat()
    {
        return $this->hasMany(PengabdianMasyarakat::class);
    }

    // Role constants
    public const ROLE_ADMIN = 'admin';
    public const ROLE_KAPRODI = 'kaprodi';
    public const ROLE_DOSEN = 'dosen';

    // Role checks
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isKaprodi(): bool
    {
        return $this->role === self::ROLE_KAPRODI;
    }

    public function isDosen(): bool
    {
        return $this->role === self::ROLE_DOSEN;
    }

    // Permission helper
    /**
     * Check if user can verify (admin)
     */
    public function canVerify(): bool
    {
        // jika ingin kedua role punya akses verifikasi:
        return in_array($this->role, [self::ROLE_ADMIN, self::ROLE_KAPRODI], true);
    }
}
