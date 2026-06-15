<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PublikasiKegiatan extends Model
{
    use HasFactory;

    protected $table = 'publikasi_kegiatans';
    protected $primaryKey = 'id_publikasi';

    protected $fillable = [
        'id_user',
        'judul',
        'ormawa',
        'caption',
        'content',
        'poster',
        'status',
        'catatan_admin',
        'placement',
    ];

    /**
     * Get the user that submitted the publication.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
