<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AnggotaPrestasiResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_anggota' => $this->id_anggota,
            'id_prestasi' => $this->id_prestasi,
            'nama' => $this->nama,
            'nim' => $this->nim,
            'prodi' => $this->prodi,
            'created_at' => $this->created_at,
        ];
    }
}
