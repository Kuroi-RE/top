<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RevisiProposalResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_revisi' => $this->id_revisi,
            'id_proposal' => $this->id_proposal,
            'ajuan_triwulan' => $this->ajuan_triwulan,
            'risiko_proposal' => $this->risiko_proposal,
            'nama_kegiatan' => $this->nama_kegiatan,
            'waktu_kegiatan' => $this->waktu_kegiatan,
            'besar_ajuan' => (float) $this->besar_ajuan,
            'catatan_revisi' => $this->catatan_revisi,
            'file' => $this->file,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
