<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DokumenPrestasiResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_dokumen' => $this->id_dokumen,
            'id_prestasi' => $this->id_prestasi,
            'jenis_dokumen' => $this->jenis_dokumen,
            'file' => $this->file,
            'created_at' => $this->created_at,
        ];
    }
}
