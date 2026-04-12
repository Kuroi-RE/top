<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RevisiProposal extends Model
{
    use HasFactory;

    protected $table = 'revisi_proposal';
    protected $primaryKey = 'id_revisi';
    public $timestamps = true;

    protected $fillable = [
        'id_proposal',
        'ajuan_triwulan',
        'risiko_proposal',
        'nama_kegiatan',
        'waktu_kegiatan',
        'besar_ajuan',
        'catatan_revisi',
        'file',
    ];

    protected $casts = [
        'waktu_kegiatan' => 'date',
        'besar_ajuan' => 'decimal:2',
    ];

    // Relationships
    public function proposal(): BelongsTo
    {
        return $this->belongsTo(ProposalKegiatan::class, 'id_proposal', 'id_proposal');
    }
}
