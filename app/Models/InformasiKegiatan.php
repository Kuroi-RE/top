<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InformasiKegiatan extends Model
{
    use HasFactory;

    protected $table = 'informasi_kegiatan';
    protected $primaryKey = 'id_informasi';
    public $timestamps = true;

    protected $fillable = [
        'id_user',
        'judul',
        'role',
        'caption',
        'file',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    // Scopes
    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }
}
