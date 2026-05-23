<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnggotaPrestasi extends Model
{
    use HasFactory;

    protected $table = 'anggota_prestasi';
    protected $primaryKey = 'id_anggota';
    public $timestamps = true;

    protected $fillable = [
        'id_prestasi',
        'nama',
        'nim',
        'prodi',
    ];

    // Relationships
    public function prestasi(): BelongsTo
    {
        return $this->belongsTo(Prestasi::class, 'id_prestasi', 'id_prestasi');
    }
}
