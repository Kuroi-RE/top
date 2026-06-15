<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class PublikasiKegiatanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_publikasi' => $this->id_publikasi,
            'id_user' => $this->id_user,
            'judul' => $this->judul,
            'ormawa' => $this->ormawa,
            'caption' => $this->caption,
            'content' => $this->content,
            'poster_url' => $this->poster ? Storage::disk('public')->url($this->poster) : null,
            'status' => $this->status,
            'catatan_admin' => $this->catatan_admin,
            'placement' => $this->placement,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
            'user' => new UserResource($this->whenLoaded('user')),
        ];
    }
}
