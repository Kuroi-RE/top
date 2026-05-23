<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DosenPendamping extends Model
{
    use HasFactory;

    protected $table = 'dosen_pendamping';
    protected $primaryKey = 'id_dosen';
    public $timestamps = true;

    protected $fillable = [
        'id_prestasi',
        'nama_dosen',
        'nidn',
        'nip',
        'prodi',
        'surat_tugas',
    ];

    public function prestasi(): BelongsTo
    {
        return $this->belongsTo(Prestasi::class, 'id_prestasi', 'id_prestasi');
    }
}
