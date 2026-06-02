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
            'mewakili_ormawa' => $this->mewakili_ormawa,
            'status_verifikasi' => $this->status_verifikasi,
            'catatan_admin' => $this->catatan_admin,
            'pelaksanaan' => $this->pelaksanaan,
            'waktu_kompetisi' => $this->waktu_kompetisi,
            'tanggal_pengumuman' => $this->tanggal_pengumuman,
            'klaster' => $this->klaster,
            'jumlah_negara' => $this->jumlah_negara,
            'dokumen' => DokumenPrestasiResource::collection($this->whenLoaded('dokumen')),
            'anggota' => AnggotaPrestasiResource::collection($this->whenLoaded('anggota')),
            'dosen' => DosenPendampingResource::collection($this->whenLoaded('dosen')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
