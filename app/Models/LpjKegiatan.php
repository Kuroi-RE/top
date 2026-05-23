<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LpjKegiatan extends Model
{
    use HasFactory;

    protected $table = 'lpj_kegiatan';
    protected $primaryKey = 'id_lpj';
    public $timestamps = true;

    protected $fillable = [
        'id_proposal',
        'file_lpj',
        'status_lpj',
        'tanggal_upload',
    ];

    protected $casts = [
        'tanggal_upload' => 'date',
    ];

    // Relationships
    public function proposal(): BelongsTo
    {
        return $this->belongsTo(ProposalKegiatan::class, 'id_proposal', 'id_proposal');
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status_lpj', $status);
    }
}
