<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProposalKegiatanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_proposal' => $this->id_proposal,
            'id_user' => $this->id_user,
            'user' => new UserResource($this->whenLoaded('user')),
            'lpj' => LpjKegiatanResource::collection($this->whenLoaded('lpj')),
            'ajuan_triwulan' => $this->ajuan_triwulan,
            'risiko_proposal' => $this->risiko_proposal,
            'no_telepon' => $this->no_telepon,
            'nama_kegiatan' => $this->nama_kegiatan,
            'waktu_kegiatan' => $this->waktu_kegiatan,
            'tempat_kegiatan' => $this->tempat_kegiatan,
            'besar_ajuan' => (float) $this->besar_ajuan,
            'nomor_rekening' => $this->nomor_rekening,
            'nama_rekening' => $this->nama_rekening,
            'nama_bank' => $this->nama_bank,
            'honor_pelatih' => $this->honor_pelatih,
            'file' => $this->file,
            'status' => $this->status,
            'anggaran_disetujui' => $this->anggaran_disetujui ? (float) $this->anggaran_disetujui : null,
            'catatan_admin' => $this->catatan_admin,
            'file_lpj_keuangan_url' => $this->file_lpj_keuangan
                ? Storage::disk('public')->url($this->file_lpj_keuangan)
                : null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
