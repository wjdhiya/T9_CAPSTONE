<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
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

    // Helper methods
    public function isSuperAdmin()
    {
        return $this->role === 'super_admin';
    }

    public function isKaprodi()
    {
        return $this->role === 'kaprodi';
    }

    public function isDosen()
    {
        return $this->role === 'dosen';
    }

    public function canVerify()
    {
        return in_array($this->role, ['super_admin', 'kaprodi']);
    }
}
