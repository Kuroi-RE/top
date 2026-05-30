<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProposalPrestasiOrmawa extends Model
{
    use HasFactory;

    protected $table = 'proposal_prestasi_ormawa';
    protected $primaryKey = 'id_proposal';
    public $timestamps = true;

    protected $fillable = [
        'id_user',
        'ajuan_triwulan',
        'risiko_proposal',
        'no_telepon',
        'nama_kegiatan',
        'waktu_kegiatan',
        'tempat_kegiatan',
        'besar_ajuan',
        'nomor_rekening',
        'nama_rekening',
        'nama_bank',
        'honor_pelatih',
        'file',
        'status',
        'anggaran_disetujui',
        'catatan_admin',
        'file_lpj_keuangan',
    ];

    protected $casts = [
        'waktu_kegiatan' => 'date',
        'besar_ajuan' => 'decimal:2',
        'anggaran_disetujui' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function revisions(): HasMany
    {
        return $this->hasMany(RevisiProposal::class, 'id_proposal', 'id_proposal');
    }

    public function lpj(): HasMany
    {
        return $this->hasMany(LpjKegiatan::class, 'id_proposal', 'id_proposal');
    }
}
