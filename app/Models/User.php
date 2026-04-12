<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $primaryKey = 'id_user';

    protected $fillable = [
        'username',
        'email',
        'nim',
        'nama_depan',
        'nama_belakang',
        'prodi',
        'password',
        'role',
        'is_active',
        'ormawa_type',
        'ormawa_name',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relationships
    public function proposals(): HasMany
    {
        return $this->hasMany(ProposalKegiatan::class, 'id_user', 'id_user');
    }

    public function prestasi(): HasMany
    {
        return $this->hasMany(Prestasi::class, 'id_user', 'id_user');
    }

    public function informations(): HasMany
    {
        return $this->hasMany(InformasiKegiatan::class, 'id_user', 'id_user');
    }

    // Check if user is admin
    public function isAdmin(): bool
    {
        return $this->role === 'Kemahasiswaan';
    }

    // Check if user is Super Admin
    public function isSuperAdmin(): bool
    {
        return $this->role === 'Super Admin';
    }

    // Check if user is DPMBEM
    public function isDpmbem(): bool
    {
        return $this->role === 'DPMBEM';
    }

    // Check if user is Ormawa
    public function isOrmawa(): bool
    {
        return $this->role === 'Ormawa';
    }

    // Check if user is Ormawa Institusi (UKM)
    public function isOrmawaInstitusi(): bool
    {
        return $this->role === 'Ormawa' && $this->ormawa_type === 'institusi';
    }

    // Check if user is Ormawa Prodi (Himpunan)
    public function isOrmawaProdi(): bool
    {
        return $this->role === 'Ormawa' && $this->ormawa_type === 'prodi';
    }

    // Check if user is Mahasiswa
    public function isMahasiswa(): bool
    {
        return $this->role === 'Mahasiswa';
    }
}
