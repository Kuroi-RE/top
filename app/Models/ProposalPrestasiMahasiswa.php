<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProposalPrestasiMahasiswa extends Model
{
    use HasFactory;

    protected $table = 'proposal_prestasi_mahasiswa';
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

    // Note: Relationships to Revisi and LPJ will need adjustment if those tables remain shared.
}
