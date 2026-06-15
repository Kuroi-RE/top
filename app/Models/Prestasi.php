<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Prestasi extends Model
{
    use HasFactory;

    protected $table = 'prestasi';
    protected $primaryKey = 'id_prestasi';
    public $timestamps = true;

    protected $fillable = [
        'id_user',
        'nama_kompetisi',
        'penyelenggara',
        'tingkat',
        'capaian',
        'kategori',
        'mewakili_ormawa',
        'status_verifikasi',
        'catatan_admin',
        'pelaksanaan',
        'waktu_kompetisi',
        'tanggal_pengumuman',
        'klaster',
        'jumlah_negara',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function dokumen(): HasMany
    {
        return $this->hasMany(DokumenPrestasi::class, 'id_prestasi', 'id_prestasi');
    }

    public function anggota(): HasMany
    {
        return $this->hasMany(AnggotaPrestasi::class, 'id_prestasi', 'id_prestasi');
    }

    public function dosen(): HasMany
    {
        return $this->hasMany(DosenPendamping::class, 'id_prestasi', 'id_prestasi');
    }

    // Scopes
    public function scopeByUser($query, $userId)
    {
        return $query->where('id_user', $userId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status_verifikasi', $status);
    }
}
