<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DosenPendampingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_dosen' => $this->id_dosen,
            'id_prestasi' => $this->id_prestasi,
            'nama_dosen' => $this->nama_dosen,
            'nidn' => $this->nidn,
            'nip' => $this->nip,
            'prodi' => $this->prodi,
            'surat_tugas' => $this->surat_tugas,
            'created_at' => $this->created_at,
        ];
    }
}
