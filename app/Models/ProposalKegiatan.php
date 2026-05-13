<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProposalKegiatan extends Model
{
    use HasFactory;

    protected $table = 'proposal_kegiatan';
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
        'category',
    ];

    protected $casts = [
        'waktu_kegiatan' => 'date',
        'besar_ajuan' => 'decimal:2',
        'anggaran_disetujui' => 'decimal:2',
    ];

    // Relationships
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

    // Scopes
    public function scopeByUser($query, $userId)
    {
        return $query->where('id_user', $userId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByTriwulan($query, $triwulan)
    {
        return $query->where('ajuan_triwulan', $triwulan);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}
