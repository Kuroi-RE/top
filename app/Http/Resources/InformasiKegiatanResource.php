<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InformasiKegiatanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_informasi' => $this->id_informasi,
            'id_user' => $this->id_user,
            'user' => new UserResource($this->whenLoaded('user')),
            'judul' => $this->judul,
            'role' => $this->role,
            'caption' => $this->caption,
            'file' => $this->file,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
