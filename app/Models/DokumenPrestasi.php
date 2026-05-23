<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DokumenPrestasi extends Model
{
    use HasFactory;

    protected $table = 'dokumen_prestasi';
    protected $primaryKey = 'id_dokumen';
    public $timestamps = true;

    protected $fillable = [
        'id_prestasi',
        'jenis_dokumen',
        'file',
    ];

    // Relationships
    public function prestasi(): BelongsTo
    {
        return $this->belongsTo(Prestasi::class, 'id_prestasi', 'id_prestasi');
    }
}
