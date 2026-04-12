<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PrestasiResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_prestasi' => $this->id_prestasi,
            'id_user' => $this->id_user,
            'user' => new UserResource($this->whenLoaded('user')),
            'nama_kompetisi' => $this->nama_kompetisi,
            'penyelenggara' => $this->penyelenggara,
            'tingkat' => $this->tingkat,
            'capaian' => $this->capaian,
            'kategori' => $this->kategori,
            'status_verifikasi' => $this->status_verifikasi,
            'dokumen' => DokumenPrestasiResource::collection($this->whenLoaded('dokumen')),
            'anggota' => AnggotaPrestasiResource::collection($this->whenLoaded('anggota')),
            'dosen' => DosenPendampingResource::collection($this->whenLoaded('dosen')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
